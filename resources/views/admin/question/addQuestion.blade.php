@extends('layout.app_with_login')
@section('title','Add New Question')
@section('script', url('public/js/dashboard/questions.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
    <div class="container-fluid">
        <h2 class="title"><a data-slug="admin/questions" href="{{url('/admin/questions')}}"><span>{{'Question Bank'}}</span></a>  >  {{'Add New'}}
        </h2>
        <div class="white_box">
            <div class="theme_tab">
                <form name="add-question-form">
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">
                            <div id="step-1">
                                <div class="form-group">
                                    <label>{{'Select Question Type'}}</label>
                                    <select class="form-control icon_control dropdown_control" name="question_type" id="question_type">
                                        <option value="">{{'Select'}}</option>
                                        @foreach($questionType as $keyQt => $valueQt)
                                            <option value="{{$keyQt}}">{{$valueQt}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>{{'Select Category'}}</label>
                                    <select class="form-control icon_control dropdown_control select2 getCategory" name="category" id="category">
                                        <option value="">{{'Select'}}</option>
                                        @foreach($parent_category as $catVal)
                                            <option value="{{$catVal->category_id}}">{{$catVal->category_desc[0]->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="get_sub_category">
                                    
                                </div>
                                <div id="gk_ca_category">
                                </div>
                                <div id="check_button_clicked">
                                    <input type="hidden" id="upload_manual_type" value="upload_manual_type">
                                </div>
                                <div class="text-center modal_btn pt-3">
                                    <button type="button" class="add_manually_btn theme_btn active_button_que">{{'Add Manually'}}</button>
                                    <button type="button" class="theme_btn" id="upload_csv">{{'Upload CSV'}}</button>
                                    <button type="button" class="theme_btn" id="bulk_upload">{{'Bulk Upload'}}</button>
                                </div>

                                <div class="text-center modal_btn pt-3">
                                    <a class="theme_btn red_btn no_sidebar_active" data-slug="admin/questions" href="{{ url('admin/questions') }}">{{'Cancel'}}</a>
                                    <button type="button" id="next-step-1" class="theme_btn">{{'Next'}}</button>
                                </div>
                            </div>

                            <div id="upload_csv_box" class="hide_content">
                                <div class=" text-center modal_btn pt-3">
                                 
                                    <div class="form-group hide_content add_csv_file_name_cl">
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">CSV File : </div>
                                            </div>
                                            <input type="text" class="form-control" id="add_csv_file_name" placeholder="CSV File">
                                        </div>
                                    </div>
                                    <div  class="upload_pic_link">
                                        <a href="javascript:void(0)" class="theme_btn text-white chnageClassHover">
                                        {{'Upload CSV File'}}<input type="file" id="upload_csv_file" name="csv_file"></a>
                                    </div>
                                    <div  class="upload_pic_link">
                                        <a href="{{ url('/public/sample_csv/sample_csv_file.csv') }}" title="sample_csv_file" download>{{'Download Sample CSV'}}</a>
                                    </div>
                                </div>

                                <div class="text-center modal_btn pt-3">
                                    <a class="theme_btn red_btn back_to_step1" href="javascript::void(0)">{{'back'}}</a>
                                    <button type="submit" name="upload_csv" value="yes" class="theme_btn">{{'Save'}}</button>
                                </div>

                            </div>
                            <!-- __ADD QUESTION MANUALLY__ -->
                            <div id="step-2" class="hide_content">
                                
                                <div class="form-group">
                                    <label>{{'Question In English'}}</label>
                                    <textarea class="form-control icon_control" name="question_en" id="question_en" ></textarea>
                                </div>
                                <ul class="clearfix media_list">
                                    <li class="myupload">
                                        <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="QUESTION" input-vali-name="que_me_en" lang-data="en" input-name="question_media[]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                    </li>
                                </ul>

                                <div class="form-group">
                                    <label>{{'Question In Hindi'}}</label>
                                    <textarea class="form-control icon_control" name="question_hi" id="question_hi" rows="3"></textarea>
                                </div>
                                <ul class="clearfix media_list">
                                    <li class="myupload">
                                        <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="QUESTION" input-vali-name="que_me_hi" lang-data="hi" input-name="question_media[]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                    </li>
                                </ul>
                                <div class="options_box row">
                                    <div class="col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label>{{'Option In English'}}</label>
                                        <textarea class="form-control icon_control" name="option_en[]" id="option_en" rows="3"></textarea>
                                    </div>
                                    <ul class="clearfix media_list">
                                        <li class="myupload">
                                            <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" input-vali-name="opt_me_en[1][]" lang-data="en" input-name="option_media[1][]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                        </li>
                                    </ul>
                                    <div class="form-group">
                                        <label>{{'Option In Hindi'}}</label>
                                        <textarea class="form-control icon_control" name="option_hi[]" id="option_hi" rows="3"></textarea>
                                    </div>
                                    <ul class="clearfix media_list">
                                        <li class="myupload">
                                            <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" lang-data="hi" input-vali-name="opt_me_hi[1][]" input-name="option_media[1][]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                        </li>
                                    </ul>
                                    <div class="form-group pt-3 mt-3">
                                        <label>Is this a correct option?</label>
                                        <label class="main">
                                            <input type="radio" name="correct_option" class="radio_button_correct" value="1">
                                            <p style="font-size: 18px;" class="correct_optionclass"></p>
                                            <span class="geekmark"></span> 
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label>{{'Option Order'}}</label>
                                        <select class="form-control icon_control dropdown_control option_order" name="option_order[]">
                                            <option value="">{{'Select'}}</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    </div>
                                </div>

                                <div class="addNewOptionList">
                                    
                                </div>
                                
                                <div class="text-center modal_btn mb-3" style="display: flow-root;">
                                    <button type="button" id="addNewOption" class="theme_btn float-right">{{'Add New Option'}}</button>
                                </div>


                                <div class="form-group">
                                    <label>{{'Solution In English'}}</label>
                                    <textarea class="form-control icon_control" name="solution_en" id="solution_en" rows="3"></textarea>
                                </div>
                                <ul class="clearfix media_list">
                                    <li class="myupload">
                                        <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="SOLUTION" input-vali-name="sol_me_en" lang-data="en" input-name="question_media[]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                    </li>
                                </ul>
                                <div class="form-group">
                                    <label>{{'Solution In Hindi'}}</label>
                                    <textarea class="form-control icon_control" name="solution_hi" id="solution_hi" rows="3"></textarea>
                                </div>
                                <ul class="clearfix media_list">
                                    <li class="myupload">
                                        <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="SOLUTION" input-vali-name="sol_me_hi" lang-data="hi" input-name="question_media[]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                    </li>
                                </ul>
                                <div class="form-group">
                                    <label>{{'Marks'}}</label>
                                    <input type="number" class="form-control icon_control" min="1" name="marks" id="marks">
                                </div>

                                <div class="text-center modal_btn pt-3">
                                    <button type="button" id="prev-step-1" class="theme_btn">{{'Back'}}</button>
                                    <button type="submit" id="save-add-new" value="yes" name="save_and_add_new" class="theme_btn">{{'Save & Add New Question'}}</button>
                                </div>

                                <div class="text-center modal_btn pt-3">
                                    <a class="theme_btn red_btn no_sidebar_active" data-slug="admin/questions" href="{{ url('admin/questions') }}">{{'Cancel'}}</a>
                                    <button type="submit" id="prev-step-1" class="theme_btn">{{'Save'}}</button>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-3"></div>
                    </div>
                    <div id="step-3" class="hide_content">
                        <section class="container">
                            <input type="hidden" id="bulk_counter" value="1">
                            <div id="added_question_id_box">
                                      
                            </div>
                            <div class="pane-hScroll">
                                <table class="scroll_body">
                                    <thead>
                                        <tr>
                                            <th>Que. En</th>
                                            <th>Media Id</th>
                                            <th>Que. Hi</th>
                                            <th>Media Id</th>
                                            <th>Sol. En</th>
                                            <th>Media Id</th>
                                            <th>Sol. hi</th>
                                            <th>Media Id</th>
                                            <th>Marks</th>

                                            <th>Option 1 En</th>
                                            <th>Media Id</th>
                                            <th>Option 1 Hi</th>
                                            <th>Media Id</th>
                                            <th>Option 1 Order</th>

                                            <th>Option 2 En</th>
                                            <th>Media Id</th>
                                            <th>Option 2 Hi</th>
                                            <th>Media Id</th>
                                            <th>Option 2 Order</th>

                                            <th>Option 3 En</th>
                                            <th>Media Id</th>
                                            <th>Option 3 Hi</th>
                                            <th>Media Id</th>
                                            <th>Option 3 Order</th>

                                            <th>Option 4 En</th>
                                            <th>Media Id</th>
                                            <th>Option 4 Hi</th>
                                            <th>Media Id</th>
                                            <th>Option 4 Order</th>

                                            <th>Option 5 En</th>
                                            <th>Media Id</th>
                                            <th>Option 5 Hi</th>
                                            <th>Media Id</th>
                                            <th>Option 5 Order</th>

                                            <th>Right Option</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="TextBoxContainer">
                                        <tr>
                                            <td>
                                                <input name="bulk_question_en[]" type="text" class="form-control">
                                            </td>
                                            <td>
                                                <ul class="clearfix media_list">
                                                    <li class="myupload">
                                                        <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="QUESTION" input-vali-name="que_me_en[0]" lang-data="en" input-name="question_media[0][]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                <input name="bulk_question_hi[]" type="text" class="form-control">
                                            </td>
                                            <td>
                                                <ul class="clearfix media_list">
                                                    <li class="myupload">
                                                        <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="QUESTION" input-vali-name="que_me_hi[0]" lang-data="hi" input-name="question_media[0][]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                                    </li>
                                                </ul>
                                                
                                            </td>  
                                            <td>
                                                <input name="bulk_solution_en[]" type="text" class="form-control">
                                            </td>
                                            <td>
                                                <ul class="clearfix media_list">
                                                    <li class="myupload">
                                                        <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="SOLUTION" input-vali-name="sol_me_en[0]" lang-data="en" input-name="question_media[0][]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                                    </li>
                                                </ul>
                                                
                                            </td>
                                            <td>
                                                <input name="bulk_solution_hi[]" type="text" class="form-control">
                                            </td>
                                            <td>
                                                <ul class="clearfix media_list">
                                                    <li class="myupload">
                                                        <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="SOLUTION" input-vali-name="sol_me_hi[0]" lang-data="hi" input-name="question_media[0][]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                                    </li>
                                                </ul>
                                                
                                            </td>
                                            <td>
                                                <input name="bulk_marks[]" type="number" class="form-control">
                                            </td>    
                                            <td>
                                                <input name="bulk_opt_en[1][]" type="text" class="form-control">
                                            </td>
                                            <td>
                                                <ul class="clearfix media_list">
                                                    <li class="myupload">
                                                        <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" input-vali-name="opt_me_en[0][1]" lang-data="en" input-name="option_media[0][1][]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                <input name="bulk_opt_hi[1][]" type="text" class="form-control">
                                            </td>
                                            <td>
                                                <ul class="clearfix media_list">
                                                    <li class="myupload">
                                                        <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" input-vali-name="opt_me_hi[0][1]" lang-data="hi" input-name="option_media[0][1][]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                                    </li>
                                                </ul>
                                                
                                            </td>
                                            <td>
                                                <input name="bulk_opt_order[1][]" type="number" class="form-control">
                                            </td>  
                                            <td>
                                                <input name="bulk_opt_en[2][]" type="text" class="form-control">
                                            </td>
                                            <td>
                                                
                                                
                                                <ul class="clearfix media_list">
                                                    <li class="myupload">
                                                        <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" input-vali-name="opt_me_en[0][2]" lang-data="en" input-name="option_media[0][2][]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                <input name="bulk_opt_hi[2][]" type="text" class="form-control">
                                            </td>
                                            <td>
                                                <ul class="clearfix media_list">
                                                    <li class="myupload">
                                                        <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" input-vali-name="opt_me_hi[0][2]" lang-data="hi" input-name="option_media[0][2][]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                                    </li>
                                                </ul>
                                                
                                            </td>
                                            <td>
                                                <input name="bulk_opt_order[2][]" type="number" class="form-control">
                                            </td>
                                            <td>
                                                <input name="bulk_opt_en[3][]" type="text" class="form-control">
                                            </td>
                                            <td>
                                                
                                                <ul class="clearfix media_list">
                                                    <li class="myupload">
                                                        <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" input-vali-name="opt_me_en[0][3]" lang-data="en" input-name="option_media[0][3][]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                                    </li>
                                                </ul>
                                                
                                            </td>
                                            <td>
                                                <input name="bulk_opt_hi[3][]" type="text" class="form-control">
                                            </td>
                                            <td>

                                                
                                                <ul class="clearfix media_list">
                                                    <li class="myupload">
                                                        <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" input-vali-name="opt_me_hi[0][3]" lang-data="hi" input-name="option_media[0][3][]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                                    </li>
                                                </ul>
                                                
                                            </td>
                                            <td>
                                                <input name="bulk_opt_order[3][]" type="number" class="form-control">
                                            </td>    
                                            <td>
                                                <input name="bulk_opt_en[4][]" type="text" class="form-control">
                                            </td>
                                            <td>
                                                <ul class="clearfix media_list">
                                                    <li class="myupload">
                                                        <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" input-vali-name="opt_me_en[0][4]" lang-data="en" input-name="option_media[0][4][]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                                    </li>
                                                </ul>
                                                
                                            </td>
                                            <td>
                                                <input name="bulk_opt_hi[4][]" type="text" class="form-control">
                                            </td>
                                            <td>
                                                <ul class="clearfix media_list">
                                                    <li class="myupload">
                                                        <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" input-vali-name="opt_me_hi[0][4]" lang-data="hi" input-name="option_media[0][4][]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                                    </li>
                                                </ul>
                                                
                                            </td>
                                            <td>
                                                <input name="bulk_opt_order[4][]" type="number" class="form-control">
                                            </td>    
                                            <td>
                                                <input name="bulk_opt_en[5][]" type="text" class="form-control">
                                            </td>
                                            <td>
                                                
                                                <ul class="clearfix media_list">
                                                    <li class="myupload">
                                                        <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" input-vali-name="opt_me_en[0][5]" lang-data="en" input-name="option_media[0][5][]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                                    </li>
                                                </ul>
                                                
                                            </td>
                                            <td>
                                                <input name="bulk_opt_hi[5][]" type="text" class="form-control">
                                            </td>
                                            <td>
                                                
                                                <ul class="clearfix media_list">
                                                    <li class="myupload">
                                                        <span><i class="fa fa-plus" aria-hidden="true"></i><input type="file" type-data="OPTION" input-vali-name="opt_me_hi[0][5]" lang-data="hi" input-name="option_media[0][5][]" click-type="type2" class="picuploadForAll picupload" multiple></span>
                                                    </li>
                                                </ul>
                                                
                                            </td>
                                            <td>
                                                <input name="bulk_opt_order[5][]" number="text" class="form-control">
                                            </td>    
                                            <td>
                                                <input name="bulk_right_opt[]" type="number" class="form-control">
                                            </td>   
                                            <td>
                                                {{-- <button type="button" class="btn btn-danger remove"><i class="fa fa-times"></i></button> --}}
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="36" class="text-right">
                                                <button id="btnAdd" type="submit" name="bulk_upload_add" value="yes" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp; Add&nbsp;</button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="text-center modal_btn pt-3">
                                <a class="theme_btn red_btn back_to_step1" href="javascript::void(0)">{{'back'}}</a>
                                <button type="submit" name="bulk_upload" value="yes" class="theme_btn">{{'Save'}}</button>
                            </div>
                        </section>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div> 
<input type="hidden" id="checkPageValue" value="add_page">
<input type="hidden" id="updateOptionVal">
@endsection
@push('custom-styles')
    <style type="text/css">
        .main { display: flex; position: relative; padding-left: 20px; margin-bottom: 15px; cursor: pointer; font-size: 20px; } 
        input[type=radio] {visibility: hidden;} 
        .geekmark { position: absolute; top: 0; left: 0; 
        height: 25px; width: 25px; background-color: white; border: 1px solid green; } 
        .main input:checked ~ .geekmark { background-color: green; } 
        .geekmark:after { content: ""; position: absolute; display: none; }
        .main input:checked ~ .geekmark:after { display: block; } 
        .main .geekmark:after { left: 8px; bottom: 5px; width: 6px; height: 12px; border: solid white; border-width: 0 4px 4px 0; -webkit-transform: rotate(45deg); -ms-transform: rotate(45deg); transform: rotate(45deg); }


        table.scroll_body{
          border-collapse: collapse;
          background: white;
          table-layout: fixed;
          width: 100%;
        }
        table.scroll_body th, td {
          padding: 8px 16px;
          border: 1px solid #ddd;
          width:170px ;
          overflow: hidden;
          text-overflow: ellipsis;
          white-space: nowrap;
        }

        .pane-hScroll {
          overflow: auto;
        }
        .pane-vScroll {
          overflow-y: auto;
          overflow-x: hidden;
          height: 240px;
        }
    </style>
@endpush
@push('custom-scripts')
<script type="text/javascript">
    $(function() {
      showLoader(false);
    });

    $(document).on('click',"input[name='correct_option']", function(){
        $('.correct_optionclass').empty();
        $(this).parent().find('.correct_optionclass').html('Correct Option');
    });
    
</script>
<script src="{{ url('public/bower_components/ckeditor/ckeditor.js') }}"></script>
{{-- <script src="https://cdn.ckeditor.com/4.15.0/basic/ckeditor.js"></script> --}}
  {{-- <script src="https://cdn.ckeditor.com/4.15.1/standard-all/ckeditor.js"></script> --}}

<script type="text/javascript">
    CKEDITOR.replace('question_en', {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('question_hi', {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('option_en', {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('option_hi', {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('solution_hi', {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('solution_en', {
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
</script>
<script type="text/javascript" src="{{ url('public/js/dashboard/questions.js') }}"></script>
@endpush