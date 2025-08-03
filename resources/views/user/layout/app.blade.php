<!DOCTYPE html>
<html lang="en">
<head>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-Z5PBGE2KH7"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-Z5PBGE2KH7');
</script>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ Setting::get('site_title','Unicotaxi') }}</title>

    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" type="image/png" href="{{ Setting::get('site_icon') }}"/>

    <!-- <link href="{{asset('asset/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('asset/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('asset/css/style.css')}}" rel="stylesheet"> -->

    <!-- External CSS -->
    <link rel="stylesheet" href="{{asset('asset/theme/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/theme/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/theme/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('asset/theme/css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset('asset/theme/css/owl.carousel.css')}}">
    <link rel="stylesheet" href="{{asset('asset/theme/css/owl.transitions.css')}}">
    <link rel="stylesheet" href="{{asset('asset/theme/css/plyr.css')}}">
    <link rel="stylesheet" href="{{asset('asset/theme/css/swiper.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/theme/css/slick.css')}}">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{asset('asset/theme/css/primary.css')}}">
    <link rel="stylesheet" href="{{asset('asset/theme/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('asset/theme/css/preloader.css')}}">
    <link rel="stylesheet" href="{{asset('asset/theme/css/responsive.css')}}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans%7CLato:400,600,900" rel="stylesheet">
    @yield('styles')
	<style type="text/css">
        body{
            overflow-x: hidden !important;
        }
		footer .cps-footer-widget-area .cps-widget .cps-socials a {
    			color: unset;
  		}
		footer .cps-footer-widget-area .cps-footer-logo {
    			padding-top: 2%;
    			padding-right: 3%;
		}
	</style>

</head>
<body>

    <!-- Header -->
    <nav class="navbar navbar-default style-11 affix">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{url('/')}}"><img src="{{asset('asset/img/unico.png')}}" alt="{{ Setting::get('site_title','Unicotaxi') }}" style="height: 100px;"></a>
                  <!-- <p style="
    padding-left: 133px;
    padding-top: 38px;
    font-size: 1.9rem;
    color: #262626;
    line-height: 1.857143;
">Non-Profit Car Pools</p> -->
            </div>

            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{url('/')}}">Home</a></li>
                    <!--<li><a href="{{url('/about')}}">About</a></li>
                    <li><a href="{{url('/contact')}}">Contact</a></li>-->
                    <li class="login-item"><a href="{{url('/login')}}">Ride Now</a></li>
                    <li class="signup-item"><a href="{{url('/provider/login')}}">Become a Driver</a></li>
                    <!-- <li class="signup-item"><a href="{{url('/corporate/login')}}">Corporate Login</a></li> -->
                </ul>
            </div>
        </div>
    </nav>

@yield('content')

    <!--<footer class="style-5">
        <div class="cps-footer-upper">
            <div class="container">
                <div class="cps-footer-widget-area">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="cps-widget about-widget">
                                <a class="cps-footer-logo" href="{{url('/')}}">
                                    <img src="{{asset('asset/img/unico.png')}}" alt="..." style="width: 142px;">
                                </a>
                                <p>{{ Setting::get('site_title','Unicotaxi') }} comprises superior and optimal in-app features that represent the relevance of taxi-booking mobile app.</p>
                                <div class="cps-socials">
                                    <a href="https://www.facebook.com/Elite-Taxi-111413946861566/" target="_blank"><i class="fa fa-facebook"></i></a>
                                    <a href="#"><i class="fa fa-twitter"></i></a>
                                    <a href="#"><i class="fa fa-google-plus"></i></a>
                                    <a href="#"><i class="fa fa-github"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="cps-widget custom-menu-widget">
                                <h4 class="cps-widget-title">Menu</h4>
                                <ul class="widget-menu">
                                    <li><a href="{{url('/')}}">Home</a></li>
                                    <li><a href="{{url('about')}}">About us</a></li>
                                    <li><a href="#">Ride Now</a></li>
                                    <li><a href="#">Become a Driver</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="cps-widget custom-menu-widget">
                                <h4 class="cps-widget-title">Useful Links</h4>
                                <ul class="widget-menu">
                                    <li><a href="#">Privacy Policy</a></li>
                                    <li><a href="#">Terms &amp; Conditions</a></li>
                                    <li><a href="{{url('contact')}}">Contact us</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cps-footer-lower">
            <div class="container">
                <div class="row">
                    <div class="xs-text-center" style="text-align: center;">
                        <p class="copyright">{{ Setting::get('site_copyright', '&copy; '.date('Y').' Unicotaxi') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>-->

    <!-- Script -->
    <script src="{{asset('asset/theme/js/jquery.min.js')}}"></script>
    <script src="{{asset('asset/theme/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('asset/theme/js/jquery.nav.js')}}"></script>
    <script src="{{asset('asset/theme/js/owl.carousel.js')}}"></script>
    <script src="{{asset('asset/theme/js/visible.js')}}"></script>
    
    <script src="{{asset('asset/theme/js/jquery.countTo.js')}}"></script>
    <script src="{{asset('asset/theme/js/imagesloaded.pkgd.min.js')}}"></script>
    <script src="{{asset('asset/theme/js/isotope.pkgd.min.js')}}"></script>
    <script src="{{asset('asset/theme/js/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('asset/theme/js/swiper.min.js')}}"></script>
    <script src="{{asset('asset/theme/js/slick.min.js')}}"></script>
    <script src="{{asset('asset/theme/js/custom.js')}}"></script>
    @yield('scripts')
</body>
</html>
