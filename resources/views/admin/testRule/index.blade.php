@extends('layout.app_with_login')
@section('title','Test Rule')
@section('script', url('public/js/dashboard/test_rule.js'))
@section('content')	
<!-- Page Content  -->
<div class="section">
    <div class="container-fluid">
        <h2 class="title">{{'Test Rule'}}</h2>
        <div class="white_box">
            <div class="theme_tab">
                <form name="test-rule-text-form">
                    <div class="row">
                        <div class="col-lg-3"></div>
                          <div class="col-lg-6">
                            <div class="">
                              
                              <div class="form-group">
                                  <label>{{'Live Test Rule In English'}}</label>
                                  <textarea class="form-control icon_control" name="live_test_en" id="live_test_en" rows="3">{{ $test_rule_Data['live_test_en'] ?? '' }}</textarea>
                              </div>
                              <div class="form-group">
                                  <label>{{'Live Test Rule In Hindi'}}</label>
                                  <textarea class="form-control icon_control" name="live_test_hi" id="live_test_hi" rows="3">{{ $test_rule_Data['live_test_hi'] ?? '' }}</textarea>
                              </div>

                              <div class="form-group">
                                  <label>{{'Quizzes Test In English'}}</label>
                                  <textarea class="form-control icon_control" name="quizzes_test_en" id="quizzes_test_en" rows="3">{{ $test_rule_Data['quizzes_test_en'] ?? '' }}</textarea>
                              </div>
                              <div class="form-group">
                                  <label>{{'Quizzes Test In Hindi'}}</label>
                                  <textarea class="form-control icon_control" name="quizzes_test_hi" id="quizzes_test_hi" rows="3">{{ $test_rule_Data['quizzes_test_hi'] ?? '' }}</textarea>
                              </div>

                            </div>
                            <div class="text-center">
                                <button class="theme_btn">Save</button>
                                <a class="theme_btn red_btn ajax_request no_sidebar_active" data-slug="admin/dashboard" href="{{ url('admin/dashboard') }}">{{'Cancel'}}</a>
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

<script type="text/javascript" src="{{ url('public/js/dashboard/test_rule.js') }}"></script>
@endpush