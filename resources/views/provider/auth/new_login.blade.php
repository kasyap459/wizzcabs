@extends('web.layouts.app')

@section('styles')

<style>
    button[type=button] {
        cursor: pointer;
        border: none;
        outline: none;
        width: 100%;
        background-color: #5fbb76;
        height: 67px;
        border-radius: 33.5px;
        text-align: center;
        font-size: 18px;
        font-weight: 600;
        color: #111111;
        transition: all .4s ease;
    }

    button[type=submit] {
        cursor: pointer;
        border: none;
        outline: none;
        width: 100%;
        background-color: #5fbb76;
        height: 67px;
        border-radius: 33.5px;
        text-align: center;
        font-size: 18px;
        font-weight: 600;
        color: #111111;
        /* transition: all .4s ease; */
    }

    input {
        border: none;
        outline: none;
        width: 100%;
        background-color: #242424;
        height: 67px;
        border-radius: 33.5px;
        padding-left: 40px;
        color: #B5B5B5;
        font-size: 14px;
        font-weight: 600;
    }

    .form-control {
        display: block;
        width: 100%;
        height: calc(2.25rem + 2px);
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        /* transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out; */
    }

    .btn-verify {
        color: #fff !important;
        background-color: #231f20 !important;
        margin-top: 23px !important;
        width: 100% !important;
        outline: none;
        padding: 15px 12px !important;
        cursor: pointer !important;
        letter-spacing: 2px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        border: 1px solid #353434 !important;
        text-transform: uppercase !important;
        transition: 0.5s all !important;
        -webkit-transition: 0.5s all !important;
        -moz-transition: 0.5s all !important;
        -o-transition: 0.5s all !important;
        -ms-transition: 0.5s all !important;
        font-family: 'Open Sans', sans-serif !important;
        background-image: none;
    }

    .code {
        width: 22%;

        /* transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out; */
    }



    .num {
        width: 78%;

        /* transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out; */
    }
    .block-title h2{
        font-size: 40px;
    }
    .about-style-three .right-block .right-bottom-block {
    background-color: #fff;
}
.tag-line span{
    font-size: 50px;
    font-weight: 700;
    color: #000;
    letter-spacing: -0.04em;
}
</style>

@endsection

@section('content')

<section class="inner-banner">
            <div class="container">
                <ul class="thm-breadcrumb">
                    <li><a href="{{url('/')}}">Home</a></li>
                    <li><span class="sep">.</span></li>
                    <li><span class="page-title">Login</span></li>
                </ul><!-- /.thm-breadcrumb -->
                <h2>Login Now</h2>
            </div><!-- /.container -->
        </section><!-- /.inner-banner -->

<!-- Banner -->
<section class="about-style-three clearfix">
    <div class="left-block">
        <div class="content-block">
            <div class="image-block">
                <img src="{{asset('web/images/resources/book-1-1.jpg')}}" alt="Awesome Image" />
            </div><!-- /.image-block -->
            <div class="block-title">
                <!-- <div><img src="https://web.unicotaxi.com/web/images/black_logo.png" alt="" width="10%"></div> -->
                <p>We’re the best in your town</p>
                <h2>{{ Setting::get('site_title','Unicotaxi') }} needs Partner <br> Like You</h2>
            </div><!-- /.block-title -->
            <p>Drive with {{ Setting::get('site_title','Unicotaxi') }} and earn great money as an independent contractor. Get paid weekly just for helping our community of riders get rides around town. Be your own boss and get paid in fares for driving on your own schedule.</p>
            <!-- <hr class="style-one" />
            <div class="tag-line">
                <span>Safe .</span>
                <span>Ontime .</span>
                <span>Quick .</span>
            </div> -->
        </div><!-- /.content-block -->
    </div><!-- /.left-block -->
    <div class="right-block">
        <div class="right-upper-block">
            <div class="content-block">
                <div class="block-title">
                    <div><img src="https://web.unicotaxi.com/web/images/white.png" alt="" width="30%"></div>
                    <!-- <p class="light-2">Looking for taxi?</p> -->
                    <h2 class="light">Login to Your <br> Account</h2>
                </div><!-- /.block-title -->
                <form id="booking-form-one" method="post" action="{{url('/provider/login')}}" autocomplete="off">
                    {{ csrf_field() }}

                    <span class="help-block text-center" id="custom_err" style="color: red; display: none;">
                        <strong class="msg">Please fill Country Code and Phone number</strong>
                    </span>
                    <span class="help-block text-center" id="custom_success" style="color: green; display: none;">
                        <strong class="msg">Please fill Country Code and Phone number</strong>
                    </span>
                    <div class="left margin" id="first_step">
                        <div class="input-holder">
                            <div class="row">
                                <input class="code" type="text" value="@if(Session::has('dial_code')) {{ Session::get('dial_code') }} @else +677 @endif" placeholder="+677" id="country_code" name="country_code" required="" autocomplete="off">

                                <input class="num" id="mobile" type="text" name="mobile" placeholder="Mobile" required="" autocomplete="off" maxlength="10" pattern="[1-9]{1}[0-9]{9}">
                            </div>
                        </div>
                        @if ($errors->has('mobile'))
                        <span class="help-block">
                            <strong>{{ $errors->first('mobile') }}</strong>
                        </span>
                        @endif

                        <div class="row d-flex align-items-center justify-content-center h-100 mt-4">
                            <div class="col-md-6">
                                <div class="container-btn" id="mobile_verfication">
                                    <!-- <button class="btn-verify btn-block" type="button" id="send_otp">
                                        Send OTP
                                    </button> -->

                                    <button class="btn btn-verify btn-block" type="button" id="sendotp">Send O T P</button>
                                    <!-- <button class="btn btn-verify btn-block" id="send_otp" type="button">Send O T P</button> -->
                                    <!-- <button class="btn btn-danger btn-block" type="submit">Register Now <i class="fa fa-long-arrow-right" aria-hidden="true"></i></button > -->

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="left mt-3" id="second_step" style="display: none;">
                        <div class="input-holder">
                            <input class="width" id="password" type="text" name="password" placeholder="Enter OTP" autocomplete="off" />
                          
                        </div>

                        @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif

                        <div class="row d-flex align-items-center justify-content-center h-100 mt-4">
                            <div class="col-md-6">
                                <div class="container-btn" id="mobile_verfication">
                                    <button class="btn-verify btn-block" type="submit" id="login_otp">
                                        Login Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                </form>
            </div><!-- /.content-block -->
        </div><!-- /.right-upper-block -->
        <div class="right-bottom-block">
            <div class="content-block cta-block">
                <div class="icon-block">
                </div><!-- /.icon-block -->
                <div class="text-block">
                    <p>Call and book emergency taxi</p>
                    <a href="callto:1234567890">1234567890</a>
                </div><!-- /.text-block -->
            </div><!-- /.content-block -->
        </div><!-- /.right-bottom-block -->
    </div><!-- /.right-block -->
</section><!-- /.about-style-three -->

<section>
    <!-- <hr class="style-one"/> -->
    <div class="tag-line text-center mt-3 mb-3">
        <span>Safe .</span>
        <span>Ontime .</span>
        <span>Quick .</span>
    </div><!-- /.tag-line -->
</section>
@endsection

@section('scripts')
 <script type="text/javascript">

    $(document).ready(function() {
        // $('#sendotp').on('click', function(e) {
        //     e.preventDefault();

        //     $('#first_step').hide();
        //     $('#second_step').show();

        // });

$('#sendotp').on('click',function(e){
    e.preventDefault();
    var csrf = $("input[name='_token']").val();
    var country_code = $("#country_code").val();
    var phone_number = $("#mobile").val();
    if(country_code ==''){
        $("#custom_err").show();
        $("#custom_err .msg").text('Please enter Country Code');
        return false;
    }
    if(phone_number ==''){
        $("#custom_err").show();
        $("#custom_err .msg").text('Please enter Phone number');
        return false;
    }
    
    $.ajax({
        url: "{{url('/provider/sendotp/login')}}",
        type:'POST',
        data:{ mobile :phone_number ,country_code :country_code ,'_token':csrf},
        success: function(result) {
            if(result.success ==1){
            $("#custom_err").hide();
            $("#custom_success").show();
            $("#custom_success .msg").text(result.data);
            $('#second_step').show();
            $('#first_step').hide();
            }else{
                $("#custom_err").show();
                $("#custom_err .msg").text(result.data);        
            }
        },
        error:function(jqXhr,status) { 
            if(jqXhr.status === 422) {
                $("#custom_err").show();
                var errors = jqXhr.responseJSON;
                $.each( errors , function( key, value ) { 
                    $("#custom_err .msg").html(value);
                }); 
            } 
        }

    });

//     $('#login_otp').on('click',function(e){
//     e.preventDefault();
//     // alert("Hai");
//     var csrf = $("input[name='_token']").val();
//     var otp = $("#password").val();
//     if(otp==''){ 
//         alert("Please enter O T P");
//         $("#custom_success").hide();
//         $("#custom_err").show();
//         $("#custom_err .msg").text('Please enter O T P');
//         return false;
//     }
//     if(otp.length != 6){
//         alert("Please check O T P");
//         $("#custom_success").hide();
//         $("#custom_err").show();
//         $("#custom_err .msg").text('Please check O T P');
//         return false;
//     }
//     $.ajax({
//         url: "{{url('/provider/verifyotplogin')}}",
//         type:'POST',
//         data:{ otp :otp ,'_token':csrf},
//         success: function(result) {
//             if(result.success ==1){
//                 $("#custom_err").hide();
//                 $("#custom_success").show();
//                 $("#custom_success .msg").text(result.data);
//                 location.reload(true);
//                 $('#second_step').hide();
//             }else{
//                 $("#custom_success").hide();
//                 $("#custom_err").show();
//                 $("#custom_err .msg").text(result.data);
//             }
//         },
//         error:function(jqXhr,status) { 
//             if(jqXhr.status === 422) {
//                 $("#custom_err").show();
//                 var errors = jqXhr.responseJSON;
//                 $.each( errors , function( key, value ) { 
//                     $("#custom_err .msg").html(value);
//                 }); 
//             }
//         }

//     });
// });
});

    });
</script>

@endsection