
	@foreach($products as $key => $row)
		
		<div class="row"><a href="/product?id={{$row['id_product']}}"> {{ $row['name'] }} </a> <span> - {{$row['price']}} руб.</span></div>
		
	@endforeach

<div class="row">
	{{$products->appends(request()->except('page'))->links('pagination')}}
</div>