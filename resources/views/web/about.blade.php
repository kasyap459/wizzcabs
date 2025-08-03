@extends('web.layouts.app')
@section('styles')
<style> #more {display: none;}
    #more2 {display: none;}
    #more3 {display: none;}
    #more4 {display: none;}
    #more5 {display: none;}
    #more6 {display: none;}</style>
@endsection

@section('content')
<!-- Banner -->

<section class="inner-banner">
            <div class="container">
                <ul class="thm-breadcrumb">
                    <li><a href="{{url('/')}}">Home</a></li>
                    <li><span class="sep">.</span></li>
                    <li><span class="page-title">About Us</span></li>
                </ul><!-- /.thm-breadcrumb -->
                <h2>About Page</h2>
            </div><!-- /.container -->
        </section><!-- /.inner-banner -->
        <section class="about-style-two">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="content-block">
                            <div class="block-title">
                            <!-- <div><img src="{{asset('web/images/black_logo.png')}}" alt="" width="10%"></div> -->
                                <p>Few words about  {{ Setting::get('site_title','Unicotaxi') }}</p>
                                <h2>Welcome <br /> To {{ Setting::get('site_title','Unicotaxi') }}</h2> 
                            </div><!-- /.block-title text-center -->
                            <p>Depend on our company for reliable local and long-distance transportation. We offer low-cost travel to any location, as well as airport shuttle service to many nearby airports. Advanced reservations are welcomed and guaranteed, no matter the size of your group, so request a quote today for our quality transportation service.</p>
                            <a href="{{url('/book-taxi')}}" class="about-btn">Book a Taxi</a>
                        </div><!-- /.content-block -->
                    </div><!-- /.col-lg-6 -->
                    <div class="col-lg-6">
                        <div class="hvr-float-shadow">
                            <div class="image-block">
                                <img src="web/images/resources/about-1-12.jpg" alt="Awesome Image" />
                                <div class="bubble-block">
                                    <div class="inner-block">
                                        <p>Trusted by</p>
                                        <span class="counter">4880</span>
                                    </div><!-- /.inner-block -->
                                </div><!-- /.bubble-block -->
                            </div><!-- /.image-block -->
                        </div><!-- /.hvr-float-shadow -->
                    </div><!-- /.col-lg-6 -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </section><!-- /.about-style-two -->
        <hr class="style-one" />
        
        <section class="feature-style-one thm-black-bg">
            <img src="web/images/background/feature-bg-1-12.png" alt="Awesome Image" class="feature-bg" />
            <div class="container">
                <div class="block-title text-center">
                <!-- <div><img src="{{asset('asset/img/unico.png')}}" alt="" width="20%"></div> -->
                    <p> {{ Setting::get('site_title','Unicotaxi') }} benefit list</p>
                    <h2 class="light">Why choose us</h2>
                </div><!-- /.block-title text-center -->
                <div class="row">
                    <div class="col-lg-4">
                        <div class="single-feature-one">
                            <div class="icon-block">
                                <i class="conexi-icon-insurance"></i>
                            </div><!-- /.icon-block -->
                            <h3><a href="">Safety Guarantee</a></h3>
                            <p>At {{ Setting::get('site_title','Unicotaxi') }}, we take your safety <br> seriously.<span id="dots4">...</span><span id="more4"> Our dedicated team of trained drivers, advanced technology, and rigorous safety protocols ensure that every ride with us is a secure and worry-free experience. From real-time GPS tracking to thorough driver background checks, we go the extra mile to provide you with the peace of mind you  deserve. Trust {{ Setting::get('site_title','Unicotaxi') }} for a safe journey every time."</span></p>
                                <a onclick="myFunction4()" id="myBtn4" class="more-link">Read More</a>
                        </div><!-- /.single-feature-one -->
                    </div><!-- /.col-lg-4 -->
                    <div class="col-lg-4">
                        <div class="single-feature-one">
                            <div class="icon-block">
                                <i class="conexi-icon-seatbelt"></i>
                            </div><!-- /.icon-block -->
                            <h3><a href="">DBS Cleared Drivers</a></h3>
                            <p>Travel with Peace of Mind: DBS Cleared Drivers at Your Service!<span id="dots5">...</span><span id="more5">.At {{ Setting::get('site_title','Unicotaxi') }}, your safety is paramount. All our drivers undergo rigorous Disclosure and Barring Service (DBS) checks, ensuring that they have been thoroughly vetted for your security. Rest easy knowing that your ride is in the hands of trustworthy and cleared professionals. Choose {{ Setting::get('site_title','Unicotaxi') }} for a safe and reliable journey."</span></p>
                                <a onclick="myFunction5()" id="myBtn5" class="more-link">Read More</a>
                        </div><!-- /.single-feature-one -->
                    </div><!-- /.col-lg-4 -->
                    <div class="col-lg-4">
                        <div class="single-feature-one">
                            <div class="icon-block">
                                <i class="conexi-icon-consent"></i>
                            </div><!-- /.icon-block -->
                            <h3><a href="">Free Quotation</a></h3>
                            <p>"Unlock Affordable Travel: Get Your Free Taxi Quotation Today!<span id="dots6">...</span><span id="more6">.Experience transparent pricing with {{ Setting::get('site_title','Unicotaxi') }}. Our commitment to fair and competitive rates means you'll always know what to expect. Simply provide your trip details, and we'll provide you with a free, no-obligation quotation. No hidden fees, no surprises – just straightforward pricing for your convenience. Start your journey with confidence, choose {{ Setting::get('site_title','Unicotaxi') }} for a hassle-free ride."</span></p>
                                <a onclick="myFunction6()" id="myBtn6" class="more-link">Read More</a>
                        </div><!-- /.single-feature-one -->
                    </div><!-- /.col-lg-4 -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </section><!-- /.feature-style-one -->
        <section class="history-style-one">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="history-carousel-block ">
                            <ul class="slider  history-slider-one">
                                <li class="slide-item">
                                    <div class="image-block">
                                        <img src="{{asset('web/images/resources/history.png')}}" alt="history image">
                                    </div>
                                </li><!-- /.image-block 
                                <li class="slide-item">
                                    <div class="image-block">
                                        <img src="web/images/resources/history-1-12.jpg" alt="history image">
                                    </div> /.image-block 
                                </li>
                                <li class="slide-item">
                                    <div class="image-block">
                                        <img src="web/images/resources/history-1-12.jpg" alt="history image">
                                    </div> /.image-block 
                                </li>-->
                            </ul>
                           <!-- <div class="history-one-slider-btn">
                                <span class="carousel-btn left-btn"><i class="conexi-icon-left"></i></span>
                                <span class="carousel-btn right-btn"><i class="conexi-icon-right"></i></span>
                            </div> /.carousel-btn-block banner-carousel-btn -->
                        </div><!-- /.history-carousel-block -->
                    </div><!-- /.col-lg-6 -->
                    <div class="col-lg-6">
                        <div class="content-block">
                            <div class="block-title">
                            <!-- <div><img src="{{asset('web/images/black_logo.png')}}" alt="" width="10%"></div> -->
                                <p> {{ Setting::get('site_title','Unicotaxi') }} history</p>
                                <h2>How we reached <br> to this level</h2>
                            </div><!-- /.block-title -->
                            <div class="history-content history-content-one-pager">
                                <a class="pager-item active" data-slide-index="1">
                                    <h3>2007</h3>
                                    <p>"Climbing Heights: Our Taxi Industry Journey Unveiled.{{ Setting::get('site_title','Unicotaxi') }} didn't just arrive; we blazed a trail. Years of unwavering commitment, innovative thinking, and customer-centric focus have propelled us to the pinnacle of the taxi industry. By embracing cutting-edge technology, prioritizing passenger safety, and fostering a dedicated team, we've earned our place as a leader. From day one, our aim was clear: to redefine travel convenience and set new standards. Trust the journey, trust {{ Setting::get('site_title','Unicotaxi') }} – your partner in elevating transportation experiences."</p>
                                </a>
                                <!-- <a href="#" class="pager-item" data-slide-index="2">
                                    <h3>2009</h3>
                                    <p>There are many variations of passages of lorem ipsum available but the majority have suffered alteration in some form by injected humour or random words which don't look even slightly believable. If you are going to use a passage of lorem ipsum you need to be sure there isn't anything embarrassing.</p>
                                </a>
                                <a href="#" class="pager-item" data-slide-index="3">
                                    <h3>2019</h3>
                                    <p>There are many variations of passages of lorem ipsum available but the majority have suffered alteration in some form by injected humour or random words which don't look even slightly believable. If you are going to use a passage of lorem ipsum you need to be sure there isn't anything embarrassing.</p>
                                </a> -->
                            </div><!-- /.testimonials-one-pager -->
                        </div><!-- /.content-block -->
                    </div><!-- /.col-lg-6 -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </section><!-- /.history-style-one -->
        <section class="offer-style-one">
            <div class="container">
                <div class="block-title text-center">
                <div><img src="{{asset('asset/img/unico.png')}}" alt="" width="20%"></div>
                    <p>Check out our benefits</p>
                    <h2>What we’re offering</h2>
                </div><!-- /.block-title -->
                <div class="row">
                    <div class="col-lg-4">
                        <div class="single-offer-one hvr-float-shadow">
                            <div class="image-block">
                                <a href="#"><i class="fa fa-link"></i></a>
                                <img src="web/images/resources/offer-1-1.jpg" alt="Awesome Image" />
                            </div><!-- /.image-block -->
                            <div class="text-block">
                            <h3><a href="#">Tap the app, get a ride</a></h3>
                                <p>{{ Setting::get('site_title','Unicotaxi') }} is the smartest way to get around. One tap and a car comes directly to you<span id="dots">...</span><span id="more">. Your driver knows exactly where to go. And you can pay with either cash or card. Advanced reservations are welcomed and guaranteed, no matter the size of your group, so request a quote today for our quality transportation service.</span></p>
                                <a onclick="myFunction()" id="myBtn" class="more-link">Read More</a>
                            </div><!-- /.text-block -->
                        </div><!-- /.single-offer-one -->
                    </div><!-- /.col-lg-4 -->
                    <div class="col-lg-4">
                        <div class="single-offer-one hvr-float-shadow">
                            <div class="image-block">
                                <a href="#"><i class="fa fa-link"></i></a>
                                <img src="web/images/resources/offer-1-2.jpg" alt="Awesome Image" />
                            </div><!-- /.image-block -->
                            <div class="text-block">
                            <h3><a href="#">Ready anywhere, anytime</a></h3>
                                <p>Daily commute. Errand across town. Early morning flight. Lat<span id="dots2">...</span><span id="more2">e night drinks. Wherever you’re headed, count on {{ Setting::get('site_title','Unicotaxi') }} for a ride—no reservations needed. We charge low rates and drive clean and well-maintained vehicles, all to ensure a pleasant and affordable ride. When you're in need of prompt and dependable taxi service, count on us!</span></p>
                                <a onclick="myFunction2()" id="myBtn2" class="more-link">Read More</a>
                            </div><!-- /.text-block -->
                        </div><!-- /.single-offer-one -->
                    </div><!-- /.col-lg-4 -->
                    <div class="col-lg-4">
                        <div class="single-offer-one hvr-float-shadow">
                            <div class="image-block">
                                <a href="#"><i class="fa fa-link"></i></a>
                                <img src="web/images/resources/offer-1-3.jpg" alt="Awesome Image" />
                            </div><!-- /.image-block -->
                            <div class="text-block">
                            <h3><a href="#">Easy to use</a></h3>
                                <p>Both riders and vehicles are geolocalized, an easy way to find each othe<span id="dots3">...</span><span id="more3">r. For a perfect transparency and service optimization, riders can rate the driver.</span></p>
                                <a onclick="myFunction3()" id="myBtn3" class="more-link">Read More</a>
                            </div><!-- /.text-block -->
                        </div><!-- /.single-offer-one -->
                    </div><!-- /.col-lg-4 -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </section><!-- /.offer-style-one -->
        <section class="cta-style-one text-center">
            <div class="container">
                <p>Call 24 hour service available</p>
                <h3>Call now and book <br> our taxi for your next ride</h3>
                <a href="{{url('/book-taxi')}}" class="cta-btn">Book a Ride</a>
            </div><!-- /.container -->
        </section><!-- /.cta-style-one -->
@endsection
@section('scripts')
<script>
function myFunction() {
  var dots = document.getElementById("dots");
  var moreText = document.getElementById("more");
  var btnText = document.getElementById("myBtn");

  if (dots.style.display === "none") {
    dots.style.display = "inline";
    btnText.innerHTML = "Read more"; 
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "Read less"; 
    moreText.style.display = "inline";
  }
}
</script>
<script>
function myFunction2() {
  var dots = document.getElementById("dots2");
  var moreText = document.getElementById("more2");
  var btnText = document.getElementById("myBtn2");

  if (dots.style.display === "none") {
    dots.style.display = "inline";
    btnText.innerHTML = "Read more"; 
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "Read less"; 
    moreText.style.display = "inline";
  }
}
</script>
<script>
function myFunction3() {
  var dots = document.getElementById("dots3");
  var moreText = document.getElementById("more3");
  var btnText = document.getElementById("myBtn3");

  if (dots.style.display === "none") {
    dots.style.display = "inline";
    btnText.innerHTML = "Read more"; 
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "Read less"; 
    moreText.style.display = "inline";
  }
}
</script>

<script>
function myFunction4() {
  var dots = document.getElementById("dots4");
  var moreText = document.getElementById("more4");
  var btnText = document.getElementById("myBtn4");

  if (dots.style.display === "none") {
    dots.style.display = "inline";
    btnText.innerHTML = "Read more"; 
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "Read less"; 
    moreText.style.display = "inline";
  }
}
</script>

<script>
function myFunction5() {
  var dots = document.getElementById("dots5");
  var moreText = document.getElementById("more5");
  var btnText = document.getElementById("myBtn5");

  if (dots.style.display === "none") {
    dots.style.display = "inline";
    btnText.innerHTML = "Read more"; 
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "Read less"; 
    moreText.style.display = "inline";
  }
}
</script>

<script>
function myFunction6() {
  var dots = document.getElementById("dots6");
  var moreText = document.getElementById("more6");
  var btnText = document.getElementById("myBtn6");

  if (dots.style.display === "none") {
    dots.style.display = "inline";
    btnText.innerHTML = "Read more"; 
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "Read less"; 
    moreText.style.display = "inline";
  }
}
</script>

@endsection
