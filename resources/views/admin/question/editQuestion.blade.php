@extends('layout.app_with_login')
@section('title','Edit Question')
@section('script', url('public/js/dashboard/questions.js'))
@section('content') 
<!-- Page Content  -->
<div class="section">
    <div class="container-fluid">
        <h2 class="title"><a  class="ajax_request" data-slug="admin/questions" href="{{url('/admin/questions')}}"><span>{{'Question Bank'}}</span></a>  >  {{'Edit'}}
        </h2>
        <div class="white_box">
            <div class="theme_tab">
                    
                <form name="add-question-form">
                    <input type="hidden" name="pkCat" value="{{ $getQuestions['questions_id'] }}">
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{'Select Question Type'}}</label>
                                <select class="form-control icon_control dropdown_control" name="question_type" id="question_type">
                                    <option value="">{{'Select'}}</option>
                                    @foreach($questionType as $keyQt => $valueQt)
                                        <option value="{{$keyQt}}" @if($getQuestions['question_type']==$keyQt) selected @endif>{{$valueQt}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group hide_content main_category">
                                <label>{{'Select Category'}}</label>
                                <select class="form-control icon_control dropdown_control select2 getCategory" name="category" id="category">
                                    <option value="">{{'Select'}}</option>
                                    @foreach($parent_category as $catVal)
                                        <option value="{{$catVal->category_id}}">{{$catVal->category_desc[0]->name}}</option>
                                    @endforeach
                                </select>
                                
                            </div>

                            <div id="get_sub_category">
                                <div class="form-group category_sub_box @if(empty($getQuestions['category_id']) || $getQuestions['category_id']==0) hide_content @endif">
                                    <label>Category</label>
                                    <select class="form-control icon_control dropdown_control " name="category">
                                        <option value="">Select</option>
                                        @foreach($getAllCateRelatParent as $AllCateRelatParent)
                                        <option value="{{$AllCateRelatParent->category_id}}" @if($getQuestions['category_desc']['category_id']==$AllCateRelatParent->category_id) selected @endif>{{$AllCateRelatParent->desc_en->name ?? ''}}</option>
                                        @endforeach
                                    </select>
                                    <div class="remove_div_category_btn">
                                        <button type="button" class="btn btn-primary btn-sm change_category">{{'Change Category'}}</button>
                                    </div>
                                </div>

                            </div>
                            <div id="gk_ca_category">
                                @if(empty($getQuestions['category_id']) || $getQuestions['category_id']==0)
                                    <input type="hidden" name="category" value="0">
                                @endif
                            </div>

                            <br>

                            @foreach($getQuestions['question_both_lang'] as $ques_val)
                                <div class="form-group">
                                    <label>@if($ques_val['lang_code']=='en') {{'Question In English'}} @else {{'Question In Hindi'}} @endif</label>
                                    <textarea class="form-control icon_control" @if($ques_val['lang_code']=='en') name="question_en" id="question_en" @else name="question_hi" id="question_hi" @endif rows="3"> {{ $ques_val['question_text'] ?? ''}} </textarea>
                                </div>

                                <!--user post text -wrap end-->
                                <ul class="clearfix media_list">
                                    @foreach($getQueMedia as $media)
                                        @if($media['media_int_type']=='QUESTION' && $media['lang_code']==$ques_val['lang_code'])
                                
                                        <li>
                                            {{-- <input type="hidden" name="question_media[]" value="{{$media['media_id']}}"> --}}
                                            <img src="{{ url('public/storage/'.Config::get('siteglobal.images_dirs.QUESTIONS')) }}{{'/'.$media['media_file']}}">
                                            <div  class='post-thumb'>
                                                <div class='inner-post-thumb'>
                                                    <a href='javascript:void(0);' class='remove-pic' 
                                                    onclick="removegalleryimage('{{$media["media_id"]}}')">
                                                        <i class='fa fa-times' aria-hidden='true'></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                        @endif
                                    @endforeach
                                    <li class="myupload">
                                        <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="QUESTION" input-vali-name="que_me_{{$ques_val['lang_code']}}" lang-data="{{$ques_val['lang_code']}}" input-name="question_media[]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                    </li>
                                </ul>
                            @endforeach

                            @foreach($getOptions as $key => $opt_val)
                                <div class="options_box row">
                                    <a class="removeOption btn btn-primary btn-small text-white ml-3">Remove</a>
                                    <div class="col-md-12 col-lg-12">
                                    @foreach($opt_val['question_option_desc'] as $desc_val)
                                        <div class="form-group">
                                            <label>@if($desc_val['lang_code']=='en'){{'Option In English'}}@else {{'Option In Hindi'}} @endif</label>
                                            <textarea class="form-control icon_control" @if($desc_val['lang_code']=='en') name="option_en[]" id="option_en_{{$key}}" @else name="option_hi[]" id="option_hi_{{$key}}" @endif rows="3"> {{ $desc_val['option_text'] }}</textarea>
                                        </div>


                                    <!--user post text -wrap end-->
                                        <ul class="clearfix media_list">
                                            @foreach($getQueMedia as $media)

                                                @if($media['media_int_type']=='OPTION' && $media['lang_code']==$desc_val['lang_code'] && $desc_val['question_options_id']==$media['media_int_id'])
                                        
                                                <li>
                                                    <input type="hidden" name="option_media[{{$key+1}}][]" value="{{$media['media_id']}}">
                                                    <img src="{{ url('public/storage/'.Config::get('siteglobal.images_dirs.QUESTIONS')) }}{{'/'.$media['media_file']}}">
                                                    <div  class='post-thumb'>
                                                        <div class='inner-post-thumb'>
                                                            <a href='javascript:void(0);' data-id="{{$media['media_id']}}" class='remove-pic' 
                                                            onclick="removegalleryimage('{{$media["media_id"]}}')">
                                                                <i class='fa fa-times' aria-hidden='true'></i>
                                                            </a>
                                                    <div></div>
                                                </li>
                                                @endif
                                            @endforeach
                                            <li class="myupload">
                                                <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" input-vali-name="opt_me_{{$desc_val['lang_code']}}[{{$key+1}}][]" lang-data="{{$desc_val['lang_code']}}" input-name="option_media[{{$key+1}}][]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                            </li>
                                        </ul>

                                    @endforeach

                                    <div class="form-check form-check-inline pt-3 mt-3">
                                        <label>Is this a correct option?</label>
                                        <label class="main">
                                            <input type="radio" name="correct_option" class="radio_button_correct" value="{{$key+1}}" @if($opt_val['is_valid']==true) checked @endif>
                                            <p style="font-size: 18px;" class="correct_optionclass">@if($opt_val['is_valid']==true) Correct Option @endif</p> 
                                            <span class="geekmark"></span> 
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label>{{'Option Order'}}</label>
                                        <select class="form-control icon_control dropdown_control option_order" name="option_order[]">
                                            <option value="">{{'Select'}}</option>
                                            <option value="1" @if($opt_val['option_order']==1) selected @endif>1</option>
                                            <option value="2" @if($opt_val['option_order']==2) selected @endif>2</option>
                                            <option value="3" @if($opt_val['option_order']==3) selected @endif>3</option>
                                            <option value="4" @if($opt_val['option_order']==4) selected @endif>4</option>
                                            <option value="5" @if($opt_val['option_order']==5) selected @endif>5</option>
                                        </select>
                                    </div>
                                    
                                </div>
                            </div>
                            @endforeach
                            
                            <div class="addNewOptionList">
                                
                            </div>
                            
                            <div class="text-center modal_btn mb-3" style="display: flow-root;">
                                <button type="button" id="addNewOption" class="theme_btn float-right">{{'Add New Option'}}</button>
                            </div>


                            <br><br>

                            @foreach($getQuestions['question_both_lang'] as $ques_val)
                                <div class="form-group">
                                    <label>@if($ques_val['lang_code']=='en') {{'Solution In English'}} @else {{'Solution In Hindi'}} @endif </label>
                                    <textarea class="form-control icon_control" @if($ques_val['lang_code']=='en') name="solution_en" id="solution_en"@else name="solution_hi" id="solution_hi"@endif rows="3"> {{ $ques_val['solution_text'] }}</textarea>
                                </div>
                                <!--user post text -wrap end-->
                                <ul class="clearfix media_list">
                                    @foreach($getQueMedia as $media)
                                        @if($media['media_int_type']=='SOLUTION' && $media['lang_code']==$ques_val['lang_code'])
                                        <li>
                                            <img src="{{ url('public/storage/'.Config::get('siteglobal.images_dirs.QUESTIONS')) }}{{'/'.$media['media_file']}}">
                                            <div  class='post-thumb'>
                                                <div class='inner-post-thumb'>
                                                    <a href='javascript:void(0);' data-id="{{$media['media_id']}}" class='remove-pic' 
                                                    onclick="removegalleryimage('{{$media["media_id"]}}')">
                                                        <i class='fa fa-times' aria-hidden='true'></i>
                                                    </a>
                                            <div></div>
                                        </li>
                                        @endif
                                    @endforeach
                                    <li class="myupload">
                                        <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="SOLUTION" input-vali-name="sol_me_{{$ques_val['lang_code']}}" lang-data="{{$ques_val['lang_code']}}" input-name="question_media[]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                    </li>
                                </ul>                             
                            @endforeach

                            <div class="form-group">
                                <label>{{'Marks'}}</label>
                                <input type="number" value="{{ $getQuestions['marks'] ?? '' }}" class="form-control icon_control" min="1" name="marks" id="marks">
                            </div>

                            <div class="text-center modal_btn pt-3">
                                <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/questions" href="{{ url('admin/questions') }}">{{'Cancel'}}</a>
                                <button type="submit" id="prev-step-1" class="theme_btn">{{'Update'}}</button>
                            </div>

                        </div>
                        <div class="col-lg-3"></div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div> 
<input type="hidden" id="checkPageValue" value="edit_page">
<input type="hidden" id="countQuestionOption" value="{{ count($getOptions) }}">
<input type="hidden" id="updateOptionVal">

@endsection
@push('custom-styles')
    <style type="text/css">
        .main { display: flex; position: relative; padding-left: 45px; margin-bottom: 15px; cursor: pointer; font-size: 20px; } 
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
{{-- <script src="https://cdn.ckeditor.com/4.15.0/basic/ckeditor.js"></script> --}}
<script type="text/javascript">
    $(document).on('click',"input[name='correct_option']", function(){
        $('.correct_optionclass').empty();
        $(this).parent().find('.correct_optionclass').html('Correct Option');
    });

    CKEDITOR.replace('question_en', {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('question_hi', {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
    $(document).on('click',"#addNewOption", function() {
        var updateOptionVal = $('#updateOptionVal').val();
        CKEDITOR.replace('option_en'+updateOptionVal, {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
        CKEDITOR.replace('option_hi'+updateOptionVal, {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
    });
    var count_q_o = $("#countQuestionOption").val();
    for (var i =0 ; i<count_q_o; i++) {
        CKEDITOR.replace('option_en_'+i, {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
          CKEDITOR.replace('option_hi_'+i, {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
    }
    CKEDITOR.replace('solution_hi', {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('solution_en', {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
        
</script>
<script type="text/javascript" src="{{ url('public/js/dashboard/questions_edit.js') }}"></script>
@endpush