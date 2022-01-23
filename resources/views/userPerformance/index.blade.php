<!DOCTYPE html>
<html>
  <head>
    <title>Digikoach| User Performance</title>
    <meta name="csrf-token" content="{{ @csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{url('/public/images/ic_fevicon.png')}}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ url('public/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.css') }}">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script type="text/javascript" src="{{ url('public/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>

    <link href="https://fonts.googleapis.com/css2?family=Karla&display=swap" rel="stylesheet">
    <style type="text/css">
      body{
        font-family: Karla;
      }
      .nav-tabs {
        display:none;
      }

      @media(min-width:768px) {
          .nav-tabs {
              display: flex;
          }
          
          .card {
              border: none;
          }

          .card .card-header {
              display:none;
          }  

          .card .collapse{
              display:block;
          }
      }

      @media(max-width:767px){
          .tab-content > .tab-pane {
              display: block !important;
              opacity: 1;
          }
      }

      ul#tabs{
          padding:20px 0px
      }
      .card.active.show .collapse {
          display: block;
      }
      .card-body {
          padding: 15px 0px;
      }
      @media(max-width:767px){
          ul#tabs{
              flex-wrap: nowrap;
              overflow-x: auto;
              overflow-y: hidden;
              flex-direction: row;
              flex: 1;
              -webkit-overflow-scrolling: touch;
          }
          ul#tabs li {
              flex: 0 0 auto;
          }
      }
      .collapse.show {
          display: none;
      }

      /* ==============
        Loader
      ===================*/
      #preloader_new {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(238, 238, 238, 0.66);
        z-index: 9999999;
        transition: 0.4s linear;
      }

      #preloader {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(238, 238, 238, 0.66);
        z-index: 9999999;
        transition: 0.4s linear;
      }

      #status {
        width: 40px;
        height: 40px;
        position: absolute;
        left: 50%;
        top: 53%;
        margin: -20px 0 0 -20px;
      }

      .spinner {
        position: absolute;
        width: 78px;
        height: 78px;
        left: 50%;
        top: 50%;
        margin-left: -39px;
        margin-top: -39px;
      }

      .spinner:before {
        content: '';
        position: absolute;
        width: 45px;
        height: 45px;
        top: 50%;
        margin-top: -23px;
        left: 50%;
        margin-left: -23px;
        border-width: 2px 1px;
        border-style: solid;
        border-color: #ff7f33;
        border-radius: 50%;
        -o-border-radius: 50%;
        -ms-border-radius: 50%;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        box-sizing: border-box;
        -o-box-sizing: border-box;
        -ms-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        animation: spin 3.45s infinite;
        -o-animation: spin 3.45s infinite;
        -ms-animation: spin 3.45s infinite;
        -webkit-animation: spin 3.45s infinite;
        -moz-animation: spin 3.45s infinite;
      }
      .spinner:after {
        content: '';
        background-color: #ff7f33;
        position: absolute;
        width: 12px;
        height: 12px;
        top: 50%;
        margin-top: -6px;
        left: 50%;
        margin-left: -6px;
        /*background-color: $primary;*/
        border-radius: 50%;
        -o-border-radius: 50%;
        -ms-border-radius: 50%;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        box-sizing: border-box;
        -o-box-sizing: border-box;
        -ms-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        animation: pulse 6.9s infinite, borderPulse 6.9s infinite;
        -o-animation: pulse 6.9s infinite, borderPulse 6.9s infinite;
        -ms-animation: pulse 6.9s infinite, borderPulse 6.9s infinite;
        -webkit-animation: pulse 6.9s infinite, borderPulse 6.9s infinite;
        -moz-animation: pulse 6.9s infinite, borderPulse 6.9s infinite;
      }

      @keyframes spin {
        0% {
          transform: rotate(0deg);
        }
        50% {
          transform: rotate(360deg);
        }
        100% {
          transform: rotate(1080deg);
        }
      }

      @-o-keyframes spin {
        0% {
          -o-transform: rotate(0deg);
        }
        50% {
          -o-transform: rotate(360deg);
        }
        100% {
          -o-transform: rotate(1080deg);
        }
      }

      @-ms-keyframes spin {
        0% {
          -ms-transform: rotate(0deg);
        }
        50% {
          -ms-transform: rotate(360deg);
        }
        100% {
          -ms-transform: rotate(1080deg);
        }
      }

      @-webkit-keyframes spin {
        0% {
          -webkit-transform: rotate(0deg);
        }
        50% {
          -webkit-transform: rotate(360deg);
        }
        100% {
          -webkit-transform: rotate(1080deg);
        }
      }

      @-moz-keyframes spin {
        0% {
          -moz-transform: rotate(0deg);
        }
        50% {
          -moz-transform: rotate(360deg);
        }
        100% {
          -moz-transform: rotate(1080deg);
        }
      }
    </style>
  </head>
  <body>
    
    <div id="preloader">
        <div id="status">
          <div class="spinner"></div>
        </div>
    </div>
    <div id="preloader_new" style="opacity: 0; display: none;">
        <div id="status_new">
          <div class="spinner"></div>
        </div>
    </div>

    <div class="container-fluid">
      <ul id="tabs" class="nav nav-pills nav-fill" role="tablist">
          <li class="nav-item">
              <a id="tab-A" href="#pane-A" class="nav-link active" data-toggle="tab" role="tab"> Live Test </a>
          </li>
          <li class="nav-item">
              <a id="tab-B" href="#pane-B" class="nav-link" data-toggle="tab" role="tab"> Quiz Test </a>
          </li>
          <li class="nav-item">
              <a id="tab-C" href="#pane-C" class="nav-link" data-toggle="tab" role="tab"> Practice Test </a>
          </li>
          <li class="nav-item">
              <a id="tab-D" href="#pane-D" class="nav-link" data-toggle="tab" role="tab"> Gk Ca Quiz Test </a>
          </li>
      </ul>


      <div id="content" class="tab-content" role="tablist">
          <div id="pane-A" class="card tab-pane fade show active" role="tabpanel" aria-labelledby="tab-A">
              
              <!-- Note: New place of `data-parent` -->
              <div id="collapse-A" class="collapse show" data-parent="#content" role="tabpanel" aria-labelledby="heading-A">
                  <div class="card-body">
                      <div class="container-fluid">
                          <div class="row">
                              <div class="col-12">
                                  <div class="chart_section">
                                      <form id="search-form">
                                          <div class="row">
                                              <div class="col-md-6 col-sm-6"></div>
                                              <div class="col-md-3 col-sm-6">
                                                  <div class="form-group "> 
                                                      <input type="text" name="start_date" id="start_date" class="datepicker1 date_control form-control icon_control pl-1" placeholder="Date From" autocomplete="off">
                                                  </div>
                                              </div>
                                              <div class="col-md-3 col-sm-6">
                                                  <div class="form-group "> 
                                                      <div class="form-group "> 
                                                      <input type="text" name="end_date" id="end_date" class="datepicker1 date_control form-control icon_control pl-1" placeholder="Date To" autocomplete="off">
                                                  </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </form>
                                      <div id="liveTestGraph"></div>
                                  </div>
                              </div>
                          </div>
                        </div>
                  </div>
              </div>
          </div>

          <div id="pane-B" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-B">
              
              <div id="collapse-B" class="collapse" data-parent="#content" role="tabpanel" aria-labelledby="heading-B">
                  <div class="card-body">
                        <div class="container-fluid">
                          <div class="row">
                              <div class="col-12">
                                  <div class="chart_section">
                                      <form id="search-form">
                                          <div class="row">
                                              <div class="col-md-6 col-sm-6"></div>
                                              <div class="col-md-3 col-sm-6">
                                                  <div class="form-group "> 
                                                      <input type="text" name="start_date" id="start_date1" class="datepicker1 date_control form-control icon_control pl-1" placeholder="Date From" autocomplete="off">
                                                  </div>
                                              </div>
                                              <div class="col-md-3 col-sm-6">
                                                  <div class="form-group "> 
                                                      <div class="form-group "> 
                                                      <input type="text" name="end_date" id="end_date1" class="datepicker1 date_control form-control icon_control pl-1" placeholder="Date To" autocomplete="off">
                                                  </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </form>
                                      <div id="quizTestGraph"></div>
                                  </div>
                              </div>
                          </div>
                        </div>                      
                  </div>
              </div>
          </div>

          <div id="pane-C" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-C">
              
              <div id="collapse-C" class="collapse" role="tabpanel" data-parent="#content" aria-labelledby="heading-C">
                  <div class="card-body">
                      
                      <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="chart_section">
                                    <form id="search-form">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6"></div>
                                            <div class="col-md-3 col-sm-6">
                                                <div class="form-group "> 
                                                    <input type="text" name="start_date" id="start_date2" class="datepicker1 date_control form-control icon_control pl-1" placeholder="Date From" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6">
                                                <div class="form-group "> 
                                                    <div class="form-group "> 
                                                    <input type="text" name="end_date" id="end_date2" class="datepicker1 date_control form-control icon_control pl-1" placeholder="Date To" autocomplete="off">
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div id="practiceTestGraph"></div>
                                </div>
                            </div>
                        </div>
                      </div>
                      
                  </div>
              </div>
          </div>

          <div id="pane-D" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-D">
              
              <div id="collapse-D" class="collapse" role="tabpanel" data-parent="#content" aria-labelledby="heading-D">
                  <div class="card-body">              
        
                      <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="chart_section">
                                    <form id="search-form">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6"></div>
                                            <div class="col-md-3 col-sm-6">
                                                <div class="form-group "> 
                                                    <input type="text" name="start_date" id="start_date3" class="datepicker1 date_control form-control icon_control pl-1" placeholder="Date From" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6">
                                                <div class="form-group "> 
                                                    <div class="form-group "> 
                                                    <input type="text" name="end_date" id="end_date3" class="datepicker1 date_control form-control icon_control pl-1" placeholder="Date To" autocomplete="off">
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div id="gkCaTestGraph"></div>
                                </div>
                            </div>
                        </div>
                      </div>
                      
                  </div>
              </div>
          </div>
      </div>
  </div>

  <input type="hidden" id="user_id_data" value="{{$user_id ?? ''}}"> 
  </body> 

  <script src="{{ url('public/js/common/highchart/highcharts.js') }}"></script>
<script src="{{ url('public/js/common/highchart/exporting.js') }}"></script>
<script src="{{ url('public/js/common/highchart/export-data.js') }}"></script>
<script src="{{ url('public/js/common/highchart/accessibility.js') }}"></script>
<script>
  var base_url = '{{ url('/') }}';
  $('.datepicker1').datepicker({
    format: "mm/dd/yyyy",
    autoclose: true
  });

  //_____liveTestGraph_____//

  var liveTestData = <?php  echo isset($testData['LIVE_TEST']) ? json_encode($testData['LIVE_TEST']['user_percentage']):"[]" ?>;
  var liveTestName = <?php  echo isset($testData['LIVE_TEST']) ? json_encode($testData['LIVE_TEST']['exam_name']):"[]" ?>;

  var chartAgency = Highcharts.chart('liveTestGraph', {
      chart: {
          type: 'column'
      },
      title: {
          text: ''
      },
      xAxis: {
          categories: liveTestName,
          crosshair: true
      },
      yAxis: {
          min: 0,
          title: {
              text: 'Percentage %'
          }
      },
      tooltip: {
          headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
          pointFormat: '<tr><td style="color:{series.color};padding:0">{categories.name}</td>' +
                  '<td style="padding:0"><b> Percentage {point.y}%</b></td></tr>',
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
              name: 'Live Test',
              data: liveTestData,
              color: '#2682df'
          }
      ],
      credits: {
          enabled: false
      },
      exporting: {enabled: false}
  },function(chartAgency) { // on complete
    if (chartAgency.series[0].data.length <1) {
      chartAgency.renderer.text('No Data Found', 20, 22).css({color: '#4572A7',fontSize: '16px'}).add()
    }
  });

  
  $(document).on('change', '#start_date, #end_date', function() {
      var name = $(this).attr("name");
      var end_date = '';
      var start_date = '';
      var user_id = $("#user_id_data").val();
      if (name=='start_date') {
        start_date = $(this).val();
        end_date = $(this).parent().parent().parent().find('#end_date').val();
      }else{
        start_date = $(this).parent().parent().parent().parent().find('#start_date').val();
        end_date = $(this).val();
      }
      showLoader(true);
      $.ajax({
          type: "POST",
          dataType: 'json',
          url: base_url+'/user/filter/performance',
          data: {user_id:user_id,start_date: start_date,end_date:end_date,type:'live'},
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(data) {
              chartAgency.series[0].setData(data.percentage);
              chartAgency.xAxis[0].setCategories(data.name);
              if (data.percentage.length == 0) {
                chartAgency.renderer.text('No Data Found', 20, 22).attr({textaatr: 1}).css({color: '#4572A7',fontSize: '16px'}).add();
              }else{
                $('[textaatr="1"]').hide(); 
              }
              showLoader(false);         
          }
      });
  });


  //_____ quizTestGraph _____//

  var quizTestData = <?php  echo isset($testData['QUIZZES']) ? json_encode($testData['QUIZZES']['user_percentage']):"[]" ?>;
  var quizTestName = <?php  echo isset($testData['QUIZZES']) ? json_encode($testData['QUIZZES']['exam_name']):"[]" ?>;

  var chartAgency1 = Highcharts.chart('quizTestGraph', {
      chart: {
          type: 'column'
      },
      title: {
          text: ''
      },
      xAxis: {
          categories: quizTestName,
          crosshair: true
      },
      yAxis: {
          min: 0,
          title: {
              text: 'Percentage %'
          }
      },
      tooltip: {
          headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
          pointFormat: '<tr><td style="color:{series.color};padding:0">{categories.name}</td>' +
                  '<td style="padding:0"><b> Percentage {point.y}%</b></td></tr>',
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
              name: 'Quiz Test',
              data: quizTestData,
              color: '#2682df'
          }
      ],
      credits: {
          enabled: false
      },
      exporting: {enabled: false}
  },function(chartAgency1) { // on complete
    if (chartAgency1.series[0].data.length <1) {
      chartAgency1.renderer.text('No Data Found', 20, 22).css({color: '#4572A7',fontSize: '16px'}).add()
    }
  });

  $(document).on('change', '#start_date1, #end_date1', function() {
      
      var name = $(this).attr("name");
      var end_date = '';
      var start_date = '';
      var user_id = $("#user_id_data").val();
      if (name=='start_date') {
        start_date = $(this).val();
        end_date = $(this).parent().parent().parent().find('#end_date1').val();
      }else{
        start_date = $(this).parent().parent().parent().parent().find('#start_date1').val();
        end_date = $(this).val();
      }
      showLoader(true);
      $.ajax({
          type: "POST",
          dataType: 'json',
          url: base_url+'/user/filter/performance',
          data: {user_id:user_id,start_date: start_date,end_date:end_date,type:'quiz'},
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(data) {
              chartAgency1.series[0].setData(data.percentage);
              chartAgency1.xAxis[0].setCategories(data.name);
              if (data.percentage.length == 0) {
                chartAgency1.renderer.text('No Data Found', 20, 22).attr({textaatr: 1}).css({color: '#4572A7',fontSize: '16px'}).add();
              }else{
                $('[textaatr="1"]').hide(); 
              }
              showLoader(false);    
          }
      });
  });



  //_____ practiceTestGraph _____//

  var practiceTestData = <?php  echo isset($testData['PRACTICE_TEST']) ? json_encode($testData['PRACTICE_TEST']['user_percentage']):"[]" ?>;
  var practiceTestName = <?php  echo isset($testData['PRACTICE_TEST']) ? json_encode($testData['PRACTICE_TEST']['exam_name']):"[]" ?>;

  var chartAgency2 = Highcharts.chart('practiceTestGraph', {
      chart: {
          type: 'column'
      },
      title: {
          text: ''
      },
      xAxis: {
          categories: practiceTestName,
          crosshair: true
      },
      yAxis: {
          min: 0,
          title: {
              text: 'Percentage %'
          }
      },
      tooltip: {
          headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
          pointFormat: '<tr><td style="color:{series.color};padding:0">{categories.name}</td>' +
                  '<td style="padding:0"><b> Percentage {point.y}%</b></td></tr>',
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
              name: 'Practice Test',
              data: practiceTestData,
              color: '#2682df'
          }
      ],
      credits: {
          enabled: false
      },
      exporting: {enabled: false}
  },function(chartAgency2) { // on complete
    if (chartAgency2.series[0].data.length <1) {
      chartAgency2.renderer.text('No Data Found', 20, 22).css({color: '#4572A7',fontSize: '16px'}).add()
    }
  });

  $(document).on('change', '#start_date2, #end_date2', function() {
      
      var name = $(this).attr("name");
      var end_date = '';
      var start_date = '';
      var user_id = $("#user_id_data").val();
      if (name=='start_date') {
        start_date = $(this).val();
        end_date = $(this).parent().parent().parent().find('#end_date2').val();
      }else{
        start_date = $(this).parent().parent().parent().parent().find('#start_date2').val();
        end_date = $(this).val();
      }
      showLoader(true);
      $.ajax({
          type: "POST",
          dataType: 'json',
          url: base_url+'/user/filter/performance',
          data: {user_id:user_id,start_date: start_date,end_date:end_date,type:'practice'},
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(data) {
              chartAgency2.series[0].setData(data.percentage);
              chartAgency2.xAxis[0].setCategories(data.name);
              if (data.percentage.length == 0) {
                chartAgency2.renderer.text('No Data Found', 20, 22).attr({textaatr: 1}).css({color: '#4572A7',fontSize: '16px'}).add();
              }else{
                $('[textaatr="1"]').hide(); 
              }      
              showLoader(false);   
          }
      });
  });




  // //_____ gkCaTestGraph _____//

  var gkCaTestData = <?php  echo isset($testData['GK_CA']) ? json_encode($testData['GK_CA']['user_percentage']):"[]" ?>;
  var gkCaTestName = <?php  echo isset($testData['GK_CA']) ? json_encode($testData['GK_CA']['exam_name']):"[]" ?>;

  var chartAgency3 = Highcharts.chart('gkCaTestGraph', {
      chart: {
          type: 'column'
      },
      title: {
          text: ''
      },
      xAxis: {
          categories: gkCaTestName,
          crosshair: true
      },
      yAxis: {
          min: 0,
          title: {
              text: 'Percentage %'
          }
      },
      tooltip: {
          headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
          pointFormat: '<tr><td style="color:{series.color};padding:0">{categories.name}</td>' +
                  '<td style="padding:0"><b> Percentage {point.y}%</b></td></tr>',
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
              name: 'Gk & Ca Test',
              data: gkCaTestData,
              color: '#2682df'
          }
      ],
      credits: {
          enabled: false
      },
      exporting: {enabled: false}
  },function(chartAgency3) { // on complete
    if (chartAgency3.series[0].data.length <1) {
      chartAgency3.renderer.text('No Data Found', 20, 22).css({color: '#4572A7',fontSize: '16px'}).add()
    }
  });

  $(document).on('change', '#start_date3, #end_date3', function() {
      
      var name = $(this).attr("name");
      var end_date = '';
      var start_date = '';
      var user_id = $("#user_id_data").val();
      if (name=='start_date') {
        start_date = $(this).val();
        end_date = $(this).parent().parent().parent().find('#end_date3').val();
      }else{
        start_date = $(this).parent().parent().parent().parent().find('#start_date3').val();
        end_date = $(this).val();
      }
      showLoader(true);
      $.ajax({
          type: "POST",
          dataType: 'json',
          url: base_url+'/user/filter/performance',
          data: {user_id:user_id,start_date: start_date,end_date:end_date,type:'gk_ca'},
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(data) {
              chartAgency3.series[0].setData(data.percentage);
              chartAgency3.xAxis[0].setCategories(data.name);
              if (data.percentage.length == 0) {
                chartAgency3.renderer.text('No Data Found', 20, 22).attr({textaatr: 1}).css({color: '#4572A7',fontSize: '16px'}).add();
              }else{
                $('[textaatr="1"]').hide(); 
              }        
              showLoader(false); 
          }
      });
  });
  
  $('#preloader').css('display','');
      $(window).on('load', function(){
      $('#preloader').css('display','none');
      $('#preloader').css('opacity','0');
      $('#contents').css('opacity','1');
  });
  function showLoader($show){
      if($show){
        $('#preloader_new').show();
        $('#preloader_new').css('opacity',1);
      }else{
        $('#preloader_new').hide();
        $('#preloader_new').css('opacity',0);
      }
  }
</script>
<script type="text/javascript">
  $(function() {
    showLoader(false);
  });
</script>
</html>
  