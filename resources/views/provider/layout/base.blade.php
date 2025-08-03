
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>{{ Setting::get('site_title','Unicotaxi') }}</title>
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link href='https://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="{{asset('asset/driverpanel/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('asset/driverpanel/css/slick-theme.css')}}">
        <link rel="stylesheet" href="{{asset('asset/driverpanel/css/slick.css')}}">
        <link rel="stylesheet" href="{{asset('asset/driverpanel/css/offside.css')}}">
        <link rel="stylesheet" href="{{asset('asset/driverpanel/css/demo.css')}}">
        <link rel="stylesheet" href="{{asset('asset/driverpanel/css/custom.css')}}">
        <link href="{{asset('asset/driverpanel/css/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
        <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>  
        </script>
        @yield('styles')
		<style>
		li > a {
    			text-decoration: none;
    			color: #fff !important;
		}
		nav {
    			color: #fff !important;
		}
		.offside {
    			background-color: #e91e63;
		}

	</style>
    </head>

    <body style="background-image: url('{{asset('asset/customer/images/bg-01.jpg')}}');">
        <div class="wrapper">
            <!-- Off-canvas Elements -->
            <nav id="menu-1" class="offside">
                <a href="#" class="icon icon--cross menu-btn-1--close h--right">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <ul>
                    <li><a href="{{ route('provider.index') }}">@lang('user.dashboard')</a></li>
                    <li><a href="{{ route('provider.earnings') }}">My Trips</a></li>
                    <li><a href="{{ route('provider.upcoming') }}">@lang('driver.upcoming')</a></li>
                    <li><a href="{{ route('provider.location.index') }}">@lang('driver.profile.update_location')</a></li>
                    <li><a href="{{ route('provider.documents') }}">@lang('driver.profile.manage_documents')</a></li>
                    <li><a href="{{url('/provider/profile')}}">@lang('driver.my_profile')</a></li>
                    <li><a  href="{{ route('provider.change.password')}}">@lang('driver.change_password')</a></li>
                    <li><a href="{{ url('/provider/logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">@lang('driver.logout')</a></li>
                            <form id="logout-form" action="{{ url('/provider/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                </ul>
            </nav>

            <!-- Site Overlay -->
            <div class="site-overlay"></div>

            <!-- Your Content -->
            <div id="container">

                <header>
                    <div class="row">
                    <div class="col-md-4">
                        <a href="#" class="icon icon--hamburger menu-btn-1 h--left">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <div class="logo">
                            <img src="{{asset('asset/img/unico.png')}}" alt="" style="width: 140px">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dropdown dropleft">
                          <div class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="profile_header" id="profile_image_preview" src="{{ Auth::guard('provider')->user()->avatar ? asset(Auth::guard('provider')->user()->avatar) : asset('main/avatar.png') }}" alt="your image"/>
                          </div>
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{url('/provider/profile')}}">@lang('user.profile.profile')</a>
                            <a class="dropdown-item" href="{{ route('provider.change.password')}}">@lang('driver.change_password')</a>
                            <a class="dropdown-item" href="{{ url('/provider/logout') }}"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">@lang('driver.logout')</a>
                                <form id="logout-form" action="{{ url('/provider/logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>    
                          </div>
                        </div>
                    </div>
                    </div>
                </header>

                

                @yield('content')

            </div>
            <script src="{{asset('asset/driverpanel/js/jquery.min.js')}}"></script>
            <script src="{{asset('asset/driverpanel/js/bootstrap.min.js')}}"></script>   
            <script type="text/javascript" src="{{asset('asset/driverpanel/js/slick.min.js')}}"></script>
            <script src="{{asset('asset/driverpanel/js/offside.js')}}"></script>

            <script>
                var offsideMenu1 = offside( '#menu-1', {

                    slidingElementsSelector: '#container, #results',
                    debug: true, 
                    buttonsSelector: '.menu-btn-1, .menu-btn-1--close',
                    slidingSide: 'left',
                    beforeOpen: function(){},
                    beforeClose: function(){},
                });

                var overlay = document.querySelector('.site-overlay')
                    .addEventListener( 'click', window.offside.factory.closeOpenOffside );

                console.log(offsideMenu1);
            </script>
            @yield('scripts')
        </div>
    </body>
</html>