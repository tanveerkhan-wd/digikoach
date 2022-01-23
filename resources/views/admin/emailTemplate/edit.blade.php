@extends('layout.app_with_login')
@section('title','Edit Email Template')
@section('script', url('public/js/dashboard/email_template.js'))
@section('content') 
<!-- Page Content  -->
<div class="section">
    <div class="container-fluid">
        <div class="content">
            <div class="section">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 mb-3">
                           <h2 class="title"><span>{{'Master'}}</span> > <a class="ajax_request" data-slug="admin/emailTemplates" href="{{url('/admin/emailTemplates')}}"><span>{{'Email Template'}}</span></a> > {{' Edit '}}</h2>
                        </div> 
                        <div class="col-12">
                            <div class="white_box pt-5 pb-5">
                                <div class="container-fliid">
                                    <div class="row">
                                        <div class="col-12">
                                            <form name="edit-form">
                                                <input type="hidden" name="cid" value="{{$data->email_master_id}}">
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-3"></div>
                                                        <div class="col-md-6">
                                                             <div class="form-group ">
                                                                <label>Title
                                                                <span class="asterisk red">*</span>
                                                                </label>
                                                                <input required="" class="form-control" type="text" disabled="" name="title" value="{{ $data->title }}"> 
                                                                <div id="title_validate"></div>
                                                            </div>
                                                            <div class="form-group ">
                                                                <label>Subject
                                                                <span class="asterisk red">*</span>
                                                                </label>
                                                                <input required="" class="form-control" type="text" name="subject" value="{{ $data->subject }}"> 
                                                                <div id="title_validate"></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Content</label>
                                                                <textarea required="" row="5" class="form-control" name="content" id="message">{{ $data->content }}</textarea>
                                                            </div>
                                                            @if($data->parameters)    
                                                             <p class="note">Note: Please do not remove <b>{{$data->parameters}}</b></p>
                                                             @endif
                                                            <div class="col-md-3"></div>
                                                        </div>
                                                    </div>

                                                    <div class="text-center">
                                                        <button class="theme_btn">Submit</button>
                                                        <a class="theme_btn red_btn ajax_request" data-slug="admin/emailTemplates" href="{{url('admin/emailTemplates')}}">Cancel</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
<!-- Include this Page JS -->
<script src="{{ url('public/bower_components/ckeditor/ckeditor.js') }}"></script>
<!-- Include this Page JS -->
<script type="text/javascript" src="{{ url('public/js/dashboard/email_template.js') }}"></script>

@endpush