@extends('web_sp.layouts.app')

@section('content')
<!-- Banner -->
<section class="inner-banner">
            <div class="container">
                <ul class="thm-breadcrumb">
                    <li><a href="{{url('/es')}}">Hogar</a></li>
                    <li><span class="sep">.</span></li>
                    <li><span class="page-title"> Conviértete en conductor</span></li>
                </ul><!-- /.thm-breadcrumb -->
                <h2>Conviértete en conductor</h2>
            </div><!-- /.container -->
        </section><!-- /.inner-banner -->
        <section class="about-style-three clearfix">
            <div class="left-block">
                <div class="content-block">
                    <div class="image-block">
                        <img src="{{asset('web/images/resources/book-1-1.jpg')}}" alt="Awesome Image"/>
                    </div><!-- /.image-block -->
                    <div class="block-title">
                        <!-- <div><img src="https://web.unicotaxi.com/web/images/black_logo.png" alt="" width="10%"></div> -->
                        <p>Somos los mejores en tu ciudad.</p>
                        <h2>Pronto Taxi <br> necesita un socio <br> como usted</h2>  
                    </div><!-- /.block-title -->
                    <p>Conduce con Pronto Taxi y gana mucho dinero como contratista independiente. Reciba un pago semanal solo por ayudar a nuestra comunidad de pasajeros a viajar por la ciudad. Sea su propio jefe y gane tarifas por conducir según su propio horario.</p>
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
                        <div><img src="https://web.unicotaxi.com/web/images/white.png" alt="" width="30%"></div>
                            <!-- <p class="light-2">Looking for taxi?</p> -->
                            <h2 class="light">Administra tu <br> cuenta</h2>
                        </div><!-- /.block-title -->
                        <form action="{{ url('/provider/login') }}" method="post"  class="booking-form-one">
                        @if ($errors->has('email'))
            <span class="help-block text-center" style="color: red;">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
                        {{csrf_field()}}
                            <div class="row">
                                <!-- <div class="col-lg-6">
                                    <div class="input-holder">
                                        <input type="text" name="name" placeholder="Your name">
                                    </div>
                                </div> -->
                                <div class="col-lg-12">
                                    <div class="input-holder">
                                        <input type="email" name="email" id="email" placeholder="Dirección de correo electrónico">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-holder">
                                        <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña">
                                    </div>
                                </div>
                                <!-- <div class="col-lg-12">
                                    <div class="input-holder">
                                        <input type="text" name="name" placeholder="Passengers #">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-holder">
                                        <input type="text" name="name" placeholder="Pick up address">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-holder">
                                        <input type="text" name="name" placeholder="Drop off address">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="input-holder">
                                        <input type="text" name="name" placeholder="Select date">
                                        <i class="conexi-icon-small-calendar"></i>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="input-holder">
                                        <select class="selectpicker">
                                            <option>Select Time</option>
                                            <option>10AM-10.59AM</option>
                                            <option>12PM-12.59PM</option>
                                            <option>1PM-1.59PM</option>
                                            <option>2PM-2.59PM</option>
                                        </select>
                                        <i class="conexi-icon-clock"></i>
                                    </div>
                                </div> -->
                                <div class="col-lg-6 d-flex">
                                <input type="checkbox" value="remember-me" style="width:20px" /> 
                                <p class="pl-2 pt-3">Recordar contraseña</p> 
                                </div>
                                <div class="col-lg-6">
                                <p style="float: right;font-size: 15px;" class="pt-3"><a href="{{ url('/provider/password/reset/es') }}">¿Olvidaste tu contraseña?</a></p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-holder">
                                        <button type="submit">Acceso</button>
                                    </div><!-- /.input-holder -->
                                </div><!-- /.col-lg-12 -->
                            </div><!-- /.row -->
                        </form><!-- /.booking-form-one -->
                    </div><!-- /.content-block -->
                </div><!-- /.right-upper-block -->
                <div class="right-bottom-block">
                    <div class="content-block cta-block">
                        <div class="icon-block">
                            <i class="conexi-icon-phone-call"></i>
                        </div><!-- /.icon-block -->
                        <div class="text-block">
                            <p>Llama y reserva un taxi de emergencia</p>
                            <a href="callto:1234567890">1234567890</a>
                        </div><!-- /.text-block -->
                    </div><!-- /.content-block -->
                </div><!-- /.right-bottom-block -->
            </div><!-- /.right-block -->
        </section><!-- /.about-style-three -->
@endsection
