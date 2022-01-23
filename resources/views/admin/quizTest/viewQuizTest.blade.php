@extends('layout.app_with_login')
@section('title','View Quiz Test')
@section('script', url('public/js/dashboard/quiz_test.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-md-4 mb-3">
                <h2 class="title"><a  class="ajax_request" data-slug="admin/quizTest" href="{{url('/admin/quizTest')}}"><span>{{'Quiz Test '}}</span></a>  >  {{'View'}}</h2>
            </div>  
            <div class="col-md-3 text-md-right mb-3">

            </div>
            <div class="col-md-3 text-md-right mb-3">
                
            </div>  
            <div class="col-md-2 col-6 mb-3">
                <a href="{{ url('admin/quizTest') }}"><button class="theme_btn show_modal full_width small_btn">{{'Back'}}</button></a>
            </div>
        </div>
        <div class="white_box">
            <div class="theme_tab">
                    
                <form name="add-live-test-form">
                    <div class="row">
                        <div class="col-md-2 col-lg-2">
                            <label class="h6 live_test_lable"><b> Test Name : </b></label>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <label class="h6">
                            {{$getExam['desc']['exam_name'] ?? ''}}
                            </label>
                        </div>
                        <div class="col-md-2 col-lg-2">
                            <label class="h6 live_test_lable"><b> Total Question : </b></label>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <label class="h6"> {{$no_of['quetion'] ?? ''}} </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 col-lg-2">
                            <label class="h6 live_test_lable"><b> Category : </b></label>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <label class="h6"> {{ $cate ?? ''}} </label>
                        </div>

                        <div class="col-md-2 col-lg-2">
                            <label class="h6 live_test_lable"><b> Total Marks: </b></label>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <label class="h6"> {{$getExam['total_marks'] ?? ''}} </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 col-lg-2">
                        
                        </div>
                        <div class="col-md-4 col-lg-4">
                            
                        </div>

                        <div class="col-md-2 col-lg-2">
                            <label class="h6 live_test_lable"><b> Duration: </b></label>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <label class="h6"> {{ $getExam['exam_duration'] ?? '' }} {{ 'Mins' }} </label>
                        </div>
                    </div> 

                    @foreach($getQueCate as $getCate)
                        <div class="row live_test_que_cat mt-3">
                            <div class="col-12">{{ $getCate['category_desc']['name'] ?? ''}} </div>
                        </div>
                        @php $que_index = 0 @endphp
                        @foreach($testData as $key => $value)
                        @if($getCate['category_id'] ==  $value['category_id'])
                            <div class="row mt-3">
                                <div class="col-md-1 col-lg-1 bold">Ques_{{++$que_index}}: </div>
                                <div class="col-md-11 col-lg-11 bold">
                                    {!! $value['question'] ?? '' !!}

                                    <div class="show_que_images">
                                        @if(!empty($value['question_media']))
                                        @foreach($value['question_media'] as $media)
                                            @if($media['media_int_type']=='QUESTION' && $media['media_int_id']==$value['questions_id'])
                                    
                                                <img src="{{ url('public/storage/'.Config::get('siteglobal.images_dirs.QUESTIONS')) }}{{'/'.$media['media_file']}}" style="max-height: 120px;width: auto;">
                                            @endif
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-lg-1 bold">Options:</div>
                                <div class="col-md-11 col-lg-11">
                                    <div class="row mt-3 mb-2">
                                        @foreach($value['option'] as $op_key => $op_value)
                                        <div class="col-md-6 col-lg-6 text-left">
                                            <div class="form-check form-check-inline">
                                                <label class="main @if($op_value['is_valid']==true)active_option @endif"> 
                                                    @if(!empty($op_value['desc']))
                                                        @if($op_value['desc']['lang_code']=='en')
                                                            {!! $op_value['desc']['option_text'] ?? '' !!}

                                                                <div class="show_que_images">
                                                                    
                                                                    @if(!empty($value['option_media']))
                                                                    @foreach($value['option_media'] as $media)
                                                                        @foreach($media as $media_val)
                                                                        @if($media_val['media_int_type']=='OPTION' && $media_val['media_int_id']==$op_value['desc']['question_options_id'])
                                                                
                                                                            <img src="{{ url('public/storage/'.Config::get('siteglobal.images_dirs.QUESTIONS')) }}{{'/'.$media_val['media_file']}}" style="max-height: 120px;width: auto;">
                                                                        @endif
                                                                        @endforeach
                                                                    @endforeach
                                                                    @endif
                                                                </div>
                                                        @endif
                                                    @endif
                                                    <input type="checkbox" @if($op_value['is_valid']==true) checked @endif disabled> 
                                                    <span class="geekmark"></span> 
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-lg-1 bold">Sol. </div>
                                <div class="col-md-11 col-lg-11"><div class="border-live-test-sol"> {!! $value['solution'] ?? '' !!} 
                                    @if(!empty($value['question_media']))
                                    @foreach($value['question_media'] as $media)
                                        @if($media['media_int_type']=='SOLUTION' && $media['media_int_id']==$value['questions_id'])
                                
                                            <img src="{{ url('public/storage/'.Config::get('siteglobal.images_dirs.QUESTIONS')) }}{{'/'.$media['media_file']}}" style="max-height: 120px;width: auto;">
                                        @endif
                                    @endforeach
                                    @endif
                                </div></div>
                            </div>
                        @endif
                        @endforeach
                    @endforeach
                </form>
            </div>
        </div>
    </div>
</div> 

@endsection

@push('custom-styles')
    <style type="text/css">
       .main { 
            display: block; 
            position: relative; 
            padding-left: 45px; 
            margin-bottom: 15px; 
            cursor: pointer; 
            font-size: 20px; 
        } 
          
        /* Hide the default checkbox */ 
        input[type=checkbox] { 
            visibility: hidden; 
        } 
          
        /* Creating a custom checkbox 
        based on demand */ 
        .geekmark { 
            position: absolute; 
            top: 0; 
            left: 0; 
            height: 25px; 
            width: 25px; 
            background-color: white; 
            border: 1px solid green; 
        } 
          
        /* Specify the background color to be 
        shown when checkbox is checked */ 
        .main input:checked ~ .geekmark { 
            background-color: green; 
        } 
          
        /* Checkmark to be shown in checkbox */ 
        /* It is not be shown when not checked */ 
        .geekmark:after { 
            content: ""; 
            position: absolute; 
            display: none; 
        } 
          
        /* Display checkmark when checked */ 
        .main input:checked ~ .geekmark:after { 
            display: block; 
        } 
          
        /* Styling the checkmark using webkit */ 
        /* Rotated the rectangle by 45 degree and  
        showing only two border to make it look 
        like a tickmark */ 
        .main .geekmark:after { 
            left: 8px; 
            bottom: 5px; 
            width: 6px; 
            height: 12px; 
            border: solid white; 
            border-width: 0 4px 4px 0; 
            -webkit-transform: rotate(45deg); 
            -ms-transform: rotate(45deg); 
            transform: rotate(45deg); 
        } 
    </style>
@endpush
@push('custom-scripts')
<script type="text/javascript">
    $(function() {
      showLoader(false);
    });
</script>

<script type="text/javascript" src="{{ url('public/js/dashboard/quiz_test.js') }}"></script>
@endpush