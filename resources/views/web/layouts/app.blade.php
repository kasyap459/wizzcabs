<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ Setting::get('site_title','DPKAR') }}</title>

    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" type="image/png" href="{{ Setting::get('site_icon') }}"/>

    <!-- <link href="{{asset('asset/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('asset/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('asset/css/style.css')}}" rel="stylesheet"> -->

    <!-- External CSS -->
    <!-- <link rel="stylesheet" href="{{asset('asset/theme/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/theme/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/theme/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('asset/theme/css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset('asset/theme/css/owl.carousel.css')}}">
    <link rel="stylesheet" href="{{asset('asset/theme/css/owl.transitions.css')}}">
    <link rel="stylesheet" href="{{asset('asset/theme/css/plyr.css')}}">
    <link rel="stylesheet" href="{{asset('asset/theme/css/swiper.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/theme/css/slick.css')}}"> -->

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{asset('web/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('web/css/responsive.css')}}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans%7CLato:400,600,900" rel="stylesheet">
    @yield('styles')
	<style type="text/css">
		footer .cps-footer-widget-area .cps-widget .cps-socials a {
    			color: unset;
  		}
		footer .cps-footer-widget-area .cps-footer-logo {
    			padding-top: 2%;
    			padding-right: 3%;
		}
        .fa{
            font-size: 18px;
        }
        .site-header.header-one .header-navigation .right-side-box .contact-btn-block .icon-block{
            border: none !important;
            border-bottom-left-radius: 50% !important;
            border-top-left-radius: 50% !important;
        }
        @media only screen and (max-width: 600px){
            .site-header.header-one .header-navigation .right-side-box .contact-btn-block .icon-block {
                line-height: 40px !important;
            }
            .site-header.header-one .header-navigation .right-side-box .text-block div img {
                height: 37px !important;
                width: 37px !important;
            }
        }
        @media only screen and (max-width: 992px){
            .site-header.header-one .header-navigation .right-side-box .contact-btn-block .icon-block {
                line-height: 40px !important;
            }
            .site-header.header-one .header-navigation .right-side-box .text-block div img {
                height: 36px !important;
                width: 36px !important;
            }
        }
        /* @media only screen and (max-width: 600px) {
  .play {
     /* padding: 0px !important; 
     width: 48%;
     transform: translateY(-10px);
     padding-left: 0px;
     padding-right:0px;
  }
} */

.dropdown {
  display: inline-block;
  position: relative;
}

button{
  border:none;
  border-radius:5px;
  color: #fff;
  background-color: transparent;
  font-size:18px;
  cursor:pointer;
}

button:hover{
  background-color:transparent;
}

.dropdown-options {
  display: none;
  position: absolute;
  overflow: auto;
  background-color: transparent;
  border-radius:5px;
  /* box-shadow: 0px 10px 10px 0px rgba(0,0,0,0.4); */
}

.dropdown:hover .dropdown-options {
  display: block;
}

.dropdown-options a {
  display: block;
  color: #fff;
  text-decoration: none;
  margin-left: 5px;
}

.dropdown-options a:hover {
  color: #fff;
  background-color: transparent;
}
	</style>

</head>
<body>

    <!-- Header -->
    <div class="page-wrapper">
        <header class="site-header header-one">
            <div class="top-bar">
                <div class="container">
                    <!-- <div class="left-block">
                        <a href="#"><i class="fa fa-user-circle"></i> Customer Sign In</a>
                        <a href="#"><i class="fa fa-envelope"></i> needhelp@Unicotaxi.com</a>
                    </div> -->

                    <div class="social-block">
                        <a href="https://twitter.com/prontotaxi"><i class="fa fa-twitter"  ></i></a>
                        <a href="https://www.facebook.com/prontotaxi/"><i class="fa fa-facebook-f"></i></a>
                        <a href="https://www.youtube.com/@prontotaxi"><i class="fa fa-youtube-play"></i></a>
                        <a href="https://www.instagram.com/prontotaxi/"><i class="fa fa-instagram"></i></a>
                        <a href="https://www.threads.net/@prontotaxi"><img src="{{asset('web/images/icon1.png')}}" alt="threads_icon" width="17px" height="17px" style="transform: translateY(-1.9px);"></a>
                        <a href="https://www.tiktok.com/@prontotaxi"><img src="{{asset('web/images/icon2.png')}}" alt="toktok_icon" width="15px" height="15px" style="transform: translateY(-1px);margin-left: -3px;"></a>
                    </div><!-- /.social-block -->

                    <div class="web/logo-block">
                        <!-- <a href="{{url('/')}}"><img src="{{asset('asset/img/unico.png')}}" width="250px" height="100px" alt="{{ Setting::get('site_title','Pronto Taxi') }}" /></a> -->
                    </div><!-- /.logo-block -->
                   

                    <!-- <div class="dropdown">
                        <button>EN</button>
                          <div class="dropdown-options">
                            <a href="{{url('/es')}}">ES</a>
                          </div>
                        </div> -->
                </div><!-- /.container -->
            </div><!-- /.top-bar -->
            <nav class="navbar navbar-expand-lg navbar-light header-navigation stricky" id="navbar">
                <div class="container clearfix">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="logo-box clearfix">
                        <button class="menu-toggler" data-target="#main-nav-bar">
                            <span class="fa fa-bars"></span>
                        </button>
                    </div><!-- /.logo-box -->
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="main-navigation" id="main-nav-bar">
                        <ul class="navigation-box">
                            <li>
                                    <a href="{{url('/')}}"><img src="{{asset('asset/img/unico.png')}}" width="110px" height="100px" alt="{{ Setting::get('site_title','Dpkar') }}" style="padding: 15px;"/></a>
                            </li>
                            <li class="navlink {{ (request()->is('/')) ? 'current' : '' }}">
                                <a href="{{url('/')}}">Home</a>
                            </li>
                            <li class="navlink {{ (request()->is('about')) ? 'current' : '' }}">
                                <a href="{{url('/about')}}">About</a></li> 
                            <!-- <li>
                                <a href="#">Pages</a>
                                <ul class="sub-menu">
                                    <li><a href="driver.html">Our Drivers</a></li>
                                    <li><a href="taxi.html">Our Taxi</a></li>
                                    <li><a href="single-taxi.html">Taxi Details</a></li>
                                </ul> /.sub-menu 
                            </li> -->
                            <li class="navlink {{ (request()->is('book-taxi')) ? 'current' : '' }} "><a href="{{url('/login')}}" >Book A Ride</a></li>
                            <li class="navlink {{ (request()->is('provider/login')) ? 'current' : '' }}"><a href="{{url('/provider/login')}}">Become A Driver</a></li>
                            <!-- <li>
                                <a href="blog.html">Blog</a>
                                <ul class="sub-menu">
                                    <li><a href="blog.html">Blog Grid</a></li>
                                    <li><a href="blog-details.html">Blog Details</a></li>
                                </ul> /.sub-menu 
                            </li> -->
                            <!-- <li class="navlink {{ (request()->is('corporate/login')) ? 'current' : '' }}"><a href="{{url('/corporate/login')}}" target="_blank">Corporate Login</a></li> -->
                        </ul>
                    </div><!-- /.navbar-collapse -->

                    <div class="right-side-box">
                        <a href="callto:1234567890" class="contact-btn-block">
                            <span class="icon-block">
                                <i class="conexi-icon-phone-call"></i>
                            </span>
                            <span class="text-block">
                            1234567890
                                <span class="tag-line">Phone line</span>
                            </span>
                        </a>
                    </div><!-- /.right-side-box -->
                </div>
                <!-- /.container -->
            </nav>
        </header><!-- /.site-header header-one -->

@yield('content')

    <footer class="site-footer">
        <img src="web/images/background/footer-bg-1-1.png" class="footer-bg" alt="Awesome Image"/>
        <div class="upper-footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="footer-widget about-widget">
                            <div class="widget-title">
                                <h3>About</h3>
                            </div><!-- /.widget-title -->
                            <p>{{ Setting::get('site_title','Unicotaxi') }} comprises superior and optimal in-app features that represent the relevance of taxi-booking mobile app.</p>
                            <div class="social-block">
                                <a href="https://twitter.com/prontotaxi"><i class="fa fa-twitter"  ></i></a>
                                <a href="https://www.facebook.com/prontotaxi/"><i class="fa fa-facebook-f"  ></i></a>
                                <a href="https://www.youtube.com/@prontotaxi"><i class="fa fa-youtube-play"  ></i></a>
                                <a href="https://www.instagram.com/prontotaxi/"><i class="fa fa-instagram"  ></i></a>
                                <a href="https://www.tiktok.com/@prontotaxi"><i><img src="{{asset('asset/img/tiktok.png')}}" alt="tiktok" style="width: 40px;height: 40px;transform: translateX(-15px);"></i></a>
                                <a href="https://www.threads.net/@prontotaxi"><i><img src="{{asset('asset/img/thread.png')}}" alt="tiktok" style="width: 25px;height: 25px;transform: translateX(-40px);"></i></a>
                            </div><!-- /.social-block -->
                        </div><!-- /.footer-widget about-widget -->
                    </div><!-- /.col-lg-3 -->
                    <div class="col-lg-3" >
                        <div class="footer-widget">
                            <div class="widget-title">
                                <h3>Links</h3>
                            </div><!-- /.widget-title -->
                            <ul class="link-lists">
                                <li><a href="{{url('/')}}">Home</a></li>
                                <li><a href="{{url('/about')}}">About Us</a></li>
                                <li><a href="{{url('/book-taxi')}}">Book a Ride</a></li>
                                <li><a href="{{url('/provider/login')}}">Become A Driver</a></li>
                                <li><a href="{{url('/contact')}}">Contact</a></li>
                            </ul>
                        </div><!-- /.footer-widget -->
                    </div><!-- /.col-lg-2 -->
                    <div class="col-lg-3" >
                        <div class="footer-widget">
                            <div class="widget-title">
                                <h3>Useful Links</h3>
                            </div><!-- /.widget-title -->
                            <ul class="link-lists">
                                <li><a href="{{url('/privacy')}}">Privacy Policy</a></li>
                                <li><a href="{{url('/terms-conditions')}}">Terms of Use</a></li>
                                <!-- <li><a href="#">Refund Policy</a></li> -->
                            </ul>
                        </div><!-- /.footer-widget -->
                    </div><!-- /.col-lg-2 -->

                    <!-- <div class="col-lg-4">
                        <div class="footer-widget">
                            <div class="widget-title">
                                <h3>Contact</h3>
                            </div>
                            <p>86 Road Broklyn Street, 600 <br> New York, USA</p>
                            <ul class="contact-infos">
                                <li><i class="fa fa-envelope"></i> needhelp@Unicotaxi.com</li>
                                <li><i class="fa fa-phone-square"></i> 666 888 000</li>
                            </ul>
                        </div>
                    </div> -->
                    <!-- <div class="col-lg-4">
                        <div class="footer-widget">
                            <div class="widget-title">
                                <h3>Newsletter</h3>
                            </div>
                            <p>Sign up now for our mailing list to get all latest news <br> and updates from Unicotaxi company.</p>
                            <form action="#" class="subscribe-form">
                                <input type="text" name="email" placeholder="Enter your email">
                                <button type="submit">Go</button>
                            </form>
                        </div>
                    </div> -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </div><!-- /.upper-footer -->
        <div class="bottom-footer">
            <div class="container">
                <div class="inner-container">
                    <div class="left-block">
                        <!-- <a href="{{url('/')}}" class="footer-logo"><img src="{{asset('web/images/ezgif.com-gif-maker (1).gif')}}" width="150px" height="65px" alt="Awesome Image" /></a> -->
                        <span>&copy; 2023 <a href="{{url('/')}}">{{ Setting::get('site_title','Unicotaxi') }}</a></span>
                    </div><!-- /.left-block -->
                    <div class="right-block">
                        <ul class="link-lists">
                            <li><a href="{{url('/terms-conditions')}}">Terms of Use</a></li>
                            <li><a href="{{url('/privacy')}}">Privacy Policy</a></li>
                            <!-- <li><a href="#">Refund Policy</a></li> -->
                        </ul>
                    </div><!-- /.right-block -->
                </div><!-- /.inner-container -->
            </div><!-- /.container --> 
        </div><!-- /.bottom-footer -->
    </footer><!-- /.site-footer -->
</div><!-- /.page-wrapper -->

<a href="#" data-target="html" class="scroll-to-target scroll-to-top"><i class="fa fa-angle-up"></i></a>

<!-- /.scroll-to-top -->


<script src="{{asset('web/js/jquery.js')}}"></script>

<script src="{{asset('web/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('web/js/owl.carousel.min.js')}}"></script>
<script src="{{asset('web/js/bootstrap-select.min.js')}}"></script>
<script src="{{asset('web/js/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('web/js/waypoints.min.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBk25E4mNfVIEt3tNl3K1HwNZVruVoFBlA"></script>
<script src="{{asset('web/js/gmaps.js')}}"></script>
<script src="{{asset('web/js/jquery.counterup.min.js')}}"></script>
<script src="{{asset('web/js/jquery.bxslider.min.js')}}"></script>
<script src="{{asset('web/js/theme.js')}}"></script>
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
    @yield('scripts')
</body>
</html>