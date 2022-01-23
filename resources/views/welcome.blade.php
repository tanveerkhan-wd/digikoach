<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{$setting['seo']['title'] ?? 'Digikoach|Home'}}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="{{$setting['seo']['title']}}">
    <meta name="description" content="{{$setting['seo']['desc']}}">
    <meta property="og:description" content="{{$setting['seo']['desc']}}">
    <meta property="og:image" content="{{url('/public/storage/'.Config::get('siteglobal.images_dirs.SETTING'))}}{{'/'.$setting['head']['banner'] ?? ''}}">
    
    <link rel="icon" type="image/x-icon" href="{{url('/public/images/ic_fevicon.png')}}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{url('/public/frontend/css/owl.carousel.min.css')}}">

    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.5.0/css/all.css'>
    <link rel="stylesheet" type="text/css" href="{{url('/public/frontend/css/animate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('/public/frontend/css/custom.css')}}">
    <script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="{{url('/public/frontend/js/wow.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
    wow = new WOW(
        {
            animateClass: 'animated',
            offset: 100,
            callback: function (box) {
                console.log("WOW: animating <" + box.tagName.toLowerCase() + ">")
            }
        }
    );
    new WOW().init();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>
    <style>
        label.error_msg{font-size: 12px;color: #f10909e0;}
    </style>
</head>

<body  id="page-top">
    <div class="inner_container">
        <div class="overlay" onClick="closeOverlay()"></div>

        <nav class="navbar navbar-expand-lg fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{url('/')}}" > <img src="{{url('/public/storage/'.Config::get('siteglobal.images_dirs.SETTING'))}}{{'/'.$setting['head']['logo'] ?? ''}}" alt="Logo" class="logo"> </a>
                  
                <a  id="slide" class="navbar-toggle mobile-menu-style d-block d-lg-none" ><i class="fas fa-bars"></i></a>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navigation navbar-nav ml-auto menu-center" id="menu-center">
                        <a class="nav-item nav-link hvr-underline-from-left active" href="#About">About Us</a>
                        <a class="nav-item nav-link hvr-underline-from-left" href="#Features">Features</a>
                        <a class="nav-item nav-link hvr-underline-from-left" href="#Download">Download</a>
                        <a class="nav-item nav-link hvr-underline-from-left" href="#Testimonials">Testimonials</a>
                        <a class="nav-item nav-link hvr-underline-from-left" href="#Contact">Contact Us</a>
                    </div>
                </div>
            </div>
        </nav>
        <section class="visible-xs">
            <div class="sideoff-off d-block d-lg-none">
                
                <div class="navbar-header">
                    <a id="slideclose" class="navbar-toggle mobile-menu-style"><i class="fas fa-times"></i></a>
                </div>

                <div class="navbar-nav ml-auto menu-center" id="menu-center1">
                    <a class="nav-item nav-link hvr-underline-from-left active" href="#About">About Us</a>
                    <a class="nav-item nav-link hvr-underline-from-left" href="#Features">Features</a>
                    <a class="nav-item nav-link hvr-underline-from-left" href="#Download">Download</a>
                    <a class="nav-item nav-link hvr-underline-from-left" href="#Testimonials">Testimonials</a>
                    <a class="nav-item nav-link hvr-underline-from-left" href="#Contact">Contact Us</a>  
                </div>
            </div>
        </section>


        <section class="hero-banner clearfix" id="home">
            <div class="left-hero-banner">
                <img src="{{url('/public/storage/'.Config::get('siteglobal.images_dirs.SETTING'))}}{{'/'.$setting['head']['banner'] ?? ''}}" alt="" class="img-fluid wow zoomIn slower banner-image">
            </div> 
            <div class="right-hero-banner">
                <div>
                    <h1>{{ $setting['head']['line1'] ?? '' }}</h1>
                    <p>{{ $setting['head']['line2'] ?? '' }}</p>
                </div>
            </div>      
        </section>

        <section class="about-section wow zoomIn delay-1s" id="About">
            <div class="container">
                <div class="section_title">                 
                    <h2>{{$setting['about']['title'] ?? 'About Us'}}</h2>
                </div>
                <div class="row flex-md-row-reverse">
                    <div class="col-md-6 text-md-center">
                        <img src="{{ url('/public/storage/'.Config::get('siteglobal.images_dirs.SETTING'))}}{{'/'.$setting['about']['image'] ?? ''}}">
                    </div>
                    <div class="col-md-6">  
                        <p>{{ $setting['about']['tag_line'] ?? '' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-8">
                        <div class="about_vdo">
                            @php
                                $data = $setting['feature']['vi_link'];    
                                $whatIWant = substr($data, strpos($data, "=") + 1);

                            @endphp
                            <iframe style="width: 100%;height: 260px;" id="video_frame"
                                src="https://www.youtube.com/embed{{'/'.$whatIWant}}" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" frameborder="0"  allowfullscreen>
                            </iframe>
                            {{-- <img src="{{url('/public/frontend/images/ic_play_btn.png')}}" class="ply_btn" id="playButton"> --}}
                        </div>
                    </div>
                    <div class="col-lg-2"></div>
                </div>
            </div>
        </section>

        <section class="feature-section gray_bg" id="Features">
            <div class="container">
                <div class="section_title text-right">                  
                    <h2>{{$setting['feature']['head'] ?? 'Features'}}</h2>
                </div>
                <div class="row equal_height">
                    <div class="col-md-4 col-sm-6 equal_height_container wow zoomIn slow">
                        <div class="feature_box">
                            <div class="feature_img_box">
                                <img src="{{url('/public/storage/'.Config::get('siteglobal.images_dirs.FEATURE'))}}{{'/'.$setting['feature']['img1'] ?? ''}}">
                            </div>
                            <h3>{{$setting['feature']['title1'] ?? ''}}</h3>
                            <p>{{$setting['feature']['desc1'] ?? ''}}</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 equal_height_container wow zoomIn slow">
                        <div class="feature_box">
                            <div class="feature_img_box">
                                <img src="{{url('/public/storage/'.Config::get('siteglobal.images_dirs.FEATURE'))}}{{'/'.$setting['feature']['img2'] ?? ''}}">
                            </div>
                            <h3>{{$setting['feature']['title2'] ?? ''}}</h3>
                            <p>{{$setting['feature']['desc2'] ?? ''}}</p>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 equal_height_container wow zoomIn slow">
                        <div class="feature_box">
                            <div class="feature_img_box">
                                <img src="{{url('/public/storage/'.Config::get('siteglobal.images_dirs.FEATURE'))}}{{'/'.$setting['feature']['img3'] ?? ''}}">
                            </div>
                            <h3>{{$setting['feature']['title3'] ?? ''}}</h3>
                            <p>{{$setting['feature']['desc3'] ?? ''}}</p>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 equal_height_container wow zoomIn slow">
                        <div class="feature_box">
                            <div class="feature_img_box">
                                <img src="{{url('/public/storage/'.Config::get('siteglobal.images_dirs.FEATURE'))}}{{'/'.$setting['feature']['img4'] ?? ''}}">
                            </div>
                            <h3>{{$setting['feature']['title4'] ?? ''}}</h3>
                            <p>{{$setting['feature']['desc4'] ?? ''}}</p>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 equal_height_container wow zoomIn slow">
                        <div class="feature_box">
                            <div class="feature_img_box">
                                <img src="{{url('/public/storage/'.Config::get('siteglobal.images_dirs.FEATURE'))}}{{'/'.$setting['feature']['img5'] ?? ''}}">
                            </div>
                            <h3>{{$setting['feature']['title5'] ?? ''}}</h3>
                            <p>{{$setting['feature']['desc5'] ?? ''}}</p>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 equal_height_container wow zoomIn slow">
                        <div class="feature_box">
                            <div class="feature_img_box">
                                <img src="{{url('/public/storage/'.Config::get('siteglobal.images_dirs.FEATURE'))}}{{'/'.$setting['feature']['img6'] ?? ''}}">
                            </div>
                            <h3>{{$setting['feature']['title6'] ?? ''}}</h3>
                            <p>{{$setting['feature']['desc6'] ?? ''}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="download-section" id="Download"> 
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-7 text-center wow zoomIn slow">
                        <img src="{{url('/public/storage/'.Config::get('siteglobal.images_dirs.SETTING'))}}{{'/'.$setting['down_link']['image'] ?? ''}}" alt="" class="img-download">
                    </div>
                    <div class="col-lg-5">
                        <h2>{{ $setting['down_link']['head'] ?? '' }}</h2>
                        <p>{{ $setting['down_link']['text'] ?? '' }}</p>
                        <div class="btn-div">
                            <a href="{{ $setting['down_link']['ps_link'] ?? '' }}" class="download-btn wow zoomIn slow">
                                <img src="{{url('/public/storage/'.Config::get('siteglobal.images_dirs.SETTING'))}}{{ '/'.$setting['down_link']['ps_icon'] ?? '' }}" class="hvr-icon">
                                <p>
                                    <span>GET IT ON</span>
                                    <span>Google Play</span>
                                </p>
                            </a>
                            <a href="{{ $setting['down_link']['as_link'] ?? '' }}" class="download-btn wow zoomIn slow" >
                                <img src="{{url('/public/storage/'.Config::get('siteglobal.images_dirs.SETTING'))}}{{ '/'.$setting['down_link']['as_icon'] ?? '' }}" class="hvr-icon">
                                <p>
                                    <span>Download on the</span>
                                    <span>App Store</span>
                                </p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>          
        </section>

        <section class="Testimonials-listing gray_bg" id="Testimonials">
            <div class="container">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="owl-carousel owl-theme homeslider testimonial">
                            @foreach($testimonial as $test_desc)
                            <div class="item wow fadeInRight">
                                <div class="testimonial_box">
                                    <div class="client_img_box">
                                        <img src="@if($test_desc->image){{url('/public/storage/'.Config::get('siteglobal.images_dirs.TESTIMONIAL'))}}{{'/'.$test_desc->image ?? ''}} @else {{url('/public/images/user_ico.png')}} @endif">
                                    </div>
                                    <div class="client_img_box_shadow"></div>
                                    <div class="feedback_sec">
                                        <p>{{ $test_desc->test_desc->description ?? '' }}</p>
                                        <h4 class="client_name"><img src="{{url('/public/frontend/images/ic_quote2.png')}}">
                                            {{ $test_desc->test_desc->testimonial_name ?? '' }}
                                        </h4>
                                    </div>
                                </div>          
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                </div>
            </div>
        </section>

        <section class="contact-section" id="Contact">
            <div class="container">
                <div class="section_title text-right">                  
                    <h2>{{'Contact Us'}}</h2>
                </div>
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="left_contact">
                            <a href="javascript::void(0)" class="contact_icon"><img src="{{url('/public/frontend/images/ic_map_pin.png')}}" class="wow jackInTheBox slow"><p>{{$setting['contact']['address'] ?? ''}}</p></a>
                            <a href="tel:9427346204" class="contact_icon"><img src="{{url('/public/frontend/images/ic_call.png')}}" class="wow jackInTheBox slow"><p>{{$setting['contact']['phone'] ?? ''}}</p></a>
                            <a href="mailto:digikoach@gmail.com" class="contact_icon"><img src="{{url('/public/frontend/images/ic_mail.png')}}" class="wow jackInTheBox slow"><p>{{$setting['contact']['email'] ?? ''}}</p></a>
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="right_contact">

                            <form name="contact-query-form" action="{{url('/contactQueryPost')}}" method="post">
                                {{ csrf_field() }}
                                <div class="contact-form">
                                    <div class="form-group">
                                        <label>Your Name</label>
                                        <input type="text" name="name" class="form-control wow zoomIn " placeholder="Enter name">
                                    </div>
                                
                                    <div class="form-group">
                                        <label>Your Email ID</label>
                                        <input type="email" name="email" class="form-control wow zoomIn " placeholder="Enter Email ID">
                                    </div>
                                
                                    <div class="form-group">
                                        <label>Message</label>
                                        <textarea name="message" class="form-control wow zoomIn" placeholder="Type your message"></textarea>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-center"><button type="submit" class="theme_btn wow zoomIn slow text-white border-0">Submit</button></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="footer-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-md-left">
                        <p>Copyright © {{date('Y')}} created by <a href="https://www.technource.com/" target="_blank" >Technource</a></p>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <ul>
                            {{-- <li><a href="#" class="hvr-underline-from-left">Terms & Condition</a></li>
                            <li><a href="#" class="hvr-underline-from-left">Privacy Policy</a></li> --}}
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <div class="your-cursor2"></div>
        <div class="follow2"></div>
    </div>
 </body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script src="{{url('/public/frontend/js/owl.carousel.js')}}"></script>
<script src="{{url('/public/frontend/js/custom.js')}}"></script>
<script src="{{ url('public/js/jquery.validate.js') }}"></script>
<script src="{{ url('public/js/dashboard/homepage.js') }}"></script>

</html>
