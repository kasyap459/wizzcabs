@extends('web_sp.layouts.app')
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
                    <li><a href="{{url('/es')}}">Hogar</a></li>
                    <li><span class="sep">.</span></li>
                    <li><span class="page-title">Sobre nosotros</span></li>
                </ul><!-- /.thm-breadcrumb -->
                <h2>Acerca de la página</h2>
            </div><!-- /.container -->
        </section><!-- /.inner-banner -->
        <section class="about-style-two">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="content-block">
                            <div class="block-title">
                            <!-- <div><img src="{{asset('web/images/black_logo.png')}}" alt="" width="10%"></div> -->
                                <p>Unas pocas palabras sobre Pronto Taxi</p>
                                <h2>Bienvenido <br />a Pronto Taxi</h2> 
                            </div><!-- /.block-title text-center -->
                            <p>Confíe en nuestra empresa para obtener transporte local y de larga distancia confiable. Ofrecemos viajes de bajo costo a cualquier lugar, así como servicio de transporte a muchos aeropuertos cercanos. Las reservaciones anticipadas son bienvenidas y garantizadas, sin importar el tamaño de su grupo, así que solicite una cotización hoy para nuestro servicio de transporte de calidad.</p>
                            <a href="{{url('/book-taxi/es')}}" class="about-btn">Reservar un Taxi</a>
                        </div><!-- /.content-block -->
                    </div><!-- /.col-lg-6 -->
                    <div class="col-lg-6">
                        <div class="hvr-float-shadow">
                            <div class="image-block">
                                <img src="{{asset('web/images/resources/about-1-12.jpg')}}" alt="Awesome Image" />
                                <div class="bubble-block">
                                    <div class="inner-block">
                                        <p>Confiado por</p>
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
            <img src="{{asset('web/images/background/feature-bg-1-12.png')}}" alt="Awesome Image" class="feature-bg" />
            <div class="container">
                <div class="block-title text-center">
                <!-- <div><img src="{{asset('asset/img/unico.png')}}" alt="" width="20%"></div> -->
                    <p> Lista de beneficios de Pronto Taxi</p>
                    <h2 class="light">Por qué elegirnos</h2>
                </div><!-- /.block-title text-center -->
                <div class="row">
                    <div class="col-lg-4">
                        <div class="single-feature-one">
                            <div class="icon-block">
                                <i class="conexi-icon-insurance"></i>
                            </div><!-- /.icon-block -->
                            <h3><a href="">Garantía de seguridad</a></h3>
                            <p>Pronto Taxi es la forma más inteligente de desplazarse. Un toque y un coche viene directamente hacia ti<span id="dots4">...</span><span id="more4">. Su conductor sabe exactamente adónde ir. Y puedes pagar en efectivo o con tarjeta. Las reservaciones anticipadas son bienvenidas y garantizadas, sin importar el tamaño de su grupo, así que solicite una cotización hoy para nuestro servicio de transporte de calidad."</span></p>
                                <a onclick="myFunction4()" id="myBtn4" class="more-link">LEER MÁS</a>
                        </div><!-- /.single-feature-one -->
                    </div><!-- /.col-lg-4 -->
                    <div class="col-lg-4">
                        <div class="single-feature-one">
                            <div class="icon-block">
                                <i class="conexi-icon-seatbelt"></i>
                            </div><!-- /.icon-block -->
                            <h3><a href="">Controladores aprobados por DBS</a></h3>
                            <p>Viaje con tranquilidad: ¡Conductores autorizados por DBS a su servicio!<span id="dots5">...</span><span id="more5">En Pronto Taxi, su seguridad es primordial. Todos nuestros conductores se someten a rigurosos controles del Servicio de divulgación y restricción (DBS), lo que garantiza que hayan sido examinados minuciosamente por su seguridad. Descanse tranquilo sabiendo que su viaje está en manos de profesionales autorizados y confiables. Elija Pronto Taxi para un viaje seguro y confiable".</span></p>
                                <a onclick="myFunction5()" id="myBtn5" class="more-link">LEER MÁS</a>
                        </div><!-- /.single-feature-one -->
                    </div><!-- /.col-lg-4 -->
                    <div class="col-lg-4">
                        <div class="single-feature-one">
                            <div class="icon-block">
                                <i class="conexi-icon-consent"></i>
                            </div><!-- /.icon-block -->
                            <h3><a href="">Cotización Gratuita</a></h3>
                            <p>"Desbloquee viajes asequibles: ¡Obtenga su cotización de taxi gratis hoy!<span id="dots6">...</span><span id="more6">Experimente precios transparentes con Pronto Taxi. Nuestro compromiso con tarifas justas y competitivas significa que siempre sabrá qué esperar. Simplemente proporcione los detalles de su viaje y le proporcionaremos un presupuesto gratuito y sin compromiso. Sin cargos ocultos ni sorpresas: solo precios sencillos para su conveniencia. Comience su viaje con confianza, elija Pronto Taxi para un viaje sin complicaciones".</span></p>
                                <a onclick="myFunction6()" id="myBtn6" class="more-link">LEER MÁS</a>
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
                                <p>Historia de Pronto Taxi</p>
                                <h2>Cómo llegamos <br> a este nivel</h2>
                            </div><!-- /.block-title -->
                            <div class="history-content history-content-one-pager">
                                <a class="pager-item active" data-slide-index="1" style="display: block;">
                                    <h3>2007</h3>
                                    <p>Escalando Alturas: Se revela nuestro viaje en la industria del taxi. Pronto Taxi no acaba de llegar; abrimos un camino. Años de compromiso inquebrantable, pensamiento innovador y enfoque centrado en el cliente nos han impulsado a la cima de la industria del taxi. Al adoptar Con tecnología de punta, priorizando la seguridad de los pasajeros y fomentando un equipo dedicado, nos hemos ganado nuestro lugar como líder. Desde el primer día, nuestro objetivo fue claro: redefinir la conveniencia de viajar y establecer nuevos estándares. Confíe en el viaje, confíe en Pronto Taxi su socio para mejorar las experiencias de transporte</p>
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
                    <p>Mira nuestros beneficios</p>
                    <h2>que estamos ofreciendo</h2>
                </div><!-- /.block-title -->
                <div class="row">
                    <div class="col-lg-4">
                        <div class="single-offer-one hvr-float-shadow">
                            <div class="image-block">
                                <a href="#"><i class="fa fa-link"></i></a>
                                <img src="{{asset('web/images/resources/offer-1-1.jpg')}}" alt="Awesome Image" />
                            </div><!-- /.image-block -->
                            <div class="text-block">
                            <h3><a href="#">Toca la aplicación y consigue un viaje</a></h3>
                                <p>Pronto Taxi es la forma más inteligente de desplazarse. Un toque y un coche viene directamente hacia ti<span id="dots">...</span><span id="more">. Su conductor sabe exactamente adónde ir. Y puedes pagar en efectivo o con tarjeta. Las reservaciones anticipadas son bienvenidas y garantizadas, sin importar el tamaño de su grupo, así que solicite una cotización hoy para nuestro servicio de transporte de calidad.</span></p>
                                <a onclick="myFunction()" id="myBtn" class="more-link">LEER MÁS</a>
                            </div><!-- /.text-block -->
                        </div><!-- /.single-offer-one -->
                    </div><!-- /.col-lg-4 -->
                    <div class="col-lg-4">
                        <div class="single-offer-one hvr-float-shadow">
                            <div class="image-block">
                                <a href="#"><i class="fa fa-link"></i></a>
                                <img src="{{asset('web/images/resources/offer-1-2.jpg')}}" alt="Awesome Image" />
                            </div><!-- /.image-block -->
                            <div class="text-block">
                            <h3><a href="#">Listo en cualquier lugar y en cualquier momento</a></h3>
                                <p>Viaje diario. Recado al otro lado de la ciudad. Vuelo temprano en la mañana. Las<span id="dots2">...</span><span id="more2"> Bebidas Nocturnas. Donde quiera que vaya, cuente con Pronto Taxi para que lo lleve, no es necesario hacer reservaciones. Cobramos tarifas bajas y conducimos vehículos limpios y en buen estado, todo para garantizar un viaje placentero y asequible. Cuando necesite un servicio de taxi rápido y confiable, ¡cuente con nosotros!</span></p>
                                <a onclick="myFunction2()" id="myBtn2" class="more-link">LEER MÁS</a>
                            </div><!-- /.text-block -->
                        </div><!-- /.single-offer-one -->
                    </div><!-- /.col-lg-4 -->
                    <div class="col-lg-4">
                        <div class="single-offer-one hvr-float-shadow">
                            <div class="image-block">
                                <a href="#"><i class="fa fa-link"></i></a>
                                <img src="{{asset('web/images/resources/offer-1-3.jpg')}}" alt="Awesome Image" />
                            </div><!-- /.image-block -->
                            <div class="text-block">
                            <h3><a href="#">Fácil de usar</a></h3>
                                <p>Tanto los pasajeros como los vehículos están geolocalizados, una manera fácil de encontrarse<span id="dots3">...</span><span id="more3">. Para una perfecta transparencia y optimización del servicio, los pasajeros pueden calificar al conductor.</span></p>
                                <a onclick="myFunction3()" id="myBtn3" class="more-link">LEER MÁS</a>
                            </div><!-- /.text-block -->
                        </div><!-- /.single-offer-one -->
                    </div><!-- /.col-lg-4 -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </section><!-- /.offer-style-one -->
        <section class="cta-style-one text-center">
            <div class="container">
                <p>Llamar servicio 24 horas disponible</p>
                <h3>Llama ahora y reserva <br> nuestro taxi para tu próximo viaje.</h3>
                <a href="{{url('/book-taxi/es')}}" class="cta-btn">Reservar un Taxi</a>
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
    btnText.innerHTML = "LEER MÁS"; 
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "Leer menos"; 
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
    btnText.innerHTML = "LEER MÁS"; 
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "Leer menos"; 
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
    btnText.innerHTML = "LEER MÁS"; 
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "Leer menos"; 
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
    btnText.innerHTML = "LEER MÁS"; 
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "Leer menos"; 
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
    btnText.innerHTML = "LEER MÁS"; 
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "Leer menos"; 
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
    btnText.innerHTML = "LEER MÁS"; 
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "Leer menos"; 
    moreText.style.display = "inline";
  }
}
</script>

@endsection
