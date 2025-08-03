@extends('web.layouts.app')

@section('content')
<!-- Banner -->
<section class="inner-banner">
            <div class="container">
                <ul class="thm-breadcrumb">
                    <li><a href="{{url('/')}}">Home</a></li>
                    <li><span class="sep">.</span></li> 
                    <li><span class="page-title">Privacy Policy</span></li>
                </ul><!-- /.thm-breadcrumb -->
                <h2>Privacy Policy</h2>
            </div><!-- /.container -->
        </section><!-- /.inner-banner -->
       
        <section class="contact-form-style-one">
            <div class="container">
            {!! $page->content !!}
               
            </div><!-- /.container -->
        </section><!-- /.contact-form-style-one -->
@endsection
