@extends('layout.app_with_login')
@section('title','View Doubt')
@section('script', url('public/js/dashboard/doubt.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
  <div class="container-fluid">
    <div class="row ">
        <div class="col-md-4 mb-3">
            <h2 class="title"><a  class="ajax_request" data-slug="admin/doubt" href="{{url('/admin/doubt')}}"><span>{{'Doubt'}}</span></a>  >  {{'View'}}</h2>
        </div>  
        <div class="col-md-3 text-md-right mb-3">

        </div>
        <div class="col-md-3 text-md-right mb-3">
            
        </div>  
        <div class="col-md-2 col-6 mb-3">
            <a href="{{ url('admin/doubt') }}"><button class="theme_btn show_modal full_width small_btn">{{'Back'}}</button></a>
        </div>
    </div>
        <div class="white_box">
            <div class="theme_tab">                    
                <form name="add-doubt-form">
                    <input type="hidden" name="pkCat" id="pkCat" value="{{$data->doubt_id}}">
                    <div class="row">
                        <div class="col-lg-1 col-md-1"></div>
                        <div class="col-lg-10 col-md-10">
                        <div class="doubt_heading"> Doubt </div>
                            <div class="text-left row align-item-center">
                                <div class="col-md-8">
                                    <div class="doubt_pic_name">
                                        <div class="profile_box">
                                            <a href="{{url('admin/viewAppUser/')}}{{'/'.$data->user_id}}">
                                                <div class="profile_pic" style="height: 55px !important;width: 55px !important;box-shadow:none !important;">
                                                    <img src="@if(!empty($data->user->user_photo)) {{ url('public/storage/'.Config::get('siteglobal.images_dirs.USERS').'/'.$data->user->user_photo) }} @else{{ url('public/images/user.png') }} @endif">
                                                </div>
                                            </a>
                                        </div>
                                        <div class="doubt_user_name text-left">
                                            <strong><a href="{{url('admin/viewAppUser/')}}{{'/'.$data->user_id}}">{{ $data->user->name ?? 'NA' }}</a></strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-md-0 mt-2">
                                    <div class="doubt_user_date text-md-right">
                                        {{ date('d-M-Y | h:i A',strtotime($data->created_at)) ?? 'NA' }}
                                    </div>
                                </div>
                            </div>

                            <div class="doubt_text pt-3 pl-3">
                                <label class="mb-3">{{ $data->doubt_text }}</label> 
                                <div class="doubt_answers_upvote_box mb-3">
                                    <a href="javascript::void(0)">Upvotes  {{$data->doubt_upvote}}</a> 
                                    <a href="javascript::void(0)" class="ml-3">Answer {{$data->total_answers}}</a> 
                                </div>                     
                            </div>
                            
                            @if(!empty($data->doubt_image))
                            <div class="text-center">
                                <div class="profile_box text-center">
                                    <div class="doubt_pic">
                                        <img id="cat_img" src="@if(!empty($data->doubt_image)) {{ url('public/storage/'.Config::get('siteglobal.images_dirs.DOUBT').'/'.$data->doubt_image) }} @endif">
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="answers_box">
                                <div class="doubt_heading h5 pt-3"> Answers </div>
                                @if(!$data->answers->isEmpty())
                                @foreach($data->answers as $ansData)
                                    <div class="row align-item-center">
                                        <div class="col-md-8">
                                            <div class="doubt_pic_name">
                                                <div class="profile_box">
                                                    <a href="{{url('admin/viewAppUser/')}}{{'/'.$ansData->user_id}}">
                                                        <div class="profile_pic" style="height: 55px !important;width: 55px !important;box-shadow:none !important;">
                                                            <img src="@if(!empty($ansData->user->user_photo)) {{ url('public/storage/'.Config::get('siteglobal.images_dirs.USERS').'/'.$ansData->user->user_photo) }} @else{{ url('public/images/user.png') }} @endif">
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="doubt_user_name text-left">
                                                    <strong><a href="{{url('admin/viewAppUser/')}}{{'/'.$ansData->user_id}}">{{ $ansData->user->name ?? 'NA' }}</a></strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mt-md-0 mt-2">
                                            <div class="doubt_user_date text-md-right">
                                                {{ date('d-M-Y | h:i A',strtotime($ansData->created_at)) ?? 'NA' }}
                                            </div>
                                        </div>
                                    </div>
                                    <a class="show_reply_modal" data-toggle="modal" data-target="#add_new"></a>
                                    <div class="doubt_answers mt-3 pl-3">
                                        <label class="mb-3">{!! $ansData->doubt_answer !!}</label>
                                        <div class="doubt_answers_upvote_box mb-3">
                                            <a href="javascript::void(0)">Upvotes  {{$ansData->answer_upvote}}</a> 
                                            <a href="javascript::void(0)" class="ml-3 show_model_popup" data-id="{{$ansData->answer_id}}">Replies  {{$ansData->total_replies}}</a> 
                                        </div>                     
                                    </div>
                                @endforeach
                                @endif
                            </div>
                            @php
                                $accessPriData = session()->get('accessPriData');
                            @endphp
                            
                            @if(Auth::user()->user_type==0 || !empty($accessPriData['Doubts']) && $accessPriData['Doubts']->answer==true)
                            <div class="form-group pt-5">
                                <label><h5>{{'Add Answer'}}</h5></label>
                                <textarea class="form-control icon_control" name="new_answer" id="new_answer" rows="3"></textarea>
                            </div>

                            <div class="text-center modal_btn ">
                                <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/doubt" href="{{ url('admin/doubt') }}">{{$translations['gn_cancel'] ?? 'Cancel'}}</a>
                                <button type="submit" class="theme_btn">{{'Post Answer'}}</button>
                            </div>
                            @endif
                        </div>
                        <div class="col-lg-1 col-md-1"></div>
                    </div>
                </form>
                
            </div>
        </div>
      </div>
</div>

<!-- Add New Popup -->
<div class="theme_modal modal fade" id="add_new" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <img src="{{url('public/images/ic_close_bg.png')}}" class="modal_top_bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="{{url('public/images/ic_close_circle_white.png')}}">
                </button>
                <div class="row">
                    <div class="col-lg-12">
                        <h5 class="modal-title" id="exampleModalCenterTitle">{{'Replies'}}</h5>
                        <div class="answers_box_popup">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    

@endsection

@push('custom-scripts')
<script type="text/javascript">
    $(function() {
      showLoader(false);
    });
</script>
{{-- <script src="https://cdn.ckeditor.com/4.15.0/basic/ckeditor.js"></script> --}}
<script src="{{ url('public/bower_components/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
    CKEDITOR.replace('new_answer', {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
</script>
<script type="text/javascript" src="{{ url('public/js/dashboard/doubt.js') }}"></script>
@endpush