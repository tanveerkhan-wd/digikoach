@extends('layout.app_with_login')
@section('title','Gk & Ca Quizzes')
@section('script', url('public/js/dashboard/gk_ca_quiz_test.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
    <div class="container-fluid">
        <h2 class="title"><span>{{'Gk & Ca'}}</span> > <a  class="ajax_request" data-slug="admin/gkCa/quizTest" href="{{url('/admin/gkCa/quizTest')}}"><span>{{'Quizzes '}}</span></a>  >  {{'Edit'}}
        </h2>
        <div class="white_box">
            <div class="theme_tab">
                    
                <form name="add-quiz-test-form">
                    <input type="hidden" name="pkCat" value="{{$getExam['exam_id']}}">
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">
                            @foreach($getExam['desc_both_lang'] as $value)
                            <div class="form-group">
                                <label> @if($value['lang_code']=='en'){{'Quiz Test Name In English'}} @else {{'Quiz Test Name In Hindi'}} @endif</label>
                                <input type="text" @if($value['lang_code']=='en') name="name_en" id="name_en" @else name="name_hi" id="name_hi" @endif value="{{$value['exam_name'] ?? ''}}" class="form-control icon_control">
                            </div>
                            @endforeach
                            <div class="form-group">
                                <label>{{'Test Duration (In Minutes)'}}</label>
                                <input type="number" name="duration" id="duration" value="{{$getExam['exam_duration'] ?? ''}}" class="form-control icon_control" placeholder="">
                            </div>

                            <div id="get_sub_category">
                                <div class="row">
                                    <div class="col-md-3 col-lg-3">
                                        <label><b>Category</b></label>
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
                                <div class="row">
                                    <div class="col-md-3 col-lg-3">
                                        <label> {{$no_of['cate_name'] ?? '' }} </label>
                                    </div>
                                    <div class="col-md-3 col-lg-3">
                                        <label> {{$no_of['total_quetion'] ?? '0'}} Questions </label>
                                    </div>
                                    <div class="col-md-3 col-lg-3">
                                        <label> {{ $no_of['unused_que'] ?? ''}}  Questions </label>
                                        <input type="hidden" id="remaining_ques" value="{{ $no_of['unused_que'] ?? ''}}">
                                    </div>
                                    <div class="col-md-3 col-lg-3">
                                        <div class="row">
                                            <div class="col-md-8 col-lg-8 p-1">
                                                <input type="number" name="question_number" value="{{ $no_of['used_que'] ?? '' }}" id="question_number" class="form-control" min="0">
                                            </div>
                                            <div class="col-md-4 col-lg-4 p-1">
                                                <label>Ques</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>                     
                            </div>

                            <div class="text-center modal_btn pt-3">
                                <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/gkCa/quizTest" href="{{ url('admin/gkCa/quizTest') }}">{{'Cancel'}}</a>
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

<script type="text/javascript" src="{{ url('public/js/dashboard/gk_ca_quiz_test.js') }}"></script>
@endpush