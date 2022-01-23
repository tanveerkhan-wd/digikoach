@extends('layout.app_with_login')
@section('title', 'Dashboard')
@section('content')	
<!-- Page Content  -->
<div class="section">
	<div class="container-fluid">
		<div class="row text-center equal_height">
            @php
                $accessPriData = session()->get('accessPriData');
            @endphp
            @if(Auth::user()->user_type==0)
            <div class="col-md-3 col-sm-6 col-xs-6 equal_height_container">
                <div class="dash_tile">
                    <a class="ajax_request" data-slug="admin/appUsers" href="{{url('admin/appUsers')}}">
                        <div class="dash_tile_top">
                        	<img src="{{ url('public/images/ic_dashoard_color.png') }}" class="tile_img">
               				<img src="{{ url('public/images/ic_dashoard_color.png') }}" class="tile_hover_img">
                        </div>
                        <div class="dash_tile_bottom">
                            <p>App Users</p>
                            <h3>{{$totalAppUser ?? ''}}</h3>
                        </div>
                    </a>
                </div>
            </div>
            @endif
            @if(Auth::user()->user_type==0 || !empty($accessPriData['Live_Test']) && $accessPriData['Live_Test']->view==true)
            <div class="col-md-3 col-sm-6 col-xs-6 equal_height_container">
                <div class="dash_tile">
                    <a class="ajax_request" data-slug="admin/liveTest" href="{{url('admin/liveTest')}}">
                        <div class="dash_tile_top">
                            <img src="{{ url('public/images/ic_test-results_color.png') }}" class="tile_img">
               				<img src="{{ url('public/images/ic_test-results_color.png') }}" class="tile_hover_img">
                        </div>
                        <div class="dash_tile_bottom">
                            <p>Live Test</p>
                            <h3>{{$totalLiveTest ?? ''}}</h3>
                        </div>
                    </a>
                </div>
            </div>
            @endif
            @if(Auth::user()->user_type==0 || !empty($accessPriData['Quizz_Test']) && $accessPriData['Quizz_Test']->view==true)
            <div class="col-md-3 col-sm-6 col-xs-6 equal_height_container">
                <div class="dash_tile">
                    <a class="ajax_request" data-slug="admin/quizTest" href="{{url('admin/quizTest')}}">
                        <div class="dash_tile_top">
                        	<img src="{{ url('public/images/ic_ebook_color.png') }}" class="tile_img">
               				<img src="{{ url('public/images/ic_ebook_color.png') }}" class="tile_hover_img">
                        </div>
                        <div class="dash_tile_bottom">
                            <p>Quiz Test</p>
                            <h3>{{$totalQuizTest ?? ''}}</h3>
                        </div>
                    </a>
                </div>
            </div>
            @endif
            @if(Auth::user()->user_type==0 || !empty($accessPriData['Practice_Test']) && $accessPriData['Practice_Test']->view==true)
            <div class="col-md-3 col-sm-6 col-xs-6 equal_height_container">
                <div class="dash_tile">
                    <a class="ajax_request" data-slug="admin/practiceTest" href="{{url('admin/practiceTest')}}">
                        <div class="dash_tile_top">
                        	<img src="{{ url('public/images/ic_test-results_color.png') }}" class="tile_img">
               				<img src="{{ url('public/images/ic_test-results_color.png') }}" class="tile_hover_img"> 
                        </div>
                        <div class="dash_tile_bottom">
                            <p>Practice Test</p>
                            <h3>{{$totalpracticeTest ?? ''}}</h3>
                        </div>
                    </a>
                </div>
            </div>
            @endif
            @if(Auth::user()->user_type==0 || !empty($accessPriData['GK_CA_Quizz']) && $accessPriData['GK_CA_Quizz']->view==true)
            <div class="col-md-3 col-sm-6 col-xs-6 equal_height_container">
                <div class="dash_tile">
                    <a class="ajax_request" data-slug="admin/gkCa/quizTest" href="{{url('admin/gkCa/quizTest')}}">
                        <div class="dash_tile_top">
                        	<img src="{{ url('public/images/ic_educational-programs_color.png') }}" class="tile_img">
               				<img src="{{ url('public/images/ic_educational-programs_color.png') }}" class="tile_hover_img">
                        </div>
                        <div class="dash_tile_bottom">
                            <p>Gk Quiz Test</p>
                            <h3>{{$totalgkQuizTest ?? ''}}</h3>
                        </div>
                    </a>
                </div>
            </div>
            @endif
            @if(Auth::user()->user_type==0 || !empty($accessPriData['Live_Test']) && $accessPriData['Live_Test']->view==true)
            <div class="col-md-3 col-sm-6 col-xs-6 equal_height_container">
                <div class="dash_tile">
                    <a href="{{url('admin/liveTest?attempt=true')}}">
                        <div class="dash_tile_top">
                            <img src="{{ url('public/images/ic_test-results_color.png') }}" class="tile_img">
               				<img src="{{ url('public/images/ic_test-results_color.png') }}" class="tile_hover_img">
                        </div>
                        <div class="dash_tile_bottom">
                            <p>Live Test Attempted</p>
                            <h3>{{$countUserAttLiveTest ?? ''}}</h3>
                        </div>
                    </a>
                </div>
            </div>
            @endif
            @if(Auth::user()->user_type==0 || !empty($accessPriData['Quizz_Test']) && $accessPriData['Quizz_Test']->view==true)
            <div class="col-md-3 col-sm-6 col-xs-6 equal_height_container">
                <div class="dash_tile">
                    <a href="{{url('admin/quizTest?attempt=true')}}">
                        <div class="dash_tile_top">
                        	<img src="{{ url('public/images/ic_ebook_color.png') }}" class="tile_img">
               				<img src="{{ url('public/images/ic_ebook_color.png') }}" class="tile_hover_img">
                        </div>
                        <div class="dash_tile_bottom">
                            <p>Quiz Test Attempted</p>
                            <h3>{{$countUserAttQuizTest ?? ''}}</h3>
                        </div>
                    </a>
                </div>
            </div>
            @endif
            @if(Auth::user()->user_type==0 || !empty($accessPriData['Practice_Test']) && $accessPriData['Practice_Test']->view==true)
            <div class="col-md-3 col-sm-6 col-xs-6 equal_height_container">
                <div class="dash_tile">
                    <a href="{{url('admin/practiceTest?attempt=true')}}">
                        <div class="dash_tile_top">
                        	<img src="{{ url('public/images/ic_test-results_color.png') }}" class="tile_img">
               				<img src="{{ url('public/images/ic_test-results_color.png') }}" class="tile_hover_img"> 
                        </div>
                        <div class="dash_tile_bottom">
                            <p>Practice Test Attempted</p>
                            <h3>{{$countUserAttPrcTest ?? ''}}</h3>
                        </div>
                    </a>
                </div>
            </div>
            @endif
            @if(Auth::user()->user_type==0 || !empty($accessPriData['GK_CA_Quizz']) && $accessPriData['GK_CA_Quizz']->view==true)
            <div class="col-md-3 col-sm-6 col-xs-6 equal_height_container">
                <div class="dash_tile">
                    <a href="{{url('admin/gkCa/quizTest?attempt=true')}}">
                        <div class="dash_tile_top">
                        	<img src="{{ url('public/images/ic_educational-programs_color.png') }}" class="tile_img">
               				<img src="{{ url('public/images/ic_educational-programs_color.png') }}" class="tile_hover_img">
                        </div>
                        <div class="dash_tile_bottom">
                            <p>Gk Quiz Attempted</p>
                            <h3>{{$countUserAttGkQuizTest ?? ''}}</h3>
                        </div>
                    </a>
                </div>
            </div>
            @endif
            @if(Auth::user()->user_type==0 || !empty($accessPriData) && isset($accessPriData['Question_Bank_Live_Test']->view) &&  $accessPriData['Question_Bank_Live_Test']->view==true || isset($accessPriData['Question_Bank_Quizz_Test']->view) && $accessPriData['Question_Bank_Quizz_Test']->view==true || isset($accessPriData['Question_Bank_Practice_Test']->view) && $accessPriData['Question_Bank_Practice_Test']->view==true || isset($accessPriData['Question_Bank_GK_CA_Test']->view) && $accessPriData['Question_Bank_GK_CA_Test']->view==true )

            <div class="col-md-3 col-sm-6 col-xs-6 equal_height_container">
                <div class="dash_tile">
                    <a class="ajax_request" data-slug="admin/questions" href="{{url('admin/questions')}}">
                        <div class="dash_tile_top">
                        	<img src="{{ url('public/images/ic_lock_color.png') }}" class="tile_img">
               				<img src="{{ url('public/images/ic_lock_color.png') }}" class="tile_hover_img">
                        </div>
                        <div class="dash_tile_bottom">
                            <p>Total Questions</p>
                            <h3>{{$totalQue ?? ''}}</h3>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-6 equal_height_container">
                <div class="dash_tile">
                    <a href="{{url('admin/questions?unused=true')}}">
                        <div class="dash_tile_top">
                        	<img src="{{ url('public/images/ic_lock_color.png') }}" class="tile_img">
               				<img src="{{ url('public/images/ic_lock_color.png') }}" class="tile_hover_img">
                        </div>
                        <div class="dash_tile_bottom">
                            <p>Fresh Questions</p>
                            <h3>{{$totalFreshQue ?? ''}}</h3>
                        </div>
                    </a>
                </div>
            </div>
            @endif
        </div>
	</div>

    @if(Auth::check() && Auth::User()->user_type==0)
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="chart_section">
                        <form id="search-form">
                            <div class="row">
                                <div class="col-md-9 col-sm-6"></div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group "> 
                                        <!-- <label>Purpose</label> -->
                                        <select name="stu_list_purpose" class="form-control select2 dropdown_control" required="" id='studentAccCreation_dropdown'>
                                        	<option>select</option>
                                        	@foreach($year_stuAccCre as $key=>$value)
                                            <option value="{{$value->year}}" @if($value->year==date('Y')) selected="selected" @endif>{{$value->year}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div id="studentAccCreation"></div>
                    </div>
                </div>
            </div>
        </div>


    	<div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="chart_section">
                        <form id="search-form">
                            <div class="row">
                                <div class="col-md-9 col-sm-6"></div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group "> 
                                        <!-- <label>Purpose</label> -->
                                        <select name="live_test_purpose" class="form-control select2 dropdown_control" required="" id='live_test_dropdown'>
                                        	<option>select</option>
                                        	@foreach($year_live_test as $key=>$value)
                                            <option value="{{$value->year}}" @if($value->year==date('Y')) selected="selected" @endif>{{$value->year}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div id="live_test"></div>
                    </div>
                </div>
            </div>
        </div>


    	<div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="chart_section">
                        <form id="search-form">
                            <div class="row">
                                <div class="col-md-9 col-sm-6"></div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group "> 
                                        <!-- <label>Purpose</label> -->
                                        <select name="quiz_test_purpose" class="form-control select2 dropdown_control" required="" id='quiz_test_dropdown'>
                                        	<option>select</option>
                                            @foreach($year_quiz_test as $key=>$value)
                                            <option value="{{$value->year}}" @if($value->year==date('Y')) selected="selected" @endif>{{$value->year}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div id="quiz_test"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="chart_section">
                        <form id="search-form">
                            <div class="row">
                                <div class="col-md-9 col-sm-6"></div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group "> 
                                        <!-- <label>Purpose</label> -->
                                        <select name="practice_test_purpose" class="form-control select2 dropdown_control" required="" id='practice_test_dropdown'>
                                        	<option>select</option>
                                            @foreach($year_practice_test as $key=>$value)
                                            <option value="{{$value->year}}" @if($value->year==date('Y')) selected="selected" @endif>{{$value->year}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div id="practice_test"></div>
                    </div>
                </div>
            </div>
        </div>


        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="chart_section">
                        <form id="search-form">
                            <div class="row">
                                <div class="col-md-9 col-sm-6"></div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group "> 
                                        <!-- <label>Purpose</label> -->
                                        <select name="gk_quiz_test_purpose" class="form-control select2 dropdown_control" required="" id='gk_quiz_test_dropdown'>
                                        	<option>select</option>
                                            @foreach($year_gk_quiz_test as $key=>$value)
                                            <option value="{{$value->year}}" @if($value->year==date('Y')) selected="selected" @endif>{{$value->year}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div id="gk_quiz_test"></div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@endsection
@push('custom-styles')
    <style type="text/css">
        .dash_tile_top img{
            width: 39px;
            height: auto;
        }
    </style>
@endpush
@push('custom-scripts')
<script src="{{ url('public/js/common/highchart/highcharts.js') }}"></script>
<script src="{{ url('public/js/common/highchart/exporting.js') }}"></script>
<script src="{{ url('public/js/common/highchart/export-data.js') }}"></script>
<script src="{{ url('public/js/common/highchart/accessibility.js') }}"></script>
<script type="text/javascript" src=""></script>
<script>


	//_____studentAccCreation_____//
	var stuAccdata = <?php echo $studentAccCreation_total ?>;
	var chartAgency = Highcharts.chart('studentAccCreation', {
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: ''
	    },
	    xAxis: {
	        categories: [
	            'Jan',
	            'Feb',
	            'Mar',
	            'Apr',
	            'May',
	            'Jun',
	            'Jul',
	            'Aug',
	            'Sep',
	            'Oct',
	            'Nov',
	            'Dec'
	        ],
	        crosshair: true
	    },
	    yAxis: {
	        min: 0,
	        title: {
	            text: 'Students Account Creation'
	        }
	    },
	    tooltip: {
	        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
	        pointFormat: '<tr><td style="color:{series.color};padding:0">{categories.name}</td>' +
	                '<td style="padding:0"><b> Students {point.y}</b></td></tr>',
	        footerFormat: '</table>',
	        shared: true,
	        useHTML: true
	    },
	    plotOptions: {
	        column: {
	            pointPadding: 0.2,
	            borderWidth: 0
	        }
	    },
	    series: [{
	            name: 'Students Account Creation',
	            data: stuAccdata,
	            color: '#2682df'
	        }
	    ],
	    credits: {
	        enabled: false
	    },
	    exporting: {enabled: false}
	});


	$(document).on('change', '#studentAccCreation_dropdown', function() {
	    $('.loader-outer-container').css('display', 'table');
	    var year = $(this).val();
	    $.ajax({
	        type: "POST",
	        dataType: 'json',
	        url: base_url+'/admin/student_acc_creation/chart/data',
	        data: {year: year},
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        success: function(data) {
	            chartAgency.series[0].setData(data);
	            $('.loader-outer-container').css('display', 'none');
	        }
	    });
	});

	//--------LIVE TEST ATTEMPT---------//
    var data = <?php echo $live_test_total ?>;
	var chart = Highcharts.chart('live_test', {
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: ''
	    },
	    xAxis: {
	        categories: [
	            'Jan',
	            'Feb',
	            'Mar',
	            'Apr',
	            'May',
	            'Jun',
	            'Jul',
	            'Aug',
	            'Sep',
	            'Oct',
	            'Nov',
	            'Dec'
	        ],
	        crosshair: true
	    },
	    yAxis: {
	        min: 0,
	        title: {
	            text: 'Live Test Attempted'
	        }
	    },
	    tooltip: {
	        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
	        pointFormat: '<tr><td style="color:{series.color};padding:0">{categories.name}</td>' +
	                '<td style="padding:0"><b> Test Users {point.y}</b></td></tr>',
	        footerFormat: '</table>',
	        shared: true,
	        useHTML: true
	    },
	    plotOptions: {
	        column: {
	            pointPadding: 0.2,
	            borderWidth: 0
	        }
	    },
	    series: [{
	            name: 'Live Test Attempted',
	            data: data,
	            color: '#2682df'
	        }
	    ],
	    credits: {
	        enabled: false
	    },
	    exporting: {enabled: false}
	});


	$(document).on('change', '#live_test_dropdown', function() {
	    $('.loader-outer-container').css('display', 'table');
	    var year = $(this).val();
	    $.ajax({
	        type: "POST",
	        dataType: 'json',
	        url: base_url+'/admin/live_test/chart/data',
	        data: {year: year},
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        success: function(data) {
	            chart.series[0].setData(data);
	            $('.loader-outer-container').css('display', 'none');
	        }
	    });
	});


	//--------QUIZ TEST ATTEMPT---------//
    var data1 = <?php echo $quiz_test_total ?>;
	var chart1 = Highcharts.chart('quiz_test', {
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: ''
	    },
	    xAxis: {
	        categories: [
	            'Jan',
	            'Feb',
	            'Mar',
	            'Apr',
	            'May',
	            'Jun',
	            'Jul',
	            'Aug',
	            'Sep',
	            'Oct',
	            'Nov',
	            'Dec'
	        ],
	        crosshair: true
	    },
	    yAxis: {
	        min: 0,
	        title: {
	            text: 'Quiz Test Attempted'
	        }
	    },
	    tooltip: {
	        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
	        pointFormat: '<tr><td style="color:{series.color};padding:0">{categories.name}</td>' +
	                '<td style="padding:0"><b> Test Users {point.y}</b></td></tr>',
	        footerFormat: '</table>',
	        shared: true,
	        useHTML: true
	    },
	    plotOptions: {
	        column: {
	            pointPadding: 0.2,
	            borderWidth: 0
	        }
	    },
	    series: [{
	            name: 'Quiz Test Attempted',
	            data: data1,
	            color: '#2682df'
	        }
	    ],
	    credits: {
	        enabled: false
	    },
	    exporting: {enabled: false}
	});


	$(document).on('change', '#quiz_test_dropdown', function() {
	    $('.loader-outer-container').css('display', 'table');
	    var year = $(this).val();
	    $.ajax({
	        type: "POST",
	        dataType: 'json',
	        url: base_url+'/admin/quiz_test/chart/data',
	        data: {year: year},
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        success: function(data) {
	            chart1.series[0].setData(data);
	            $('.loader-outer-container').css('display', 'none');
	        }
	    });
	});



	//--------PRACTICE TEST ATTEMPT---------//
    var data2 = <?php echo $practice_test_total ?>;
	var chart2 = Highcharts.chart('practice_test', {
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: ''
	    },
	    xAxis: {
	        categories: [
	            'Jan',
	            'Feb',
	            'Mar',
	            'Apr',
	            'May',
	            'Jun',
	            'Jul',
	            'Aug',
	            'Sep',
	            'Oct',
	            'Nov',
	            'Dec'
	        ],
	        crosshair: true
	    },
	    yAxis: {
	        min: 0,
	        title: {
	            text: 'Practice Test Attempted'
	        }
	    },
	    tooltip: {
	        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
	        pointFormat: '<tr><td style="color:{series.color};padding:0">{categories.name}</td>' +
	                '<td style="padding:0"><b> Test Users {point.y}</b></td></tr>',
	        footerFormat: '</table>',
	        shared: true,
	        useHTML: true
	    },
	    plotOptions: {
	        column: {
	            pointPadding: 0.2,
	            borderWidth: 0
	        }
	    },
	    series: [{
	            name: 'Practice Test Attempted',
	            data: data2,
	            color: '#2682df'
	        }
	    ],
	    credits: {
	        enabled: false
	    },
	    exporting: {enabled: false}
	});


	$(document).on('change', '#practice_test_dropdown', function() {
	    $('.loader-outer-container').css('display', 'table');
	    var year = $(this).val();
	    $.ajax({
	        type: "POST",
	        dataType: 'json',
	        url: base_url+'/admin/practice_test/chart/data',
	        data: {year: year},
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        success: function(data) {
	            chart2.series[0].setData(data);
	            $('.loader-outer-container').css('display', 'none');
	        }
	    });
	});



	//--------GK QUIZ TEST ATTEMPT---------//
    var data3 = <?php echo $gk_quiz_test_total ?>;
	var chart3 = Highcharts.chart('gk_quiz_test', {
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: ''
	    },
	    xAxis: {
	        categories: [
	            'Jan',
	            'Feb',
	            'Mar',
	            'Apr',
	            'May',
	            'Jun',
	            'Jul',
	            'Aug',
	            'Sep',
	            'Oct',
	            'Nov',
	            'Dec'
	        ],
	        crosshair: true
	    },
	    yAxis: {
	        min: 0,
	        title: {
	            text: 'Gk Quiz Test Attempted'
	        }
	    },
	    tooltip: {
	        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
	        pointFormat: '<tr><td style="color:{series.color};padding:0">{categories.name}</td>' +
	                '<td style="padding:0"><b> Test Users {point.y}</b></td></tr>',
	        footerFormat: '</table>',
	        shared: true,
	        useHTML: true
	    },
	    plotOptions: {
	        column: {
	            pointPadding: 0.2,
	            borderWidth: 0
	        }
	    },
	    series: [{
	            name: 'Gk Quiz Test Attempted',
	            data: data3,
	            color: '#2682df'
	        }
	    ],
	    credits: {
	        enabled: false
	    },
	    exporting: {enabled: false}
	});


	$(document).on('change', '#gk_quiz_test_dropdown', function() {
	    $('.loader-outer-container').css('display', 'table');
	    var year = $(this).val();
	    $.ajax({
	        type: "POST",
	        dataType: 'json',
	        url: base_url+'/admin/gk_quiz_test/chart/data',
	        data: {year: year},
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        success: function(data) {
	            chart3.series[0].setData(data);
	            $('.loader-outer-container').css('display', 'none');
	        }
	    });
	});

</script>
@endpush