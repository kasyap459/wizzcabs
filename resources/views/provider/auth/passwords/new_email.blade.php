@extends('web.layouts.app')

@section('content')
<!-- Banner -->
<section class="inner-banner">
            <div class="container">
                <ul class="thm-breadcrumb">
                    <li><a href="{{url('/')}}">Home</a></li>
                    <li><span class="sep">.</span></li>
                    <li><span class="page-title">Reset Password</span></li>
                </ul><!-- /.thm-breadcrumb -->
                <h2>Reset Password</h2>
            </div><!-- /.container -->
        </section><!-- /.inner-banner -->
        <section class="about-style-three clearfix">
            <div class="left-block">
                <div class="content-block">
                    <div class="image-block">
                        <img src="{{asset('web/images/resources/book-1-1.jpg')}}" alt="Awesome Image"/>
                    </div><!-- /.image-block -->
                    <div class="block-title">
                        <div class="dot-line"></div><!-- /.dot-line -->
                        <p>We’re the best in your town</p>
                        <h2>Welcome to the <br> most trusted <br> company</h2>
                    </div><!-- /.block-title -->
                    <p>There are many variations of passages of lorem ipsum available but the majority have suffered alteration in some form by injected humor or random word which don't look even slightly believable you are going to use a passage.</p>
                    <hr class="style-one" />
                    <div class="tag-line">
                        <span>Safe .</span>
                        <span>Fast .</span>
                        <span>Quick .</span>
                    </div><!-- /.tag-line -->
                </div><!-- /.content-block -->
            </div><!-- /.left-block -->
            <div class="right-block">
                <div class="right-upper-block">
                    <div class="content-block">
                        <div class="block-title">
                            <div class="dot-line"></div><!-- /.dot-line -->
                            <!-- <p class="light-2">Looking for taxi?</p> -->
                            <h2 class="light">Reset Your Password</h2>
                        </div><!-- /.block-title -->
                        <form id="provider_registration" action="{{ url('/provider/password/email') }}" method="post"  class="booking-form-one">
                        {{csrf_field()}}
                            <div class="row">
                                <!-- <div class="col-lg-6">
                                    <div class="input-holder">
                                        <input type="text" name="name" placeholder="Your name">
                                    </div>
                                </div> -->
                                <div class="col-lg-12">
                                    <div class="input-holder">
                                        <input type="email" name="email" id="email" placeholder="Email address" value="{{ old('email') }}">
                                    </div>
                                    @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-12">    
                                <button class="btn btn-danger btn-block" type="submit">Send Password Reset Link <i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>
                                </div>
                            </div><!-- /.row -->
                        </form><!-- /.booking-form-one -->

                        <div class="row justify-content-center align-item-center"> 
                                <a class="twitter-login mt-4" href="{{url('/provider/login')}}"> Already Have an Account ?</a>
                        </div>

                    </div><!-- /.content-block -->
                </div><!-- /.right-upper-block -->
                <div class="right-bottom-block">
                    <div class="content-block cta-block">
                        <div class="icon-block">
                            <i class="conexi-icon-phone-call"></i>
                        </div><!-- /.icon-block -->
                        <div class="text-block">
                            <p>Call and book emergency taxi</p>
                            <a href="callto:888-532-7555">888-532-7555</a>
                        </div><!-- /.text-block -->
                    </div><!-- /.content-block -->
                </div><!-- /.right-bottom-block -->
            </div><!-- /.right-block -->
        </section><!-- /.about-style-three -->
@endsection
