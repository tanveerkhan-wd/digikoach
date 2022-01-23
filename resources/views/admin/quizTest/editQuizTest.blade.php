@extends('layout.app_with_login')
@section('title','Edit Quiz Test')
@section('script', url('public/js/dashboard/quiz_test.js'))
@section('content') 
<!-- Page Content  -->
<div class="section">
    <div class="container-fluid">
        <h2 class="title"><a  class="ajax_request" data-slug="admin/quizTest" href="{{url('/admin/quizTest')}}"><span>{{'Quiz Test '}}</span></a>  >  {{'Edit'}}
        </h2>
        <div class="white_box">
            <div class="theme_tab">
                    
                <form name="add-quiz-test-form">
                    <div class="row">
                        <input type="hidden" name="pkCat" value="{{ $getExam['exam_id'] }}">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">
                            @foreach($getExam['desc_both_lang'] as $exmValue)
                            <div class="form-group">
                                <label>@if($exmValue['lang_code']=='en') {{'Quiz Test Name In English'}} @else {{'Quiz Test Name In Hindi'}} @endif </label>
                                <input type="text" @if($exmValue['lang_code']=='en') name="name_en" id="name_en" @else name="name_hi" id="name_hi" @endif class="form-control icon_control" value="{{ $exmValue['exam_name'] ?? ''}}">
                            </div>
                            @endforeach

                            <div class="form-group">
                                <label>{{'Test Duration (In Minutes)'}}</label>
                                <input type="number" name="duration" id="duration" class="form-control icon_control" value="{{ $getExam['exam_duration'] ?? '' }}">
                            </div>
                        

                           <div class="form-group change_category hide_content">
                                <label>{{'Select Category'}}</label>
                                <select class="form-control icon_control dropdown_control select2 getCategory" name="main_category" id="category">
                                    <option value="">{{'Select'}}</option>
                                    @foreach($parent_category as $catVal)
                                        <option value="{{$catVal->category_id}}" @if($getExam['category_id']==$catVal->category_id) selected @endif>{{$catVal->category_desc[0]->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group change_subcategory">
                                <label>{{'Category'}}</label>
                                <select class="form-control icon_control dropdown_control getCategory" name="main_category" id="category">
                                    <option value="{{$getSubPare['category_id']}}"selected>{{$getSubPare['desc']['name']}}</option>
                                </select>
                            </div>
                                
                            <div class="remove_div_category_btn mb-4">
                                <button type="button" class="btn btn-primary btn-sm change_category">{{'Change Category'}}</button>
                            </div>

                            
                            <div id="get_sub_category">
                                <div class="row">
                                    <div class="col-md-3 col-lg-3">
                                        <label><b>Sub Categories</b></label>
                                    </div>
                                    <div class="col-md-3 col-lg-3">
                                        <label><b>No. of Que. in QB</b></label>
                                    </div>
                                    <div class="col-md-3 col-lg-3">
                                        <label><b>No. of Unused Que. in QB</b></label>
                                    </div>
                                    <div class="col-md-3 col-lg-3">
                                        <label><b>How many Que. want to add in test</b></label>
                                    </div>
                                </div>
                            </div>

                            <div id="get_sub_category_question">
                                @foreach($no_of as $no_key => $no_value)
                                <div class="row">
                                    <div class="col-md-3 col-lg-3">
                                        <label> {{$no_value['cate_name']['desc']['name'] ?? '' }} </label>
                                    </div>
                                    <div class="col-md-3 col-lg-3">
                                        <label> {{$no_value['total_quetion'] ?? '0'}} Questions </label>
                                    </div>
                                    <div class="col-md-3 col-lg-3">
                                        <label> {{ $no_value['unused_que'] ?? ''}}  Questions </label>
                                        <input type="hidden" id="remaining_ques" value="{{ $no_value['unused_que'] ?? ''}}">
                                    </div>
                                    <div class="col-md-3 col-lg-3">
                                        <div class="row">
                                            <div class="col-md-8 col-lg-8 p-1">
                                                <input type="number" name="question_number[]" id="question_number" class="form-control" value="{{$no_value['used_que'] ?? ''}}" min="0">
                                            </div>
                                            <div class="col-md-4 col-lg-4 p-1">
                                                <label>Ques</label>
                                            </div>
                                        </div>
                                        <input type="hidden" name="sub_category_id[]" value="{{$no_value['cate_name']['category_id'] ?? ''}}">
                                    </div>
                                </div>           
                                @endforeach                
                            </div>


                            <div class="text-center modal_btn pt-3">
                                <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/quizTest" href="{{ url('admin/quizTest') }}">{{'Cancel'}}</a>
                                <button type="submit" class="theme_btn">{{'Update Test'}}</button>
                            </div>

                        </div>
                        <div class="col-lg-3"></div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div> 

@endsection

@push('custom-styles')

@endpush
@push('custom-scripts')
<script type="text/javascript">
    $(function() {
      showLoader(false);
    });
</script>

<script type="text/javascript" src="{{ url('public/js/dashboard/quiz_test.js') }}"></script>
@endpush