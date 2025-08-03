@extends('user.layout.app')

@section('content')
<!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h2 class="page-title"></h2>
        </div>
    </div>
    <!-- Page Header End -->
<div class="cps-main-wrap">
<!-- About us -->
        <div class="cps-section cps-section-padding" id="cps-contact">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2 col-xs-12">
                        <div class="cps-section-header text-center">
                            <h3 class="cps-section-title">Get in Touch</h3>
                            <p class="cps-section-text">Contacting us to make a reservation has never been easier. At {{ Setting::get('site_title','Unicotaxi') }}, you can now make reservation online or by phone. </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <form id="contactForm" class="cps-contact-form style-2" action="{{ url('/contactprocess') }}" method="post">
                            {{csrf_field()}}
                            
                                    <input id="name" type="text" name="name" placeholder="Your Name">
                                    <input id="email" type="email" name="email" placeholder="Email">
                                    <input id="phone" type="tel" name="phone" placeholder="Phone">
                               
                                    <textarea id="content" name="content" placeholder="Your Message Here"></textarea>
                                    <button type="submit">Send</button>
                            @if(Session::has('flash_success'))
                            <p class="input-success">{{ Session::get('flash_success') }}</p>
                            @endif
                            @if(Session::has('flash_error'))
                            <p class="input-error">{{ Session::get('flash_error') }}</p>
                            @endif
                        </form>
                    </div>
                    <div class="col-md-6">
                        <h5>Address</h5>
                        <p>United States</p>
                        <h5>Phone</h5>
                        <p>+1 123 456 789 </p>
                        <h5>Email</h5>
                        <p>info@crowncabs.com</p>
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
@endsection

@section('scripts')
  <script src=
"https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">
    </script>

     <script>
$("#contactForm").submit(function(e){
    return false;
});

 </script>
@endsection