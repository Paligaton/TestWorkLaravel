<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Groups;
use App\Models\Products;

class MainController extends Controller
{
    public function index(Request $req){
		$parent_id = "0";
		$sort = 'nameAsc';
		if($req->has('parent_id')){
			$parent_id = $req->parent_id;
		}
		$pagination=6;
		if($req->has('pagination')){
			$pagination = $req->pagination;
		}
		$data = $this->getList($parent_id);
		$ids = $this->getChildrenGroups($parent_id);
		if($req->has('sort')){
			$sort = $req->sort;
		}
		$products = Products::join('prices', 'prices.id_product', '=', 'products.id')->whereIn('products.id_group', $ids);
		switch ($sort)
		{
			case 'nameAsc':
				$products = $products->orderBy('products.name');
				break;
			case 'nameDesc':
				$products = $products->orderByDesc('products.name');
				break;
			case 'priceAsc':
				$products = $products->orderBy('prices.price');
				break;
			case 'priceDesc':
				$products = $products->orderByDesc('prices.price');
				break;
		}
		$products = $products->paginate($pagination);
		if ($req->ajax()) {
            return view('load', ['products'=>$products])->render();  
        }
		//dd($products);		
		return view('check', ['data'=>$data, 'products'=>$products, 'parent_id'=>$parent_id, 'sort'=>$sort]);
	}
	
	private	function getChildrenGroups($parent_id)
	{
		$recursive =  DB::select('with recursive cte (id, name, parent_id) as (
  select     id,
             name,
             id_parent
  from       groups
  where      id_parent = '.$parent_id.'
  union all
  select     p.id,
             p.name,
             p.id_parent
  from       groups p
  inner join cte
          on p.id_parent = cte.id
)
select id from cte;');
		$array = [];
		foreach($recursive as $row)
		{
			$array[] = $row->id;
		}
		$array[]=$parent_id;
		return $array;
	}
	
	private function getList($parent_id, $parent=0, $array=[])
	{
		if(!$parent)
		{
			if($parent_id)
			{
				$parent = $parent_id;
				$data = Groups::where('id_parent', '=', $parent_id)->get();
				foreach($data as $row)
				{
					$array[$row->id] = $row;
					$ids = $this->getChildrenGroups($row->id);
					$array[$row->id]['count'] = Products::whereIn('id_group', $ids)->count();
					
				}
				$parent_id = Groups::find($parent_id);
				return $this->getList($parent_id->id_parent, $parent, $array);
			}
			else
			{
				$data = Groups::where('id_parent', '=', $parent_id)->get();
				foreach($data as $row)
				{
					$array[$row->id] = $row;
					$ids = $this->getChildrenGroups($row->id);
					$array[$row->id]['count'] = Products::whereIn('id_group', $ids)->count();
				}
				return $array;
			}
		}
		else
		{
			if($parent_id!=0)
			{
				$array2 = [];
				$data = Groups::where('id_parent', '=', $parent_id)->get();
				foreach($data as $row)
				{
					$array2[$row->id] = $row;
					$ids = $this->getChildrenGroups($row->id);
					$array2[$row->id]['count'] = Products::whereIn('id_group', $ids)->count();
					if($row->id=$parent)
					{
						$array2[$row->id]['array']=$array;
						$parent_id = $row->id_parent;
					}
				}
				$parent = $parent_id;
				$parent_id = Groups::find($parent_id);
				return $this->getList($parent_id->id_parent, $parent, $array2);
			}
			else
			{
				$array2 = [];
				$data = Groups::where('id_parent', '=', $parent_id)->get();
				foreach($data as $row)
				{
					$array2[$row->id] = $row;
					$ids = $this->getChildrenGroups($row->id);
					$array2[$row->id]['count'] = Products::whereIn('id_group', $ids)->count();
					if($row->id=$parent)
					{
						$array2[$row->id]['array']=$array;
					}
				}
				return $array2;
			}
		}
	}

	public function product(Request $req)
	{
		if($req->has('id')){
			$id = $req->id;
			$product = Products::join('prices', 'prices.id_product', '=', 'products.id')->find($id);
			$breadcrumbs = $this->getBreadCrumbs($product['id_group']);
			return view('product', ['product'=>$product, 'breadcrumbs'=>$breadcrumbs ]);
		}
		else
		{
			return redirect('/check');
		}
		
	}
	
	private function getBreadCrumbs($parent_id, $array=[])
	{
		if($parent_id)
		{
			$group = Groups::find($parent_id);
			$parent_id = $group['id_parent'];
			$array[] = $group;
			return $this->getBreadCrumbs($parent_id, $array);
		}
		else
		{
			return array_reverse($array);
		}
	}

}
