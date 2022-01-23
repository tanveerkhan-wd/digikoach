@extends('layout.app_with_login')
@section('title','View Question')
@section('script', url('public/js/dashboard/questions.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-md-4 mb-3">
                <h2 class="title"><a  class="ajax_request" data-slug="admin/questions" href="{{url('/admin/questions')}}"><span>{{'Question Bank'}}</span></a>  >  {{'View'}}</h2>
            </div>  
            <div class="col-md-3 text-md-right mb-3">

            </div>
            <div class="col-md-2 col-6 mb-3">
            </div>
            <div class="col-md-3 text-md-right mb-3">
                <a class="theme_btn ajax_request no_sidebar_active" data-slug="admin/questions" href="{{ url('admin/questions') }}">{{'Back'}}</a>
                
            </div>  
        </div>
        <div class="white_box">
            <div class="theme_tab">
                    
                <form name="add-live-test-form">
                    <div class="row">
                        <div class="col-md-2 col-lg-2">
                            <label class="h6 live_test_lable"><b> Category : </b></label>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <label class="h6"> {{$getQuestions['category_name'] ?? ''}} </label>
                        </div>
                        <div class="col-md-2 col-lg-2">
                            <label class="h6 live_test_lable"><b> Question Type : </b></label>
                        </div>
                        <div class="col-md-2 col-lg-2">
                            <label class="h6"> {{ $getQuestions['question_type'] ?? ''}} </label>
                        </div>

                        <div class="col-md-2 col-lg-2">
                            <label class="h6 live_test_lable"><b> Marks : </b></label>
                        </div>
                        <div class="col-md-1 col-lg-1">
                            <label class="h6"> {{ $getQuestions['marks'] ?? ''}} </label>
                        </div>
                    </div> 
                    <br>
                        @php $que_index = 0 @endphp
                        @foreach($getQuestions['question_both_lang'] as $ques_val)
                        <div class="row live_test_que_cat mt-3">
                            <div class="col-12">{{ $ques_val['lang_code']=='en'?'Question in English':'Question in Hindi'}} </div>
                        </div>
                            <div class="row mt-3 row mb-3 ">
                                <div class="col-md-1 col-lg-1 bold">Ques : </div>
                                <div class="col-md-11 col-lg-11 bold">
                                    {!! $ques_val['question_text'] ?? ''!!}
                                    <div class="show_que_images">
                                            @foreach($getQueMedia as $media)
                                                @if($media['media_int_type']=='QUESTION' && $media['lang_code']==$ques_val['lang_code'])
                                                    <img src="{{ url('public/storage/'.Config::get('siteglobal.images_dirs.QUESTIONS')) }}{{'/'.$media['media_file']}}" style="max-height: 200px;width: auto;">
                                                @endif
                                            @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 ">
                                <div class="col-md-1 col-lg-1 bold">Option:</div>
                                <div class="col-md-11 col-lg-11">
                                    @foreach($getOptions as $key => $opt_val)
                                    <div class="row ml-2 mt-3 mb-2">
                                            <div class="form-check form-check-inline">
                                                <label class="main @if($opt_val['is_valid']==true)active_option @endif"> 
                                                    @if(!empty($opt_val['question_option_desc']))
                                                        @foreach($opt_val['question_option_desc'] as $desc_val)
                                                            @if($desc_val['lang_code']==$ques_val['lang_code'])
                                                                {!! $desc_val['option_text'] ?? '' !!}
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    <div class="show_que_images">
                                                        @foreach($getQueMedia as $media)
                                                            @if($media['media_int_type']=='OPTION' && $media['lang_code']==$ques_val['lang_code'] && $desc_val['question_options_id']==$media['media_int_id'])
                                                    
                                                                <img src="{{ url('public/storage/'.Config::get('siteglobal.images_dirs.QUESTIONS')) }}{{'/'.$media['media_file']}}" style="max-height: 100px;width: auto;">
                                                            
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <input type="checkbox" @if($opt_val['is_valid']==true) checked @endif disabled> 
                                                    <span class="geekmark"></span> 
                                                </label>
                                            </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-1 col-lg-1 bold">Sol. </div>
                                <div class="col-md-11 col-lg-11">
                                    <div class="border-live-test-sol"> 
                                        {!! $ques_val['solution_text'] ?? '' !!} 
                                        <div class="show_que_images">
                                        @foreach($getQueMedia as $media)
                                            @if($media['media_int_type']=='SOLUTION' && $media['lang_code']==$ques_val['lang_code'])
                                    
                                                <img src="{{ url('public/storage/'.Config::get('siteglobal.images_dirs.QUESTIONS')) }}{{'/'.$media['media_file']}}" style="max-height: 200px;width: auto;">
                                            @endif
                                        @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                </form>
            </div>
        </div>
    </div>
</div> 

@endsection

@push('custom-styles')
    <style type="text/css">
       .main { display: block; position: relative; padding-left: 45px; margin-bottom: 15px; cursor: pointer; font-size: 20px; } 
        input[type=radio] {visibility: hidden;} 
        .geekmark { position: absolute; top: 0; left: 0; 
        height: 25px; width: 25px; background-color: white; border: 1px solid green; } 
        .main input:checked ~ .geekmark { background-color: green; } 
        .geekmark:after { content: ""; position: absolute; display: none; }
        .main input:checked ~ .geekmark:after { display: block; } 
        .main .geekmark:after { left: 8px; bottom: 5px; width: 6px; height: 12px; border: solid white; border-width: 0 4px 4px 0; -webkit-transform: rotate(45deg); -ms-transform: rotate(45deg); transform: rotate(45deg); } 
    </style>
@endpush
@push('custom-scripts')
<script type="text/javascript">
    $(function() {
      showLoader(false);
    });
</script>
<script src="{{ url('public/bower_components/ckeditor/ckeditor.js') }}"></script>

<script type="text/javascript" src="{{ url('public/js/dashboard/questions.js') }}"></script>
@endpush