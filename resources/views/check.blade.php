@extends('layouts.app')
@section('title-page')Home @endsection

@section('side')
<ul>
@foreach($data as $key => $row)
	<li><a href="/check?parent_id={{ $key }}" > {{ $row['name'] }} ({{$row['count']}}) </a>
	<?php if(isset($row['array'])) { ?>
		@include('ul', ['array'=>$row['array']])
	<?php } ?>
	</li>
@endforeach
</ul>
@endsection

@section('content')
<div class="row">
	<span> Сортировать: </span>
	<a href="/check?parent_id={{$parent_id}}&sort=nameAsc" class="sort" value="nameAsc" > По названию v </a>
	<a href="/check?parent_id={{$parent_id}}&sort=nameDesc" class="sort" value="nameDesc" > По названию ^ </a>
	<a href="/check?parent_id={{$parent_id}}&sort=priceAsc" class="sort" value="priceAsc" > По цене v </a>
	<a href="/check?parent_id={{$parent_id}}&sort=priceDesc" class="sort" value="priceDesc" > По цене ^ </a>
	
		<select id="paginationSelect" name="pagination">
		  <option value="6">6</option>
		  <option value="12">12</option>
		  <option value="18">18</option>
		</select>
		<label for="pagination">
		Количество записей на странице
		</label> <p><input id="pagination" href="/check?parent_id={{$parent_id}}&sort={{$sort}}" type="button" value="Отправить"></p>
	
</div>

<section class="articles">
	@include('load')
</section>
 
@endsection