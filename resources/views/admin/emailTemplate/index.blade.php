@extends('layout.app_with_login')
@section('title', 'Email Template')
@section('script', url('public/js/dashboard/email_template.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
	<div class="container-fluid">
		<div class="row ">
            <div class="col-12 mb-3">
    			<h2 class="title"><span>{{'Master'}}</span> >  {{'Email Template'}}</h2>
            </div>
            <div class="col-md-4 mb-3">
                <input type="text" id="search_email_template" class="form-control without_border icon_control search_control" placeholder="{{$translations['gn_search'] ?? 'Search'}}">
            </div>  
            <div class="col-md-4 text-md-right mb-3">
                
            </div> 
            <div class="col-md-2 col-6 mb-3">
               
            </div>
            <div class="col-md-2 col-6 mb-3">
                
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="theme_table">
                    <div class="table-responsive">
                        <table id="email_template_listing" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>{{$translations['gn_title'] ?? 'Title'}}</th>
                                    <th>{{$translations['gn_parameter'] ?? 'Parameter'}}</th>
                                    <th>{{$translations['gn_subject'] ?? 'Subject'}}</th>
                                    <th><div class="action">{{$translations['gn_actions'] ?? 'Actions'}}</div></th>
                                </tr>
                            </thead>
                        </table>
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
<script type="text/javascript" src="{{ url('public/js/dashboard/email_template.js') }}"></script>
@endpush