{{--
  SolarShare Landing Page (Solartec Template Implementation)
  Adheres strictly to Blade inheritance:
  - @extends('layouts.solartec')
  - @section('title', ...)
  - @section('content')
--}}
@extends('layouts.solartec')

@section('title', __('messages.home.page_title'))

@section('meta_description', __('messages.home.meta_description'))

@section('content')

    <!-- ==================== HERO CAROUSEL ==================== -->
    <div class="container-fluid p-0 pb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="owl-carousel header-carousel position-relative">
            <!-- Slide 1 -->
            <div class="owl-carousel-item position-relative" data-dot="<img src='{{ asset('solartec/img/carousel-1.jpg') }}'>">
                <img class="img-fluid" src="{{ asset('solartec/img/carousel-1.jpg') }}" alt="{{ __('messages.home.hero_slide1_alt') }}">
                <div class="owl-carousel-inner">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-10 col-lg-8">
                                <span class="badge badge-solar mb-3 text-uppercase px-3 py-2">{{ __('messages.home.hero_slide1_badge') }}</span>
                                <h1 class="display-3 text-white animated slideInDown">{{ __('messages.home.hero_slide1_title') }}</h1>
                                <p class="fs-5 fw-medium text-white mb-4 pb-3">{{ __('messages.home.hero_slide1_desc') }}</p>
                                <a href="{{ route('marketplace') }}" class="btn btn-primary rounded-pill py-3 px-5 animated slideInLeft me-3">
                                    <i class="fa fa-search me-2"></i>{{ __('messages.home.hero_slide1_btn1') }}
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-outline-light rounded-pill py-3 px-5 animated slideInRight">
                                    <i class="fa fa-plus-circle me-2"></i>{{ __('messages.home.hero_slide1_btn2') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="owl-carousel-item position-relative" data-dot="<img src='{{ asset('solartec/img/carousel-2.jpg') }}'>">
                <img class="img-fluid" src="{{ asset('solartec/img/carousel-2.jpg') }}" alt="{{ __('messages.home.hero_slide2_alt') }}">
                <div class="owl-carousel-inner">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-10 col-lg-8">
                                <span class="badge badge-clean mb-3 text-uppercase px-3 py-2">{{ __('messages.home.hero_slide2_badge') }}</span>
                                <h1 class="display-3 text-white animated slideInDown">{{ __('messages.home.hero_slide2_title') }}</h1>
                                <p class="fs-5 fw-medium text-white mb-4 pb-3">{{ __('messages.home.hero_slide2_desc') }}</p>
                                <a href="{{ route('marketplace') }}" class="btn btn-primary rounded-pill py-3 px-5 animated slideInLeft me-3">{{ __('messages.home.hero_slide2_btn1') }}</a>
                                <a href="#how-it-works" class="btn btn-outline-light rounded-pill py-3 px-5 animated slideInRight">{{ __('messages.home.hero_slide2_btn2') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="owl-carousel-item position-relative" data-dot="<img src='{{ asset('solartec/img/carousel-3.jpg') }}'>">
                <img class="img-fluid" src="{{ asset('solartec/img/carousel-3.jpg') }}" alt="{{ __('messages.home.hero_slide3_alt') }}">
                <div class="owl-carousel-inner">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-10 col-lg-8">
                                <span class="badge badge-rent mb-3 text-uppercase px-3 py-2">{{ __('messages.home.hero_slide3_badge') }}</span>
                                <h1 class="display-3 text-white animated slideInDown">{{ __('messages.home.hero_slide3_title') }}</h1>
                                <p class="fs-5 fw-medium text-white mb-4 pb-3">{{ __('messages.home.hero_slide3_desc') }}</p>
                                <a href="{{ route('register') }}" class="btn btn-primary rounded-pill py-3 px-5 animated slideInLeft me-3">{{ __('messages.home.hero_slide3_btn1') }}</a>
                                <a href="#packs" class="btn btn-outline-light rounded-pill py-3 px-5 animated slideInRight">{{ __('messages.home.hero_slide3_btn2') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ==================== HERO CAROUSEL END ==================== -->


    <!-- ==================== STATS / IMPACT COUNTERS ==================== -->
    <div class="container-xxl py-5" id="impact">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.1s">
                    <div class="p-4 bg-light rounded shadow-sm h-100">
                        <div class="btn-lg-square bg-primary rounded-circle mx-auto mb-3">
                            <i class="fa fa-bolt text-white fs-4"></i>
                        </div>
                        <h2 class="display-6 fw-bold mb-1" data-toggle="counter-up">12480</h2>
                        <span class="text-primary fw-bold">{{ __('messages.home.stat_kwh') }}</span>
                        <p class="text-muted small mt-2 mb-0">{{ __('messages.home.stat_kwh_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.3s">
                    <div class="p-4 bg-light rounded shadow-sm h-100">
                        <div class="btn-lg-square bg-primary rounded-circle mx-auto mb-3">
                            <i class="fa fa-leaf text-white fs-4"></i>
                        </div>
                        <h2 class="display-6 fw-bold mb-1"><span data-toggle="counter-up">8</span>.2 t</h2>
                        <span class="text-primary fw-bold">{{ __('messages.home.stat_co2') }}</span>
                        <p class="text-muted small mt-2 mb-0">{{ __('messages.home.stat_co2_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.5s">
                    <div class="p-4 bg-light rounded shadow-sm h-100">
                        <div class="btn-lg-square bg-primary rounded-circle mx-auto mb-3">
                            <i class="fa fa-users text-white fs-4"></i>
                        </div>
                        <h2 class="display-6 fw-bold mb-1" data-toggle="counter-up">3150</h2>
                        <span class="text-primary fw-bold">{{ __('messages.home.stat_members') }}</span>
                        <p class="text-muted small mt-2 mb-0">{{ __('messages.home.stat_members_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.7s">
                    <div class="p-4 bg-light rounded shadow-sm h-100">
                        <div class="btn-lg-square bg-primary rounded-circle mx-auto mb-3">
                            <i class="fa fa-solar-panel text-white fs-4"></i>
                        </div>
                        <h2 class="display-6 fw-bold mb-1" data-toggle="counter-up">940</h2>
                        <span class="text-primary fw-bold">{{ __('messages.home.stat_equipment') }}</span>
                        <p class="text-muted small mt-2 mb-0">{{ __('messages.home.stat_equipment_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ==================== STATS END ==================== -->


    <!-- ==================== ABOUT / NOTRE MISSION ==================== -->
    <div class="container-fluid bg-light overflow-hidden my-5 px-lg-0" id="about">
        <div class="container about px-lg-0">
            <div class="row g-0 mx-lg-0 align-items-center">
                <div class="col-lg-6 ps-lg-0 wow fadeIn" data-wow-delay="0.1s" style="min-height: 420px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute img-fluid w-100 h-100" src="{{ asset('solartec/img/about.jpg') }}" style="object-fit: cover;" alt="{{ __('messages.home.about_alt') }}">
                    </div>
                </div>
                <div class="col-lg-6 about-text py-5 wow fadeIn" data-wow-delay="0.5s">
                    <div class="p-lg-5 pe-lg-0">
                        <h6 class="text-primary text-uppercase fw-bold">{{ __('messages.home.mission_badge') }}</h6>
                        <h2 class="mb-4">{{ __('messages.home.mission_title') }}</h2>
                        <p class="text-muted">{{ __('messages.home.mission_desc') }}</p>
                        
                        <div class="row g-3 mt-2">
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-check-circle text-primary fs-5 me-3"></i>
                                    <span>{{ __('messages.home.feature_deposit') }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-check-circle text-primary fs-5 me-3"></i>
                                    <span>{{ __('messages.home.feature_inspection') }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-check-circle text-primary fs-5 me-3"></i>
                                    <span>{{ __('messages.home.feature_certified') }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-check-circle text-primary fs-5 me-3"></i>
                                    <span>{{ __('messages.home.feature_hubs') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mt-4 pt-2">
                            <a href="#how-it-works" class="btn btn-primary rounded-pill py-3 px-5 me-4">{{ __('messages.home.hero_slide2_btn2') }}</a>
                            <div class="d-flex align-items-center">
                                <div class="btn-square bg-white rounded-circle shadow-sm me-3 text-center">
                                    <i class="fa fa-phone-alt text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">{{ __('messages.home.support_question') }}</small>
                                    <strong class="text-dark">{{ __('messages.home.support_7j') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ==================== ABOUT END ==================== -->


    <!-- ==================== HOW IT WORKS ==================== -->
    <div class="container-xxl py-5" id="how-it-works">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 650px;">
                <h6 class="text-primary text-uppercase fw-bold">{{ __('messages.home.how_badge') }}</h6>
                <h2 class="mb-4">{{ __('messages.home.how_title') }}</h2>
                <p class="text-muted">{{ __('messages.home.how_desc') }}</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card border-0 shadow-sm p-4 text-center h-100">
                        <div class="btn-lg-square bg-primary text-white rounded-circle mx-auto mb-3 fs-4 fw-bold">1</div>
                        <h5 class="mb-2">{{ __('messages.home.step1_title') }}</h5>
                        <p class="text-muted small">{{ __('messages.home.step1_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="card border-0 shadow-sm p-4 text-center h-100">
                        <div class="btn-lg-square bg-primary text-white rounded-circle mx-auto mb-3 fs-4 fw-bold">2</div>
                        <h5 class="mb-2">{{ __('messages.home.step2_title') }}</h5>
                        <p class="text-muted small">{{ __('messages.home.step2_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="card border-0 shadow-sm p-4 text-center h-100">
                        <div class="btn-lg-square bg-primary text-white rounded-circle mx-auto mb-3 fs-4 fw-bold">3</div>
                        <h5 class="mb-2">{{ __('messages.home.step3_title') }}</h5>
                        <p class="text-muted small">{{ __('messages.home.step3_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.7s">
                    <div class="card border-0 shadow-sm p-4 text-center h-100">
                        <div class="btn-lg-square bg-primary text-white rounded-circle mx-auto mb-3 fs-4 fw-bold">4</div>
                        <h5 class="mb-2">{{ __('messages.home.step4_title') }}</h5>
                        <p class="text-muted small">{{ __('messages.home.step4_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ==================== HOW IT WORKS END ==================== -->


    <!-- ==================== CATEGORIES / SERVICES ==================== -->
    <div class="container-xxl py-5" id="categories">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 650px;">
                <h6 class="text-primary text-uppercase fw-bold">{{ __('messages.home.cat_badge') }}</h6>
                <h2 class="mb-4">{{ __('messages.home.cat_title') }}</h2>
                <p class="text-muted">{{ __('messages.home.cat_desc') }}</p>
            </div>
            <div class="row g-4">
                <!-- Catégorie 1: Panneaux solaires portables -->
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item rounded overflow-hidden shadow-sm h-100">
                        <img class="img-fluid" src="{{ asset('solartec/img/img-600x400-1.jpg') }}" alt="{{ __('messages.home.cat_solar_panels') }}">
                        <div class="position-relative p-4 pt-0">
                            <div class="service-icon">
                                <i class="fa fa-solar-panel fa-3x"></i>
                            </div>
                            <h4 class="mb-3">{{ __('messages.home.cat_solar_panels') }}</h4>
                            <p class="text-muted">{{ __('messages.home.cat_solar_panels_desc') }}</p>
                            <a class="small fw-bold text-primary" href="{{ route('marketplace', ['category' => 'panneaux-solaires-portables']) }}">{{ __('messages.home.cat_view_ads') }} <i class="fa fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} ms-2"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Catégorie 2: Micro-éoliennes nomades -->
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="service-item rounded overflow-hidden shadow-sm h-100">
                        <img class="img-fluid" src="{{ asset('solartec/img/img-600x400-2.jpg') }}" alt="{{ __('messages.home.cat_wind') }}">
                        <div class="position-relative p-4 pt-0">
                            <div class="service-icon">
                                <i class="fa fa-wind fa-3x"></i>
                            </div>
                            <h4 class="mb-3">{{ __('messages.home.cat_wind') }}</h4>
                            <p class="text-muted">{{ __('messages.home.cat_wind_desc') }}</p>
                            <a class="small fw-bold text-primary" href="{{ route('marketplace', ['category' => 'micro-eoliennes']) }}">{{ __('messages.home.cat_view_ads') }} <i class="fa fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} ms-2"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Catégorie 3: Batteries & Stations d'énergie -->
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="service-item rounded overflow-hidden shadow-sm h-100">
                        <img class="img-fluid" src="{{ asset('solartec/img/img-600x400-3.jpg') }}" alt="{{ __('messages.home.cat_batteries') }}">
                        <div class="position-relative p-4 pt-0">
                            <div class="service-icon">
                                <i class="fa fa-car-battery fa-3x"></i>
                            </div>
                            <h4 class="mb-3">{{ __('messages.home.cat_batteries') }}</h4>
                            <p class="text-muted">{{ __('messages.home.cat_batteries_desc') }}</p>
                            <a class="small fw-bold text-primary" href="{{ route('marketplace', ['category' => 'stations-energie-batteries']) }}">{{ __('messages.home.cat_view_ads') }} <i class="fa fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} ms-2"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Catégorie 4: Convertisseurs & Onduleurs -->
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item rounded overflow-hidden shadow-sm h-100">
                        <img class="img-fluid" src="{{ asset('solartec/img/img-600x400-4.jpg') }}" alt="{{ __('messages.home.cat_inverters') }}">
                        <div class="position-relative p-4 pt-0">
                            <div class="service-icon">
                                <i class="fa fa-plug fa-3x"></i>
                            </div>
                            <h4 class="mb-3">{{ __('messages.home.cat_inverters') }}</h4>
                            <p class="text-muted">{{ __('messages.home.cat_inverters_desc') }}</p>
                            <a class="small fw-bold text-primary" href="{{ route('marketplace', ['category' => 'onduleurs-nomades']) }}">{{ __('messages.home.cat_view_ads') }} <i class="fa fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} ms-2"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Catégorie 5: Kits solaires combinés -->
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="service-item rounded overflow-hidden shadow-sm h-100">
                        <img class="img-fluid" src="{{ asset('solartec/img/img-600x400-5.jpg') }}" alt="{{ __('messages.home.cat_packs') }}">
                        <div class="position-relative p-4 pt-0">
                            <div class="service-icon">
                                <i class="fa fa-boxes fa-3x"></i>
                            </div>
                            <h4 class="mb-3">{{ __('messages.home.cat_packs') }}</h4>
                            <p class="text-muted">{{ __('messages.home.cat_packs_desc') }}</p>
                            <a class="small fw-bold text-primary" href="#packs">{{ __('messages.home.cat_view_packs') }} <i class="fa fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} ms-2"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Catégorie 6: Hubs & Relais locaux -->
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="service-item rounded overflow-hidden shadow-sm h-100">
                        <img class="img-fluid" src="{{ asset('solartec/img/img-600x400-6.jpg') }}" alt="{{ __('messages.home.cat_hubs') }}">
                        <div class="position-relative p-4 pt-0">
                            <div class="service-icon">
                                <i class="fa fa-map-marked-alt fa-3x"></i>
                            </div>
                            <h4 class="mb-3">{{ __('messages.home.cat_hubs') }}</h4>
                            <p class="text-muted">{{ __('messages.home.cat_hubs_desc') }}</p>
                            <a class="small fw-bold text-primary" href="{{ route('hubs.index') }}">{{ __('messages.home.cat_find_hub') }} <i class="fa fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} ms-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ==================== CATEGORIES END ==================== -->


    <!-- ==================== FEATURED EQUIPMENT LISTINGS ==================== -->
    <div class="container-xxl py-5" id="equipment">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 650px;">
                <h6 class="text-primary text-uppercase fw-bold">{{ __('messages.home.feat_badge') }}</h6>
                <h2 class="mb-4">{{ __('messages.home.feat_title') }}</h2>
                <p class="text-muted">{{ __('messages.home.feat_desc') }}</p>
            </div>

            <div class="row g-4">
                <!-- Matériel 1 : EcoFlow Delta 2 -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card equipment-card border-0 shadow-sm h-100">
                        <div class="position-relative">
                            <img class="card-img-top" src="{{ asset('solartec/img/img-600x400-3.jpg') }}" alt="{{ __('messages.home.feat1_title') }}" style="height: 220px; object-fit: cover;">
                            <span class="badge bg-success position-absolute top-0 end-0 m-3 px-3 py-2">{{ __('messages.marketplace.available_today') }}</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-light text-primary fw-bold">{{ __('messages.home.feat1_spec') }}</span>
                                <span class="small text-muted"><i class="fa fa-map-marker-alt text-danger me-1"></i> {{ __('messages.home.feat1_dist') }}</span>
                            </div>
                            <h4 class="card-title mb-2">{{ __('messages.home.feat1_title') }}</h4>
                            <p class="card-text text-muted small">{{ __('messages.home.feat1_desc') }}</p>
                            <div class="d-flex align-items-center mb-3">
                                <div class="text-warning me-2">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star-half-alt"></i>
                                </div>
                                <span class="small fw-bold">4.96</span>
                                <span class="small text-muted ms-1">{{ __('messages.home.feat1_reviews') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <div>
                                    <span class="fs-4 fw-bold text-primary">32 €</span>
                                    <span class="text-muted small"> {{ __('messages.marketplace.per_day') }}</span>
                                </div>
                                <a href="{{ route('register') }}" class="btn btn-sm btn-primary rounded-pill px-3 py-2">
                                    <i class="fa fa-calendar-check me-1"></i> {{ __('messages.marketplace.reserve') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Matériel 2 : SolarSaga 200W Panel -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="card equipment-card border-0 shadow-sm h-100">
                        <div class="position-relative">
                            <img class="card-img-top" src="{{ asset('solartec/img/img-600x400-1.jpg') }}" alt="{{ __('messages.home.feat2_title') }}" style="height: 220px; object-fit: cover;">
                            <span class="badge bg-success position-absolute top-0 end-0 m-3 px-3 py-2">{{ __('messages.marketplace.available_today') }}</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-light text-primary fw-bold">{{ __('messages.home.feat2_spec') }}</span>
                                <span class="small text-muted"><i class="fa fa-map-marker-alt text-danger me-1"></i> {{ __('messages.home.feat2_dist') }}</span>
                            </div>
                            <h4 class="card-title mb-2">{{ __('messages.home.feat2_title') }}</h4>
                            <p class="card-text text-muted small">{{ __('messages.home.feat2_desc') }}</p>
                            <div class="d-flex align-items-center mb-3">
                                <div class="text-warning me-2">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                                <span class="small fw-bold">4.91</span>
                                <span class="small text-muted ms-1">{{ __('messages.home.feat2_reviews') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <div>
                                    <span class="fs-4 fw-bold text-primary">18 €</span>
                                    <span class="text-muted small"> {{ __('messages.marketplace.per_day') }}</span>
                                </div>
                                <a href="{{ route('register') }}" class="btn btn-sm btn-primary rounded-pill px-3 py-2">
                                    <i class="fa fa-calendar-check me-1"></i> {{ __('messages.marketplace.reserve') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Matériel 3 : Automaxx Wind Kit 400W -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="card equipment-card border-0 shadow-sm h-100">
                        <div class="position-relative">
                            <img class="card-img-top" src="{{ asset('solartec/img/img-600x400-2.jpg') }}" alt="{{ __('messages.home.feat3_title') }}" style="height: 220px; object-fit: cover;">
                            <span class="badge bg-info text-white position-absolute top-0 end-0 m-3 px-3 py-2">{{ __('messages.marketplace.tomorrow') }}</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-light text-primary fw-bold">{{ __('messages.home.feat3_spec') }}</span>
                                <span class="small text-muted"><i class="fa fa-map-marker-alt text-danger me-1"></i> {{ __('messages.home.feat3_dist') }}</span>
                            </div>
                            <h4 class="card-title mb-2">{{ __('messages.home.feat3_title') }}</h4>
                            <p class="card-text text-muted small">{{ __('messages.home.feat3_desc') }}</p>
                            <div class="d-flex align-items-center mb-3">
                                <div class="text-warning me-2">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star-half-alt"></i>
                                </div>
                                <span class="small fw-bold">4.88</span>
                                <span class="small text-muted ms-1">{{ __('messages.home.feat3_reviews') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <div>
                                    <span class="fs-4 fw-bold text-primary">25 €</span>
                                    <span class="text-muted small"> {{ __('messages.marketplace.per_day') }}</span>
                                </div>
                                <a href="{{ route('register') }}" class="btn btn-sm btn-primary rounded-pill px-3 py-2">
                                    <i class="fa fa-calendar-check me-1"></i> {{ __('messages.marketplace.reserve') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Explore Marketplace CTA -->
            <div class="text-center mt-5">
                <a href="{{ route('marketplace') }}" class="btn btn-primary rounded-pill py-3 px-5 shadow-sm" style="font-weight: 700; font-size: 1.05rem;">
                    <i class="fa fa-th-large me-2"></i> {{ __('messages.home.feat_explore_cta') }} {!! app()->getLocale() === 'ar' ? '&larr;' : '&rarr;' !!}
                </a>
            </div>
        </div>
    </div>
    <!-- ==================== FEATURED EQUIPMENT END ==================== -->


    <!-- ==================== PACKS & KITS ==================== -->
    <div class="container-xxl py-5 bg-light my-5" id="packs">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 650px;">
                <h6 class="text-primary text-uppercase fw-bold">{{ __('messages.home.packs_badge') }}</h6>
                <h2 class="mb-4">{{ __('messages.home.packs_title') }}</h2>
                <p class="text-muted">{{ __('messages.home.packs_desc') }}</p>
            </div>

            <div class="row g-4">
                <!-- Pack 1 -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card border-0 shadow-sm p-4 h-100 rounded-3">
                        <div class="badge bg-warning text-dark align-self-start mb-3 px-3 py-2 fw-bold">{{ __('messages.home.pack1_badge') }}</div>
                        <h4>{{ __('messages.home.pack1_title') }}</h4>
                        <p class="text-muted small">{{ __('messages.home.pack1_desc') }}</p>
                        <hr>
                        <ul class="list-unstyled mb-4 small text-secondary">
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> {{ __('messages.home.pack1_item1') }}</li>
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> {{ __('messages.home.pack1_item2') }}</li>
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> {{ __('messages.home.pack1_item3') }}</li>
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> {{ __('messages.home.pack1_item4') }}</li>
                        </ul>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fs-4 fw-bold text-primary">42 €</span>
                                <span class="text-muted small"> {{ __('messages.marketplace.per_day') }}</span>
                            </div>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary rounded-pill px-3">{{ __('messages.kits.reserve_pack') }}</a>
                        </div>
                    </div>
                </div>

                <!-- Pack 2 -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="card border-primary border-2 shadow p-4 h-100 rounded-3 position-relative">
                        <span class="badge bg-primary text-white position-absolute top-0 end-0 m-3 px-3 py-2">{{ __('messages.home.pack2_popular') }}</span>
                        <div class="badge bg-success text-white align-self-start mb-3 px-3 py-2 fw-bold">{{ __('messages.home.pack2_badge') }}</div>
                        <h4>{{ __('messages.home.pack2_title') }}</h4>
                        <p class="text-muted small">{{ __('messages.home.pack2_desc') }}</p>
                        <hr>
                        <ul class="list-unstyled mb-4 small text-secondary">
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> {{ __('messages.home.pack2_item1') }}</li>
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> {{ __('messages.home.pack2_item2') }}</li>
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> {{ __('messages.home.pack2_item3') }}</li>
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> {{ __('messages.home.pack2_item4') }}</li>
                        </ul>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fs-4 fw-bold text-primary">58 €</span>
                                <span class="text-muted small"> {{ __('messages.marketplace.per_day') }}</span>
                            </div>
                            <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-3">{{ __('messages.kits.reserve_pack') }}</a>
                        </div>
                    </div>
                </div>

                <!-- Pack 3 -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="card border-0 shadow-sm p-4 h-100 rounded-3">
                        <div class="badge bg-secondary text-white align-self-start mb-3 px-3 py-2 fw-bold">{{ __('messages.home.pack3_badge') }}</div>
                        <h4>{{ __('messages.home.pack3_title') }}</h4>
                        <p class="text-muted small">{{ __('messages.home.pack3_desc') }}</p>
                        <hr>
                        <ul class="list-unstyled mb-4 small text-secondary">
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> {{ __('messages.home.pack3_item1') }}</li>
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> {{ __('messages.home.pack3_item2') }}</li>
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> {{ __('messages.home.pack3_item3') }}</li>
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> {{ __('messages.home.pack3_item4') }}</li>
                        </ul>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fs-4 fw-bold text-primary">50 €</span>
                                <span class="text-muted small"> {{ __('messages.marketplace.per_day') }}</span>
                            </div>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary rounded-pill px-3">{{ __('messages.kits.reserve_pack') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ==================== PACKS END ==================== -->


    <!-- ==================== TESTIMONIALS ==================== -->
    <div class="container-xxl py-5" id="testimonials">
        <span id="community"></span>
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 650px;">
                <h6 class="text-primary text-uppercase fw-bold">{{ __('messages.home.testi_badge') }}</h6>
                <h2 class="mb-4">{{ __('messages.home.testi_title') }}</h2>
                <p class="text-muted">{{ __('messages.home.testi_desc') }}</p>
            </div>
            <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
                <div class="testimonial-item text-center p-4">
                    <div class="testimonial-img position-relative">
                        <img class="img-fluid rounded-circle mx-auto mb-4" src="{{ asset('solartec/img/testimonial-1.jpg') }}" alt="{{ __('messages.home.testi1_name') }}">
                        <div class="btn-square bg-primary rounded-circle">
                            <i class="fa fa-quote-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-white"></i>
                        </div>
                    </div>
                    <div class="testimonial-text text-center rounded p-4 shadow-sm bg-light">
                        <p class="text-muted">{{ __('messages.home.testi1_quote') }}</p>
                        <h5 class="mb-1">{{ __('messages.home.testi1_name') }}</h5>
                        <span class="fst-italic text-primary small">{{ __('messages.home.testi1_role') }}</span>
                    </div>
                </div>

                <div class="testimonial-item text-center p-4">
                    <div class="testimonial-img position-relative">
                        <img class="img-fluid rounded-circle mx-auto mb-4" src="{{ asset('solartec/img/testimonial-2.jpg') }}" alt="{{ __('messages.home.testi2_name') }}">
                        <div class="btn-square bg-primary rounded-circle">
                            <i class="fa fa-quote-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-white"></i>
                        </div>
                    </div>
                    <div class="testimonial-text text-center rounded p-4 shadow-sm bg-light">
                        <p class="text-muted">{{ __('messages.home.testi2_quote') }}</p>
                        <h5 class="mb-1">{{ __('messages.home.testi2_name') }}</h5>
                        <span class="fst-italic text-primary small">{{ __('messages.home.testi2_role') }}</span>
                    </div>
                </div>

                <div class="testimonial-item text-center p-4">
                    <div class="testimonial-img position-relative">
                        <img class="img-fluid rounded-circle mx-auto mb-4" src="{{ asset('solartec/img/testimonial-3.jpg') }}" alt="{{ __('messages.home.testi3_name') }}">
                        <div class="btn-square bg-primary rounded-circle">
                            <i class="fa fa-quote-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-white"></i>
                        </div>
                    </div>
                    <div class="testimonial-text text-center rounded p-4 shadow-sm bg-light">
                        <p class="text-muted">{{ __('messages.home.testi3_quote') }}</p>
                        <h5 class="mb-1">{{ __('messages.home.testi3_name') }}</h5>
                        <span class="fst-italic text-primary small">{{ __('messages.home.testi3_role') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ==================== TESTIMONIALS END ==================== -->


    <!-- ==================== CALL TO ACTION / BANNER ==================== -->
    <div class="container-fluid bg-primary text-white py-5 my-5 wow fadeIn" data-wow-delay="0.1s" id="contact">
        <span id="faq"></span>
        <div class="container text-center py-4">
            <h2 class="display-6 text-white mb-3">{{ __('messages.home.cta_title') }}</h2>
            <p class="lead text-white-50 mx-auto mb-4" style="max-width: 700px;">
                {{ __('messages.home.cta_desc') }}
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('register') }}" class="btn btn-warning rounded-pill py-3 px-5 fw-bold text-dark shadow">
                    <i class="fa fa-plus-circle me-2"></i>{{ __('messages.home.cta_btn') }}
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill py-3 px-5 fw-bold">
                    {{ __('messages.home.cta_btn2') }}
                </a>
            </div>
        </div>
    </div>
    <!-- ==================== CALL TO ACTION END ==================== -->

@endsection
