@extends('layout.app_with_login')
@section('title','Add New Practice Test')
@section('script', url('public/js/dashboard/practice_test.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
    <div class="container-fluid">
        <h2 class="title"><a  class="ajax_request" data-slug="admin/practiceTest" href="{{url('/admin/practiceTest')}}"><span>{{'Practice Test '}}</span></a>  >  {{'Add New'}}
        </h2>
        <div class="white_box">
            <div class="theme_tab">
                    
                <form name="add-practice-test-form">
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">

                            <div class="form-group">
                                <label>{{'Practice Test Name In English'}}</label>
                                <input type="text" name="name_en" id="name_en" class="form-control icon_control">
                            </div>

                            <div class="form-group">
                                <label>{{'Practice Test Name In Hindi'}}</label>
                                <input type="text" name="name_hi" id="name_hi" class="form-control icon_control">
                            </div>

                            <div class="form-group">
                                <label>{{'Select Category'}}</label>
                                <select class="form-control icon_control dropdown_control select2 getCategory" name="main_category" id="category">
                                    <option value="">{{'Select'}}</option>
                                    @foreach($parent_category as $catVal)
                                        <option value="{{$catVal->category_id}}">{{$catVal->category_desc[0]->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="get_sub_category">
                                
                            </div>

                            <div id="get_sub_category_question">
                                
                            </div>

                            <div class="text-center modal_btn pt-3">
                                <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/practiceTest" href="{{ url('admin/practiceTest') }}">{{'Cancel'}}</a>
                                <button type="submit" class="theme_btn">{{'Create Test'}}</button>
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

<script type="text/javascript" src="{{ url('public/js/dashboard/practice_test.js') }}"></script>
@endpush