@extends('layout.app_with_web_view')
@section('title','About Us')
@section('content')

    <style type="text/css">
    body{
    	font-family:Karla;
    }
    .blog_box {
		margin: 30px 10px;
	}
	.blog_box .blog_body pre {
	    display: inline-block;
	    word-break: break-word;
	    white-space: pre-wrap;
	}
	.blog_box .blog_title {
	    font-size: 18px;
	    text-align: left;
	    margin-bottom: 20px;
	    font-weight: bold;
	    line-height: 1.4;
	}
	.blog_box .blog_body {
	    font-size: 16px;
	}
	.blog_box .blog_body  p {
	    margin-bottom: 5px;
	    text-align: justify;
	}
	img {
	    height: auto !important;
	    width:  auto !important;
	    max-width: 100%;
	    max-height: 200px;
	}
	.blog_date {
		font-size: 11px;
	    text-align: right;
	    font-weight: bold;
	    padding-bottom: 9px;
	}
	.blog_image {
	    text-align: center;
	    margin-bottom: 20px;
	}
	.blog_box {
    	margin: 30px 10px;
	}
    </style>
</head>
<body>

	<div class="container">
		<div class="row blog_box">
			@if(isset($blog) && !empty($blog) || $blog!=null )
			<div class="col-12 blog_image"><img src="@if(!empty($blog->blog_image)){{ url('public/storage/'.Config::get('siteglobal.images_dirs.BLOG')) }}/{{$blog->blog_image ?? ''}} @else {{ url('public/images/pic_default.png') }} @endif" alt="Image not Available"></div>
			<div class="col-12 blog_date">Updated On: {{ date('d-M-Y | h:i A',strtotime($blog->created_at)) ?? ''}}</div>
			<div class="col-12 blog_title">{{$blog->desc->blog_post_title ?? ''}}</div>
			<div class="col-12 blog_body">{!! $blog->desc->description ?? '' !!}</div>
			@else
			This Blog is not Available!
			@endif
		</div>
	</div>


@endsection