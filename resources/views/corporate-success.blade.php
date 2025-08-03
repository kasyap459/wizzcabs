<!DOCTYPE html>
<html lang="en">
<head>
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
</head>
<body>

    <!-- Header -->
    <nav class="navbar navbar-default style-11 affix">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{url('/')}}"><img src="{{Setting::get('site_logo')}}" alt="{{ Setting::get('site_title','Unicotaxi') }}" style="height: 65px;"></a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
            </div>
        </div>
    </nav>

    <div class="page-header">
        <div class="container">
            <h2 class="page-title"></h2>
        </div>
    </div>
    <!-- Page Header End -->
<div class="cps-main-wrap">
<!-- About us -->
        <div class="cps-section cps-section-padding" id="about">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div>
                            <h3 class="text-center">Account Created Successfully</h3>
                            <p class="text-center">Please <a href="{{url('/login')}}">login</a> to your account</p>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        <!-- About us end -->
        <div class="cps-cta cps-gray-bg style-4">
            <div class="container text-center">
                <h3 class="cps-cta-title">Driver &amp; Passenger App on Android and IOS</h3>
                <p class="cps-cta-text">Your App for Taxis, Cars and Beyond...</p>
                <div class="cps-cta-download">
                    <a href="{{Setting::get('store_link_android','#')}}" target="_blank"><img src="{{asset('asset/theme/images/googleplay.png')}}" alt="Download from Google Play"></a>
                    <a href="{{Setting::get('store_link_ios','#')}}" target="_blank"><img src="{{asset('asset/theme/images/appstore.png')}}" alt="Download from Play Store"></a>
                </div>
            </div>
        </div>
</div>
<footer class="style-5">
        <div class="cps-footer-lower">
            <div class="container">
                <div class="row">
                    <div class="xs-text-center" style="text-align: center;">
                        <p class="copyright">{{ Setting::get('site_copyright', '&copy; '.date('Y').' Unicotaxi') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

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
</body>
</html>