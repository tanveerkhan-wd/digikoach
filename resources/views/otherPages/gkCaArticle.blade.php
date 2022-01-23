@extends('layout.app_with_web_view')
@section('title','About Us')
@section('content')
</head>
<body>

	<div class="container">
		<div class="row article_box">
			<div class="col-12 article_title">{{$article->desc->article_title}}</div>
			<div class="col-12 article_body">{!! $article->desc->article_body !!}</div>
			<div class="col-12 article_date">Updated On: {{ date('d-M-Y',strtotime($article->created_at)) }}</div>
		</div>
	</div>


@endsection