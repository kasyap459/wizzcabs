@extends('web_sp.layouts.app')

@section('content')
<!-- Banner -->
<section class="inner-banner">
            <div class="container">
                <ul class="thm-breadcrumb">
                    <li><a href="{{url('/')}}">Hogar</a></li>
                    <li><span class="sep">.</span></li>
                    <li><span class="page-title">Restablecer la contraseña</span></li>
                </ul><!-- /.thm-breadcrumb -->
                <h2>Restablecer la contraseña</h2>
            </div><!-- /.container -->
        </section><!-- /.inner-banner -->
        <section class="about-style-three clearfix">
            <div class="left-block">
                <div class="content-block">
                    <div class="image-block">
                        <img src="{{asset('web/images/resources/book-1-1.jpg')}}" alt="Awesome Image"/>
                    </div><!-- /.image-block -->
                    <div class="block-title">
                        <!-- /.dot-line -->
                        <p>Somos los mejores en tu ciudad.</p>
                        <h2>Bienvenido a la <br> empresa <br> más confiable</h2>
                    </div><!-- /.block-title -->
                    <p>Hay muchas variaciones de pasajes de lorem ipsum disponibles, pero la mayoría ha sufrido alteraciones de alguna forma por humor inyectado o palabras aleatorias que no parecen ni un poco creíbles si vas a utilizar un pasaje.</p>
                    <hr class="style-one" />
                    <div class="tag-line">
                        <span>Seguro .</span>
                        <span>A tiempo .</span>
                        <span>Rápido .</span>
                    </div><!-- /.tag-line -->
                </div><!-- /.content-block -->
            </div><!-- /.left-block -->
            <div class="right-block">
                <div class="right-upper-block">
                    <div class="content-block">
                        <div class="block-title">
                            <div class="dot-line"></div><!-- /.dot-line -->
                            <!-- <p class="light-2">Looking for taxi?</p> -->
                            <h2 class="light">Restablecer su contraseña</h2>
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
                                        <input type="email" name="email" id="email" placeholder="Dirección de correo electrónico" value="{{ old('email') }}">
                                    </div>
                                    @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-12">    
                                <button class="btn btn-danger btn-block" type="submit">Enviar enlace para <br> restablecer contraseña <i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>
                                </div>
                            </div><!-- /.row -->
                        </form><!-- /.booking-form-one -->

                        <div class="row justify-content-center align-item-center"> 
                                <a class="twitter-login mt-4" href="{{url('/provider/login/es')}}"> Ya tienes una cuenta ? ?</a>
                        </div>

                    </div><!-- /.content-block -->
                </div><!-- /.right-upper-block -->
                <div class="right-bottom-block">
                    <div class="content-block cta-block">
                        <div class="icon-block">
                            <i class="conexi-icon-phone-call"></i>
                        </div><!-- /.icon-block -->
                        <div class="text-block">
                            <p>Llama y reserva un taxi de emergencia</p>
                            <a href="callto:8888880000">888 888 0000</a>
                        </div><!-- /.text-block -->
                    </div><!-- /.content-block -->
                </div><!-- /.right-bottom-block -->
            </div><!-- /.right-block -->
        </section><!-- /.about-style-three -->
@endsection
