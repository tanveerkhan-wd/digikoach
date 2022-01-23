@extends('layout.app_with_web_view')
@section('title','About Us')
@section('content')
	
	<div class="container">
		<div class="row about_box">
			<div class="col-12 about_image"><img src="@if(!empty($data['image'])){{ url('public/storage/'.Config::get('siteglobal.images_dirs.SETTING').'/'.$data['image']) }} @else {{ url('public/images/pic_default.png') }} @endif"></div>
			<div class="col-12 about_title">{{$data['title']}}</div>
			<div class="col-12 about_body">{!! $data['tag_line'] !!}</div>
		</div>
	</div>

@endsection