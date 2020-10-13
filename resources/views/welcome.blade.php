@extends('layouts.app')
@section('content')
<header class="masthead text-white text-center"
        style="background: url('assets/img/bg-masthead.jpg')no-repeat center center;background-size: cover;
               height: 100vh">
    <div class="overlay"></div>
    <div class="container">
        <div class="row" style="font-family: monospace">
            <div class="col-xl-9 mx-auto">
            <h1 class="mb-5">BIENVENUE SUR UHBC TRANSIT</h1>
                <h3>Site de gestion de transport universitaire</h3>
                <h3>de la ville de Chlef</h3>
            </div>
        </div>
    </div>
</header>
<section class="features-icons bg-light text-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="mx-auto features-icons-item mb-5 mb-lg-0 mb-lg-3">
                    <div class="d-flex features-icons-icon"><i class="icon-screen-desktop m-auto text-primary"
                                                               data-bs-hover-animate="pulse"></i></div>
                    <h3>Fully Responsive</h3>
                    <p class="lead mb-0">This theme will look great on any device, no matter the size!</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="mx-auto features-icons-item mb-5 mb-lg-0 mb-lg-3">
                    <div class="d-flex features-icons-icon"><i class="icon-layers m-auto text-primary"
                                                               data-bs-hover-animate="pulse"></i></div>
                    <h3>Bootstrap 4 Ready</h3>
                    <p class="lead mb-0">Featuring the latest build of the new Bootstrap 4 framework!</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="mx-auto features-icons-item mb-5 mb-lg-0 mb-lg-3">
                    <div class="d-flex features-icons-icon"><i class="icon-check m-auto text-primary"
                                                               data-bs-hover-animate="pulse"></i></div>
                    <h3>Easy to Use</h3>
                    <p class="lead mb-0">Ready to use with your own content, or customize the source files!</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="showcase">
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <div class="col-lg-6 order-lg-2 text-white showcase-img"
                 style="background-image:url('assets/img/bg-showcase-1.jpg');"><span></span></div>
        <div class="col-lg-6 my-auto order-lg-1 showcase-text">
            <h2>Fully Responsive Design</h2>
            <p class="lead mb-0">When you use a theme created with Bootstrap, you know that the theme will look great on
                any device, whether it's a phone, tablet, or desktop the page will behave responsively!</p>
        </div>
    </div>
    <div class="row no-gutters">
        <div class="col-lg-6 text-white showcase-img"
             style="background-image:url('assets/img/bg-showcase-2.jpg');"><span></span></div>
    <div class="col-lg-6 my-auto order-lg-1 showcase-text">
        <h2>Updated For Bootstrap 4</h2>
        <p class="lead mb-0">Newly improved, and full of great utility classes, Bootstrap 4 is leading the way in mobile
            responsive web development! All of the themes are now using Bootstrap 4!</p>
    </div>
    </div>
    </div>
</section>
@endsection
