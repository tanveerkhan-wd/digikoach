@extends('layout.app_with_login')
@section('title','User Quiz Test')
@section('content')	
<!-- Page Content  -->
<div class="section">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-md-6 mb-3">
                <h2 class="title"><a  class="ajax_request" data-slug="admin/quizTest" href="{{url('/admin/quizTest')}}"><span>{{'Quiz Test'}}</span></a>  > <a href="{{url('/admin/quizTest/appearedStudents')}}{{'/'.$data['exam_id']}}"><span>{{'Appeared Student List'}}</span></a> > {{'View'}}</h2>
            </div>
            <div class="col-md-4 text-md-right mb-3">
                
            </div>  
            <div class="col-md-2 col-6 mb-3">
                <a href="{{url('/admin/quizTest/appearedStudents')}}{{'/'.$data['exam_id']}}"><button class="theme_btn show_modal full_width small_btn">{{'Back'}}</button></a>
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
                            {{$data['exam_desc']['exam_name'] ?? ''}}
                            </label>
                        </div>
                        <div class="col-md-2 col-lg-2">
                            <label class="h6 live_test_lable"><b> Attempted : </b></label>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <label class="h6">
                            {{$data['total_attempted'] ?? ''}}
                            </label>
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
                            <label class="h6 live_test_lable"><b> Correct : </b></label>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <label class="h6">
                            {{$data['total_correct'] ?? ''}}
                            </label>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-3 col-lg-3">
                            <label class="h6 live_test_lable"><b> Test Attempted Start Time: </b></label>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <label class="h6"> {{ date('d-m-Y H:i A', strtotime($data['attempted_on'])) ?? '' }} </label>
                        </div>
                        <div class="col-md-2 col-lg-2">
                            <label class="h6 live_test_lable"><b>Incorrect  : </b></label>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <label class="h6">
                            {{$data['total_incorrect'] ?? ''}}
                            </label>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-2 col-lg-2">
                            <label class="h6 live_test_lable"><b> Rank: </b></label>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <label class="h6"> {{ $data['user_rank']?? 'NA'}}/{{ $data['user_rank_base'] ?? 'NA'}} </label>
                        </div>
                        <div class="col-md-2 col-lg-2">
                            <label class="h6 live_test_lable"><b>Taken Time : </b></label>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <label class="h6">
                                @php
                                    $minut = gmdate('i', ($data['total_time_spent']));
                                @endphp
                                    {{ $minut ?? '0' }} {{' Mins '}}
                            </label>
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
                                <div class="col-md-1 col-lg-1 bold">Ques {{++$que_index}}: </div>
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
                                                <label class="main 
                                                @if($op_value['is_valid']==true)
                                                    active_option
                                                @endif
                                                @foreach($getAttemptedOption as $attemptVal)
                                                    @if($attemptVal['option_id']==$op_value['question_options_id'] && $attemptVal['is_valid']==false)
                                                        wrong_options
                                                    @endif
                                                @endforeach
                                                ">
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
                                                    <input type="checkbox" 
                                                    @foreach($getAttemptedOption as $attemptVal)
                                                        @if($attemptVal['option_id']==$op_value['question_options_id'] && $attemptVal['is_valid']==true)
                                                            checked
                                                        @elseif($attemptVal['option_id']==$op_value['question_options_id'] && $attemptVal['is_valid']==false)
                                                            checked 
                                                        @endif
                                                    @endforeach
                                                    disabled> 
                                                    <span class="geekmark
                                                    @foreach($getAttemptedOption as $attemptVal)
                                                        @if($attemptVal['option_id']==$op_value['question_options_id'] && $attemptVal['is_valid']==false)
                                                            wrong_options
                                                        @endif
                                                    @endforeach
                                                    @if($op_value['is_valid']==true)
                                                            set_green_border
                                                    @endif
                                                    "></span> 
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-lg-1 bold">Sol. </div>
                                <div class="col-md-11 col-lg-11"><div class="border-live-test-sol"> 
                                    {!! $value['solution'] ?? '' !!} 

                                    <div class="show_que_images">
                                        
                                            @if(!empty($value['question_media']))
                                            @foreach($value['question_media'] as $media)
                                                @if($media['media_int_type']=='SOLUTION' && $media['media_int_id']==$value['questions_id'])
                                        
                                                    <img src="{{ url('public/storage/'.Config::get('siteglobal.images_dirs.QUESTIONS')) }}{{'/'.$media['media_file']}}" style="max-height: 120px;width: auto;">
                                                @endif
                                            @endforeach
                                            @endif
                                    </div>
                                    
                                </div></div>
                            </div>
                        @endif
                        @endforeach
                    @endforeach

                    {{-- <div class="text-center modal_btn pt-3">
                        <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/appUsers" href="{{ url('admin/appUsers') }}">{{'Cancel'}}</a>
                    </div> --}}
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
        height: 25px; width: 25px; background-color: white; border: 1px solid black; } 
        .main input:checked ~ .geekmark { background-color: green;} 
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
@endpush