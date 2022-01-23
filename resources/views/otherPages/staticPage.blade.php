@extends('layout.app_with_web_view')
@section('title','Static Page')
@section('content')
	<div class="container">
		<div class="row static_box">
			<div class="col-12 static_title">{{$data->desc->cms_title}}</div>
			<div class="col-12 static_body">{!! $data->desc->cms_description !!}</div>
			<div class="col-12 static_date">Updated On: {{ date('d-M-Y | h:i A',strtotime($data->created_at)) }}</div>
		</div>
	</div>

@endsection