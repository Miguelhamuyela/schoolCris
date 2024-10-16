@extends('layouts.merge.site')
@section('title', 'Início')
@section('content')

    <!-- ======= Hero Section ======= -->
    <section id="hero" class="clearfix">
        <div class="container d-flex h-100">
            <div class="row justify-content-center align-self-center" data-aos="fade-up">
                <div class="col-lg-6 intro-info order-lg-first order-last" data-aos="zoom-in" data-aos-delay="100">
                    <h2>Gestão Escolar!<br><span></span></h2>
                    <div class="bbtn">
                        <a href="{{ route('admin.home') }}" class="btn bbttn"></a>
                    </div>
                </div>

                <div class="col-lg-6 intro-img order-lg-last order-first" data-aos="zoom-out" data-aos-delay="200">
                    <img src="/site/assets/img/intro-img.svg" alt="" class="img-fluid">
                </div>
            </div>

        </div>
    </section><!-- End Hero -->



















  <main id="main">




  </main><!-- End #main -->


@endsection
