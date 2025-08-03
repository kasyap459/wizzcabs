@extends('provider.layout.auth')

@section('style')
<style>
.cps-banner.style-16 .cps-banner-form .account-form {
    background-color: #ffffff;
    border-radius: 3px;
    padding: 4px 42px 20px;
}
.account-form input:not([type=submit]):not([type=radio]):not([type=checkbox]), .account-form select, .account-form textarea {
    border-radius: 3px;
    margin-bottom: 8px;
}
.account-form select{
  padding: 13px 16px !important;
}
.helper{
    color: #f31616;
}
.termlink{
    color: #0662c3 !important;
}

/*------------------------------------------------------*/
.agile_info a {
    color: #5db1f9;
}
.facebook-login{
    color: white !important;
}
input[type="text"], select {
    font-size: 15px !important;
    font-weight: 500 !important;
    text-align: left;
    text-transform: capitalize;
    letter-spacing: 1px;
    padding: 12px 10px 12px 10px;
    width:90% ;
    display:inline-block ;
    box-sizing: border-box !important;
    border: none;
    outline: none;
    background: transparent !important;
    font-family: 'Open Sans', sans-serif;
}
input[type="email"] {
    font-size: 15px !important;
    font-weight: 500  !important;
    text-align: left;
    letter-spacing: 1px;
    padding: 12px 10px 12px 10px;
    width:90% !important;
    display:inline-block !important;
    box-sizing: border-box !important;
    border: none !important;
    outline: none;
    background: transparent !important;
    font-family: 'Open Sans', sans-serif;
}
input[type="Password"] {
    font-size: 15px !important;
    font-weight: 500 !important;
    text-align: left;
    text-transform: capitalize;
    letter-spacing: 1px;
    padding: 12px 10px 12px 10px;
    width:90%;
    display:inline-block !important;
    box-sizing: border-box;
    border: none !important;
    outline: none;
    background: transparent !important;
    font-family: 'Open Sans', sans-serif;
}
select{
    font-size: 15px !important;
    font-weight: 500 !important;
    text-align: left;
    text-transform: capitalize;
    letter-spacing: 1px;
    padding: 7px 49px 6px 36px;
    width: 94%;
    display: inline-block;
    box-sizing: border-box !important;
    border: none;
    outline: none;
    background: transparent !important;
    font-family: 'Open Sans', sans-serif;
}
.input-group {
    padding: 0px 0px;
    border-bottom: 1px solid #ddd;
    margin-bottom: 3px;
    margin-top: 7px;
    display: block;
}
.btn-block {
    display: block;
    width: 100%;
}
.btn:active {
    outline: none;
}
.btn-danger {
    color: #f9f9f9 !important;
    background-color: #6675df !important;
    margin-top: 30px !important;
    width: 100% !important;
    outline:none;
    padding:15px 12px !important;
    cursor:pointer !important;
    letter-spacing: 2px !important;
    font-size: 15px !important;
    font-weight: 600 !important;
    border: 1px solid #fff !important;
    text-transform: uppercase !important;
    transition: 0.5s all !important;
    -webkit-transition: 0.5s all !important;
    -moz-transition: 0.5s all !important;
    -o-transition: 0.5s all !important;
    -ms-transition: 0.5s all !important;
    font-family: 'Open Sans', sans-serif !important;
    background-image: none;
}
.btn-verify {
    color: #fff !important;
    background-color: #4CAF50 !important;
    margin-top: 23px !important;
    width: 100% !important;
    outline: none;
    padding: 15px 12px !important;
    cursor: pointer !important;
    letter-spacing: 2px !important;
    font-size: 11px !important;
    font-weight: 600 !important;
    border: 1px solid #4CAF50 !important;
    text-transform: uppercase !important;
    transition: 0.5s all !important;
    -webkit-transition: 0.5s all !important;
    -moz-transition: 0.5s all !important;
    -o-transition: 0.5s all !important;
    -ms-transition: 0.5s all !important;
    font-family: 'Open Sans', sans-serif !important;
    background-image: none;
}
.input-verify{
    padding: 0px 0px;
    border-bottom: none;
    margin-bottom: 28px;
    margin-top: 7px;
}
.btn-danger:hover{
    background: transparent !important;
    border: 1px solid #121312 !important;
    color: #121312 !important;
}
.w3_info h2 {
  display: inline-block;
    font-size: 26px;
    margin-bottom: 40px;
    color: #353434;
    letter-spacing: 2px;    
}
.w3_info h4 {
    display: inline-block;
    font-size: 15px;
    color: #444;
    text-transform: capitalize;
}
span.fa.fa-facebook {
    vertical-align: middle;
    font-size: 20px;
    padding-left: 20px;
}
 ::-webkit-input-placeholder {
color:#999 !important;
}
:-moz-placeholder { /* Firefox 18- */
color:#999 !important;
}
::-moz-placeholder {  /* Firefox 19+ */
color:#999 !important;
letter-spacing:5px;
}
:-ms-input-placeholder {  
color:#999 !important;
}

a.btn.btn-block.btn-social.btn-facebook {
    display: block;
    width: 100%;
    padding: 10px 0px;
    text-align: center;
    font-size: 16px;
    font-weight: 600;
    letter-spacing: 1px;
}
h1 {
      text-align: center;
    font-size: 45px;
    margin: 30px 0px;
    letter-spacing: 3px;
    color: #fff;
    text-transform: uppercase;
    font-weight: 700;
}
.w3_info {
    flex-basis: 65%;
    -webkit-flex-basis: 65%;
    box-sizing: border-box;
    padding: 4em;
    background-color: #fff;
}
.left_grid_info {
    padding: 7em 4em;
}
.left_grid_info h3 {
    color: #353434;
    font-size: 2.2em;
}
.left_grid_info p {
    color: #383636;
    font-size: 14px;
    margin: 2em 0;
    line-height: 28px;
}
.btn{
    line-height: 1.42857143 !important;
    border-radius: 4px !important;
}
a.btn {
    color: #fff !important;
    font-weight: 600 !important;
    font-size: 15px !important;
    border: 1px solid #fff !important;
    padding: 12px 40px !important;
    border: 1px solid #fff !important;
    display: inline-block !important;
    margin-top: 1em !important;
    text-transform: uppercase !important;
    letter-spacing: 2px !important;
}
a.btn:hover {
    background: #fff;
    color: #4CAF50;
}
.agile_info {
    display: -webkit-box;      /* OLD - iOS 6-, Safari 3.1-6 */
    display: -moz-box;         /* OLD - Firefox 19- (buggy but mostly works) */
    display: -ms-flexbox;      /* TWEENER - IE 10 */
    display: -webkit-flex;     /* NEW - Chrome */
    display: flex;             /* NEW, Spec - Opera 12.1, Firefox 20+ */
}

.w3l_form {
    padding: 0px;
    flex-basis: 35%;
    -webkit-flex-basis: 35%;
    background: #f7d9d7;
}
.left {
    width: 100%;
    float: left;
    margin-bottom: 20px;
}
.margin {
    margin-right: 2%;
}
label {
    margin: 0;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #777;
}
h3.w3ls {
    margin: 10px 0px;
    padding-left: 60px;
}
h3.agileits {
    padding-left: 10px;
}
.container1 {
    width: 75%;
    margin: 0 auto;
}
.input-group i.fa {
    font-size: 16px;
    vertical-align: middle;
    color: #999;
    box-sizing:border-box;
    float:left;
    width: 6%;
    text-align: center;
    margin-top: 12px;
}
h5 {
    text-align: center;
    margin: 10px 0px;
    font-size: 15px;
    font-weight: 600;
        color: #000;
}

@media screen and (max-width: 1080px){
    .left_grid_info {
        padding: 7em 3em;
    }
    .w3_info {
        padding: 3em 3em;
    }
    .left_grid_info h4 {
        font-size: 1.1em;
    }
}
@media screen and (max-width: 1024px){
    .left_grid_info h3 {
        font-size: 2em;
    }
    .left_grid_info {
        padding: 5em 3em;
    }
}
@media screen and (max-width: 991px){
    .w3_info h2 {
        font-size: 24px;
    }
}
@media screen and (max-width: 900px){
    
    .left_grid_info h4 {
        font-size: 1em;
    }
    .agile_info {
        flex-direction: column;
    }
    .left_grid_info {
        padding: 3em 3em;
    }
}
@media screen and (max-width: 800px){
    input[type="text"],input[type="email"],input[type="password"] {
        font-size: 14px;
    }
    i.fa.fa-user,i.fa.fa-envelope,i.fa.fa-lock {
        margin-top: 10px;
    }
}
@media screen and (max-width: 768px){
    
    .left_grid_info h3 {
        font-size: 1.6em;
    }
}
@media screen and (max-width: 736px){
    
    .left_grid_info h3 {
        font-size: 1.7em;
    }
    .w3_info h2 {
        font-size: 22px;
        margin-bottom: 20px;
    }
    .w3_info {
        padding: 3em 2em;
    }
    .footer p {
        font-size: 14px;
    }
    .w3_info h4 {
        font-size: 14px;
    }
    .btn-danger {
        padding: 13px 12px;
        font-size: 14px;
    }
}
@media screen and (max-width: 640px){
    .w3l_form {
        padding: 3em 4em;
        float: none;
        margin: 0 auto;
    }
    .left_grid_info {
        padding: 0;
    }
    .w3_info {
        padding: 3em 4em;
        margin: 0 auto;
    }
    label {
        font-size: 14px;
        letter-spacing: .5px;
    }
}
@media screen and (max-width: 568px){
    .btn-danger {
        font-size: 13px;
        width: 55%;
    }
    a.btn {
        font-size: 13px;
        padding: 10px 40px;
        letter-spacing: 1px;
    }
}
@media screen and (max-width: 480px){
    .w3l_form {
        padding: 3em;
    }
    .w3_info {
        padding: 3em;
    }
    .btn-danger {
        width: 63%;
    }
}
@media screen and (max-width: 414px){
    .w3l_form {
        padding: 3em 2em;
    }
    .w3_info {
        padding: 2em;
    }
    .left_grid_info p {
        font-size: 13px;
    }
    .left {
        width: 100%;
        float: none;
        margin: 0;
    }
    .left_grid_info h3 {
        font-size: 1.4em;
    }
}
@media screen and (max-width: 384px){
    .left_grid_info h4 {
        font-size: .9em;
    }
    .left_grid_info p {
        letter-spacing: .5px;
    }
    .btn-danger {
        width: 70%;
    }
    .input-group {
        margin-bottom: 25px;
        margin-top: 0px;
    }
}
@media screen and (max-width: 375px){
    .left_grid_info h3 {
        font-size: 1.5em;
    }
    .w3_info h4 ,label,input[type="text"], input[type="email"], input[type="password"]{
        font-size: 13px;
    }
}
@media screen and (max-width: 320px){
    .btn-danger {
        padding: 13px 12px;
        font-size: 13px;
    }
    input[type="text"], input[type="email"], input[type="password"] {
        font-size: 13px;
    }
    .w3_info h2 {
        font-size: 20px;
        letter-spacing: 1px;
    }
}
.code{
    width: 20% !important;
    display: inline-block !important;
    padding: 0px 10px !important;
    margin-right: 18px !important;
    border: none !important;
    border-bottom: 1px solid gray !important;
    border-radius: 0 !important;
}
.phone{
    display: inline-block !important;
    width: 60% !important;
    border: none !important;
    border-bottom: 1px solid gray !important;
    border-radius: 0 !important;
}
.width{
    width: 90% !important;
    border: none !important;
}
.no-border{
    border: none;
}
.creatbox{
        text-align: center;
    margin: 20px 0px;
}
.help-block{
    color: red;
}
.btn, .btn:focus, .btn:visited, .btn:active, .btn:active:focus{
    background-image: none;
}
footer .cps-footer-widget-area .cps-widget .cps-socials a {
    color: unset !important;
}
footer .cps-footer-widget-area .cps-footer-logo {
    	margin-right: 3%;
    	padding-top: 4%;
}
footer.style-5 .cps-footer-widget-area .cps-widget .cps-socials a:hover {
    color: #ffffff !important;
}
</style>
@endsection
@section('content')
<!-- Page Header -->
    <div class="page-header">
        <div class="container">
            
        </div>
    </div>
<!-- Page Header End -->
<div class="cps-section cps-section-padding custom-padding">
    <div class="container1">
    <div class="row">
        <div class="agile_info">
            <div class="w3_info">
            @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
                <h2>Reset Password</h2>
                    <form id="provider_registration" action="{{ url('/provider/password/email') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="left margin">
                        <label>Mobile</label>
                        <div class="input-group">
                            <span><i class="fa fa-user" aria-hidden="true"></i></span>
                            <input class="width" type="text" name="mobile" placeholder="Mobile Number" value="{{ old('mobile') }}" required> 
                        </div>
                        @if ($errors->has('mobile'))
                            <span class="help-block">
                                <strong>{{ $errors->first('mobile') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="clear"></div>
                    <div>     
                        <button class="btn btn-danger btn-block" type="submit">SEND PASSWORD RESET LINK <i class="fa fa-long-arrow-right" aria-hidden="true"></i></button >
                    </div>                
                </form>
                <div class="creatbox">
                <a class="twitter-login" href="{{url('/provider/login')}}"> ALREADY HAVE AN ACCOUNT?</a>
                </div>
        </div>
        <div class="w3l_form">
            <div class="left_grid_info">
                <h3>{{ Setting::get('site_title','Unicotaxi') }} needs Partner Like You</h3>
                <p>Drive with {{ Setting::get('site_title','Unicotaxi') }} and earn great money as an independent contractor. Get paid weekly just for helping our community of riders get rides around town. Be your own boss and get paid in fares for driving on your own schedule.</p>
            </div>
        </div>
        <div class="clear"></div>
        </div>
     </div>
    </div>
</div>
@endsection
