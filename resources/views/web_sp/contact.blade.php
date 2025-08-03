@extends('web_sp.layouts.app')

@section('content')
<!-- Banner -->
<section class="inner-banner">
            <div class="container">
                <ul class="thm-breadcrumb">
                    <li><a href="{{url('/es')}}">Hogar</a></li>
                    <li><span class="sep">.</span></li> 
                    <li><span class="page-title">Contacto</span></li>
                </ul><!-- /.thm-breadcrumb -->
                <h2>Contacto</h2>
            </div><!-- /.container --> 
        </section><!-- /.inner-banner -->
        <div class="contact-page-map-wrapper">
            <div class="mapouter google-map" id="contact-google-map" style="transform:translateX(-20px); margin-top:20px;"><div class="gmap_canvas"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4067821.2281338163!2d-104.50082084448762!3d24.916927720673197!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x84043a3b88685353%3A0xed64b4be6b099811!2sMexico!5e0!3m2!1sen!2sin!4v1697719032803!5m2!1sen!2sin" width="1200" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe><a href="https://2yu.co"></a><br><style>.mapouter{position:relative;text-align:right;height:100%;width:100%;}</style><a href="https://embedgooglemap.2yu.co/"></a><style>.gmap_canvas {overflow:hidden;background:none!important;height:100%;width:100%;}</style></div></div>
            <div class="contact-info-block mt-5">
                <p>Mexico</p>
                <ul class="contact-infos">
                    <li><i class="fa fa-envelope"></i>info@pronto.com</li>
                    <li><i class="fa fa-phone-square"></i>1234567890</li>
                </ul><!-- /.contact-infos -->
            </div><!-- /.contact-info-block -->
        </div><!-- /.contact-page-map-wrapper -->
        <section class="contact-form-style-one">
            <div class="container">
                <div class="block-title text-center">
                <div><img src="{{asset('asset/img/unico.png')}}" alt="" width="20%"></div><!-- /.dot-line -->
                    <p>Contacta con nosotros ahora</p>
                    <h2>Dejar un mensaje</h2>
                </div><!-- /.block-title -->
                <form action="inc/sendmail.php" class="contact-form-one row" id="contact-form" method="post">
                    <div class="col-lg-6">
                        <div class="input-holder">
                            <input class="form-control" type="text" name="form_name" placeholder="Su nombre">
                        </div><!-- /.input-holder -->
                    </div><!-- /.col-lg-6 -->
                    <div class="col-lg-6">
                        <div class="input-holder">
                            <input class="form-control" type="email" name="form_email" placeholder="Dirección de correo electrónico">
                        </div><!-- /.input-holder -->
                    </div><!-- /.col-lg-6 -->
                    <div class="col-lg-6">
                        <div class="input-holder">
                            <input class="form-control" type="text" name="form_phone" placeholder="Teléfono">
                        </div><!-- /.input-holder -->
                    </div><!-- /.col-lg-6 -->
                    <div class="col-lg-6">
                        <div class="input-holder">
                            <input class="form-control" type="text" name="form_subject" placeholder="Sujeto">
                        </div><!-- /.input-holder -->
                    </div><!-- /.col-lg-6 -->
                    <div class="col-lg-12">
                        <div class="input-holder">
                            <textarea class="form-control" name="form_message" placeholder="Escribe un mensaje"></textarea>
                        </div><!-- /.input-holder -->
                    </div><!-- /.col-lg-6 -->
                    <div class="col-lg-12">
                        <div class="input-holder text-center">
                            <input id="form_botcheck" name="form_botcheck" class="form-control" type="hidden" value="">
                            <button class="theme-btn btn-style-two" type="submit" data-loading-text="Please wait..."><span>Enviar mensaje</span></button>
                        </div><!-- /.input-holder -->
                    </div><!-- /.col-lg-6 -->
                </form><!-- /.contact-form-one -->
            </div><!-- /.container -->
        </section><!-- /.contact-form-style-one -->
@endsection
