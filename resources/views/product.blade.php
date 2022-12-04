@extends('layouts.app2')
@section('title-page')Home @endsection

@section('breadcrumbs')
	<a href='/check'>Главная </a>  
	@foreach($breadcrumbs as $breadcrumb)
		-><a href='/check?id={{$breadcrumb["id"]}}'>{{$breadcrumb["name"]}} </a>  
	@endforeach
@endsection

@section('content')
	<h1>
	{{$product['name']}}
	</h1>
	<BR>
	<h2>
	Цена: {{$product['price']}} руб.
	</h2>
@endsection