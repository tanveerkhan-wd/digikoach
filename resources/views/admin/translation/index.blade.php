@extends('layout.app_with_login')
@section('title','Translation')
@section('script', url('public/js/dashboard/translation.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
	<div class="container-fluid">
		<div class="row ">
            <div class="col-12 mb-3">
    			<h2 class="title"><span>{{'Master'}}</span> > {{'Translation'}}</h2>
            </div>
            <div class="col-md-4 mb-3">
                <input type="text" id="search_translation" class="form-control without_border icon_control search_control" placeholder="{{'Search'}}">
            </div>  
            <div class="col-md-4 text-md-right mb-3">
                
            </div> 
            <div class="col-md-2 col-6 mb-3">
               
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a><button class="theme_btn show_modal full_width small_btn" data-toggle="modal" data-target="#add_new">{{'Add New'}}</button></a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="theme_table">
                    <div class="table-responsive">
                        <table id="translation_listing" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>{{'Sr. No.'}}</th>
                                    <th>{{'Section'}}</th>
                                    <th>{{'Translation Key'}}</th>
                                    <th>{{'English'}}</th>
                                    <th>{{'Hindi'}}</th>
                                    <th><div class="action">{{'Actions'}}</div></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
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
                <form name="add-translation-form">
                    <div class="row">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-10">
                            <h5 class="modal-title" id="exampleModalCenterTitle">{{'Translation'}}</h5>
                            <input type="hidden" name="pkCat" id="pkCat">

                            <div class="form-group">
                                <label>{{'Section'}}*</label>
                                <input type="text" name="group" id="group" class="form-control icon_control">
                            </div>
                            <div class="form-group">
                                <label>{{'Translation Key'}}*</label>
                                <input type="text" name="key" id="key" class="form-control icon_control">
                            </div>

                            <div class="form-group">
                                <label>{{'English'}}*</label>
                                <input type="text" name="name_en" id="name_en" class="form-control icon_control">
                            </div>
                            <div class="form-group">
                                <label>{{'Hindi'}}*</label>
                                <input type="text" name="name_hi" id="name_hi" class="form-control icon_control">
                            </div>
                            <div class="text-center modal_btn ">
                                <button type="submit" class="theme_btn">{{$translations['gn_submit'] ?? 'Submit'}}</button>
                            </div>
                        </div>
                        <div class="col-lg-1"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="theme_modal modal fade" id="delete_prompt" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <img src="{{url('public/images/ic_close_bg.png')}}" class="modal_top_bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="{{url('public/images/ic_close_circle_white.png')}}">
                </button>
                    <div class="row">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-10">
                            <h5 class="modal-title" id="exampleModalCenterTitle">{{'Delete'}}</h5>
                            <div class="form-group text-center">
                                <label>{{$translations['gn_delete_prompt'] ?? 'Are you sure you want to delete ?'}}</label>
                                <input type="hidden" id="did">
                            </div>
                            <div class="text-center modal_btn ">
                                <button style="display: none;" class="theme_btn show_delete_modal full_width small_btn" data-toggle="modal" data-target="#delete_prompt">{{$translations['gn_delete'] ?? 'Delete'}}</button>
                                <button type="button" onclick="confirmDelete()" class="theme_btn">{{$translations['gn_yes'] ?? 'Yes'}}</button>
                                <button type="button" data-dismiss="modal" class="theme_btn red_btn">{{$translations['gn_no'] ?? 'No'}}</button>
                            </div>
                        </div>
                        <div class="col-lg-1"></div>
                    </div>
            </div>
        </div>
    </div>
</div>

<!-- view  Popup -->
{{-- <div class="theme_modal modal fade" id="show_view_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <img src="{{url('public/images/ic_close_bg.png')}}" class="modal_top_bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="{{url('public/images/ic_close_circle_white.png')}}">
                </button>
                    <div class="row">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-10">
                            <h5 class="modal-title" id="exampleModalCenterTitle">{{'CMS'}}</h5>
                            <input type="hidden" name="pkCat" id="pkCat">
                            <div class="form-group">
                                <label>{{'Title In English'}}</label>
                                <input type="text" id="view_name_en" class="form-control icon_control" disabled>
                            </div>
                            <div class="form-group">
                                <label>{{'Title In Hindi'}}</label>
                                <input type="text" id="view_name_hi" class="form-control icon_control" disabled>
                            </div>
                            
                            <div class="form-group">
                                <label>{{'Description In English'}}</label>
                                <textarea class="form-control icon_control" id="view_desc_en" rows="3" disabled></textarea>
                            </div>
                            <div class="form-group">
                                <label>{{'Description In Hindi'}}</label>
                                <textarea class="form-control icon_control" id="view_desc_hi" rows="3" disabled></textarea>
                            </div>
                            <div class="form-group">
                                <label>{{'SEO Meta Title'}}</label>
                                <input type="text" id="view_seo_meta_title" class="form-control icon_control" disabled>
                            </div>
                            <div class="form-group">
                                <label>{{'SEO Meta Description'}}</label>
                                <textarea class="form-control icon_control" id="view_seo_meta_description" rows="3" disabled></textarea>
                            </div>
                            <div class="form-group">
                                <label>{{'Status'}}</label>
                                <input type="text" id="view_status" class="form-control icon_control" disabled>
                            </div>

                        </div>
                        <div class="col-lg-1"></div>
                    </div>
            </div>
        </div>
    </div>
</div>
 --}}
@endsection

@push('custom-scripts')
<script type="text/javascript">
    $(function() {
      showLoader(false);
    });
</script>
<!-- Include this Page JS -->
<script src="{{ url('public/bower_components/ckeditor/ckeditor.js') }}"></script>

<script type="text/javascript" src="{{ url('public/js/dashboard/translation.js') }}"></script>
@endpush