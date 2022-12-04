<ul>
@foreach($array as $key => $row)
	<li><a href="/check?parent_id={{ $key }}" > {{ $row['name'] }} ({{$row['count']}}) </a>
		<?php if(isset($row['array'])) { ?>
			@include('ul', ['array'=>$row['array']])
		<?php } ?>
	</li>
@endforeach
</ul>