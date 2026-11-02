{{--
  SolarShare Landing Page (Solartec Template Implementation)
  Adheres strictly to Blade inheritance:
  - @extends('layouts.solartec')
  - @section('title', ...)
  - @section('content')
--}}
@extends('layouts.solartec')

@section('title', 'SolarShare — Partage & Location d\'Équipements Solaires et Éoliens')

@section('meta_description', 'Plateforme collaborative de location de panneaux solaires portables, batteries nomades et micro-éoliennes entre particuliers.')

@section('content')

    <!-- ==================== HERO CAROUSEL ==================== -->
    <div class="container-fluid p-0 pb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="owl-carousel header-carousel position-relative">
            <!-- Slide 1 -->
            <div class="owl-carousel-item position-relative" data-dot="<img src='{{ asset('solartec/img/carousel-1.jpg') }}'>">
                <img class="img-fluid" src="{{ asset('solartec/img/carousel-1.jpg') }}" alt="Énergie solaire partagée">
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
                <img class="img-fluid" src="{{ asset('solartec/img/carousel-2.jpg') }}" alt="Panneaux solaires portables">
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
                <img class="img-fluid" src="{{ asset('solartec/img/carousel-3.jpg') }}" alt="Générateurs et éoliennes portables">
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
    <div class="container-xxl py-5">
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
                        <img class="position-absolute img-fluid w-100 h-100" src="{{ asset('solartec/img/about.jpg') }}" style="object-fit: cover;" alt="Partage d'équipements solaires">
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
                        <img class="img-fluid" src="{{ asset('solartec/img/img-600x400-1.jpg') }}" alt="Panneaux solaires portables">
                        <div class="position-relative p-4 pt-0">
                            <div class="service-icon">
                                <i class="fa fa-solar-panel fa-3x"></i>
                            </div>
                            <h4 class="mb-3">{{ __('messages.home.cat_solar_panels') }}</h4>
                            <p class="text-muted">{{ __('messages.home.cat_solar_panels_desc') }}</p>
                            <a class="small fw-bold text-primary" href="{{ route('marketplace', ['category' => 'panneaux-solaires-portables']) }}">{{ __('messages.home.cat_view_ads') }} <i class="fa fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Catégorie 2: Micro-éoliennes nomades -->
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="service-item rounded overflow-hidden shadow-sm h-100">
                        <img class="img-fluid" src="{{ asset('solartec/img/img-600x400-2.jpg') }}" alt="Micro-éoliennes portables">
                        <div class="position-relative p-4 pt-0">
                            <div class="service-icon">
                                <i class="fa fa-wind fa-3x"></i>
                            </div>
                            <h4 class="mb-3">{{ __('messages.home.cat_wind') }}</h4>
                            <p class="text-muted">{{ __('messages.home.cat_wind_desc') }}</p>
                            <a class="small fw-bold text-primary" href="{{ route('marketplace', ['category' => 'micro-eoliennes']) }}">{{ __('messages.home.cat_view_ads') }} <i class="fa fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Catégorie 3: Batteries & Stations d'énergie -->
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="service-item rounded overflow-hidden shadow-sm h-100">
                        <img class="img-fluid" src="{{ asset('solartec/img/img-600x400-3.jpg') }}" alt="Batteries nomades">
                        <div class="position-relative p-4 pt-0">
                            <div class="service-icon">
                                <i class="fa fa-car-battery fa-3x"></i>
                            </div>
                            <h4 class="mb-3">{{ __('messages.home.cat_batteries') }}</h4>
                            <p class="text-muted">{{ __('messages.home.cat_batteries_desc') }}</p>
                            <a class="small fw-bold text-primary" href="{{ route('marketplace', ['category' => 'stations-energie-batteries']) }}">{{ __('messages.home.cat_view_ads') }} <i class="fa fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Catégorie 4: Convertisseurs & Onduleurs -->
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item rounded overflow-hidden shadow-sm h-100">
                        <img class="img-fluid" src="{{ asset('solartec/img/img-600x400-4.jpg') }}" alt="Onduleurs et régulateurs MPPT">
                        <div class="position-relative p-4 pt-0">
                            <div class="service-icon">
                                <i class="fa fa-plug fa-3x"></i>
                            </div>
                            <h4 class="mb-3">Régulateurs MPPT & Onduleurs</h4>
                            <p class="text-muted">Boîtiers d'optimisation de charge solaire et convertisseurs pur sinus 12V/24V vers 230V.</p>
                            <a class="small fw-bold text-primary" href="{{ route('marketplace', ['category' => 'onduleurs-nomades']) }}">Voir les annonces <i class="fa fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Catégorie 5: Kits solaires combinés -->
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="service-item rounded overflow-hidden shadow-sm h-100">
                        <img class="img-fluid" src="{{ asset('solartec/img/img-600x400-5.jpg') }}" alt="Kits solaires autonomie">
                        <div class="position-relative p-4 pt-0">
                            <div class="service-icon">
                                <i class="fa fa-boxes fa-3x"></i>
                            </div>
                            <h4 class="mb-3">Packs Complets Prêts à l'Emploi</h4>
                            <p class="text-muted">Ensembles comprenant panneaux solaires, câbles d'extension MC4 et station de stockage prête à brancher.</p>
                            <a class="small fw-bold text-primary" href="#packs">Voir les packs <i class="fa fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Catégorie 6: Hubs & Relais locaux -->
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="service-item rounded overflow-hidden shadow-sm h-100">
                        <img class="img-fluid" src="{{ asset('solartec/img/img-600x400-6.jpg') }}" alt="Points relais de partage">
                        <div class="position-relative p-4 pt-0">
                            <div class="service-icon">
                                <i class="fa fa-map-marked-alt fa-3x"></i>
                            </div>
                            <h4 class="mb-3">Hubs & Relais de Quartier</h4>
                            <p class="text-muted">Lieux de collecte associatifs et tiers-lieux partenaires pour déposer et récupérer le matériel sans contact direct.</p>
                            <a class="small fw-bold text-primary" href="#contact">Trouver un hub <i class="fa fa-arrow-right ms-2"></i></a>
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
                <h6 class="text-primary text-uppercase fw-bold">Disponibles Immédiatement</h6>
                <h2 class="mb-4">Équipements les Plus Loués en Ce Moment</h2>
                <p class="text-muted">Matériels de haute performance vérifiés par notre communauté d'adhérents.</p>
            </div>

            <div class="row g-4">
                <!-- Matériel 1 : EcoFlow Delta 2 -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card equipment-card border-0 shadow-sm h-100">
                        <div class="position-relative">
                            <img class="card-img-top" src="{{ asset('solartec/img/img-600x400-3.jpg') }}" alt="EcoFlow Delta 2" style="height: 220px; object-fit: cover;">
                            <span class="badge bg-success position-absolute top-0 end-0 m-3 px-3 py-2">Disponible aujourd'hui</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-light text-primary fw-bold">Capacité 1024Wh</span>
                                <span class="small text-muted"><i class="fa fa-map-marker-alt text-danger me-1"></i> À 1.2 km</span>
                            </div>
                            <h4 class="card-title mb-2">EcoFlow Delta 2</h4>
                            <p class="card-text text-muted small">Station d'énergie portable 1800W AC, sortie 220V pure sinus. Idéale télétravail nomade et campings.</p>
                            <div class="d-flex align-items-center mb-3">
                                <div class="text-warning me-2">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star-half-alt"></i>
                                </div>
                                <span class="small fw-bold">4.96</span>
                                <span class="small text-muted ms-1">(47 avis)</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <div>
                                    <span class="fs-4 fw-bold text-primary">32 €</span>
                                    <span class="text-muted small"> / jour</span>
                                </div>
                                <a href="{{ route('register') }}" class="btn btn-sm btn-primary rounded-pill px-3 py-2">
                                    <i class="fa fa-calendar-check me-1"></i> Réserver
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Matériel 2 : SolarSaga 200W Panel -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="card equipment-card border-0 shadow-sm h-100">
                        <div class="position-relative">
                            <img class="card-img-top" src="{{ asset('solartec/img/img-600x400-1.jpg') }}" alt="SolarSaga 200W" style="height: 220px; object-fit: cover;">
                            <span class="badge bg-success position-absolute top-0 end-0 m-3 px-3 py-2">Disponible aujourd'hui</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-light text-primary fw-bold">Puissance 200W</span>
                                <span class="small text-muted"><i class="fa fa-map-marker-alt text-danger me-1"></i> À 0.9 km</span>
                            </div>
                            <h4 class="card-title mb-2">Panneau Solaire SolarSaga 200W</h4>
                            <p class="card-text text-muted small">Panneau solaire pliable haute efficacité monocristalline avec pieds intégrés et adaptateurs universels.</p>
                            <div class="d-flex align-items-center mb-3">
                                <div class="text-warning me-2">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                                <span class="small fw-bold">4.91</span>
                                <span class="small text-muted ms-1">(33 avis)</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <div>
                                    <span class="fs-4 fw-bold text-primary">18 €</span>
                                    <span class="text-muted small"> / jour</span>
                                </div>
                                <a href="{{ route('register') }}" class="btn btn-sm btn-primary rounded-pill px-3 py-2">
                                    <i class="fa fa-calendar-check me-1"></i> Réserver
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Matériel 3 : Automaxx Wind Kit 400W -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="card equipment-card border-0 shadow-sm h-100">
                        <div class="position-relative">
                            <img class="card-img-top" src="{{ asset('solartec/img/img-600x400-2.jpg') }}" alt="Automaxx Wind Kit" style="height: 220px; object-fit: cover;">
                            <span class="badge bg-info text-white position-absolute top-0 end-0 m-3 px-3 py-2">Dispo demain</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-light text-primary fw-bold">Éolien 400W</span>
                                <span class="small text-muted"><i class="fa fa-map-marker-alt text-danger me-1"></i> À 2.4 km</span>
                            </div>
                            <h4 class="card-title mb-2">Kit Éolien Automaxx 400W</h4>
                            <p class="card-text text-muted small">Micro-éolienne silencieuse complète avec mât d'haubanage rapide et régulateur hybride 12V/24V.</p>
                            <div class="d-flex align-items-center mb-3">
                                <div class="text-warning me-2">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star-half-alt"></i>
                                </div>
                                <span class="small fw-bold">4.88</span>
                                <span class="small text-muted ms-1">(18 avis)</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <div>
                                    <span class="fs-4 fw-bold text-primary">25 €</span>
                                    <span class="text-muted small"> / jour</span>
                                </div>
                                <a href="{{ route('register') }}" class="btn btn-sm btn-primary rounded-pill px-3 py-2">
                                    <i class="fa fa-calendar-check me-1"></i> Réserver
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Explore Marketplace CTA -->
            <div class="text-center mt-5">
                <a href="{{ route('marketplace') }}" class="btn btn-primary rounded-pill py-3 px-5 shadow-sm" style="font-weight: 700; font-size: 1.05rem;">
                    <i class="fa fa-th-large me-2"></i> Explorer toute la Marketplace & Catégories &rarr;
                </a>
            </div>
        </div>
    </div>
    <!-- ==================== FEATURED EQUIPMENT END ==================== -->


    <!-- ==================== PACKS & KITS ==================== -->
    <div class="container-xxl py-5 bg-light my-5" id="packs">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 650px;">
                <h6 class="text-primary text-uppercase fw-bold">Solutions Clé-en-Main</h6>
                <h2 class="mb-4">Nos Packs Énergétiques Thématiques</h2>
                <p class="text-muted">Des ensembles cohérents combinant production solaire et stockage à tarif remisé.</p>
            </div>

            <div class="row g-4">
                <!-- Pack 1 -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card border-0 shadow-sm p-4 h-100 rounded-3">
                        <div class="badge bg-warning text-dark align-self-start mb-3 px-3 py-2 fw-bold">Pack Événement & Plein Air</div>
                        <h4>Pack Vanlife & Bivouac</h4>
                        <p class="text-muted small">Autonomie complète pour une escapade de plusieurs jours sans raccordement réseau.</p>
                        <hr>
                        <ul class="list-unstyled mb-4 small text-secondary">
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> 1x Batterie Nomade 1024Wh (Prise 220V)</li>
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> 2x Panneaux Solaires 100W pliables</li>
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> Câbles MC4 rallonge 5m & adaptateurs</li>
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> Sacoche étanche de transport fournie</li>
                        </ul>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fs-4 fw-bold text-primary">42 €</span>
                                <span class="text-muted small"> / jour</span>
                            </div>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary rounded-pill px-3">Réserver le pack</a>
                        </div>
                    </div>
                </div>

                <!-- Pack 2 -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="card border-primary border-2 shadow p-4 h-100 rounded-3 position-relative">
                        <span class="badge bg-primary text-white position-absolute top-0 end-0 m-3 px-3 py-2">Le plus populaire</span>
                        <div class="badge bg-success text-white align-self-start mb-3 px-3 py-2 fw-bold">Pack Grande Puissance</div>
                        <h4>Pack Chantier & Télétravail</h4>
                        <p class="text-muted small">Alimentez outillage électroportatif, ordinateurs et éclairages puissants en continu.</p>
                        <hr>
                        <ul class="list-unstyled mb-4 small text-secondary">
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> 1x Station d'énergie 2048Wh (2200W crête)</li>
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> 1x Panneau Solaire Rigide 400W</li>
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> Multiprise industrielle IP44</li>
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> Assistance technique au montage incluse</li>
                        </ul>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fs-4 fw-bold text-primary">58 €</span>
                                <span class="text-muted small"> / jour</span>
                            </div>
                            <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-3">Réserver le pack</a>
                        </div>
                    </div>
                </div>

                <!-- Pack 3 -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="card border-0 shadow-sm p-4 h-100 rounded-3">
                        <div class="badge bg-secondary text-white align-self-start mb-3 px-3 py-2 fw-bold">Pack Hybride 24h/24</div>
                        <h4>Pack Solaire + Éolien</h4>
                        <p class="text-muted small">Le soleil le jour, le vent la nuit : autonomie garantie même par météo changeante.</p>
                        <hr>
                        <ul class="list-unstyled mb-4 small text-secondary">
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> 1x Micro-Éolienne 400W avec mât</li>
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> 1x Panneau Solaire 200W pliable</li>
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> 1x Régulateur de charge hybride MPPT</li>
                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> Batterie 1200Wh avec onduleur pur sinus</li>
                        </ul>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fs-4 fw-bold text-primary">50 €</span>
                                <span class="text-muted small"> / jour</span>
                            </div>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary rounded-pill px-3">Réserver le pack</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ==================== PACKS END ==================== -->


    <!-- ==================== TESTIMONIALS ==================== -->
    <div class="container-xxl py-5" id="testimonials">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 650px;">
                <h6 class="text-primary text-uppercase fw-bold">Retours d'Expérience</h6>
                <h2 class="mb-4">Ce Que Dit la Communauté SolarShare</h2>
                <p class="text-muted">Découvrez les témoignages de propriétaires et d'utilisateurs qui partagent l'énergie propre.</p>
            </div>
            <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
                <div class="testimonial-item text-center p-4">
                    <div class="testimonial-img position-relative">
                        <img class="img-fluid rounded-circle mx-auto mb-4" src="{{ asset('solartec/img/testimonial-1.jpg') }}" alt="Témoignage SolarShare">
                        <div class="btn-square bg-primary rounded-circle">
                            <i class="fa fa-quote-left text-white"></i>
                        </div>
                    </div>
                    <div class="testimonial-text text-center rounded p-4 shadow-sm bg-light">
                        <p class="text-muted">« J'ai loué un kit EcoFlow avec panneau 200W pour une semaine de camping sauvage dans les Cévennes. Aucune odeur, aucun bruit de groupe électrogène, juste l'énergie du soleil ! »</p>
                        <h5 class="mb-1">Camille Laurent</h5>
                        <span class="fst-italic text-primary small">Locataire · Lyon</span>
                    </div>
                </div>

                <div class="testimonial-item text-center p-4">
                    <div class="testimonial-img position-relative">
                        <img class="img-fluid rounded-circle mx-auto mb-4" src="{{ asset('solartec/img/testimonial-2.jpg') }}" alt="Témoignage Prêteur">
                        <div class="btn-square bg-primary rounded-circle">
                            <i class="fa fa-quote-left text-white"></i>
                        </div>
                    </div>
                    <div class="testimonial-text text-center rounded p-4 shadow-sm bg-light">
                        <p class="text-muted">« Mes panneaux solaires pliables passaient 11 mois sur 12 dans mon placard. En les mettant sur SolarShare, ils sont déjà rentabilisés et ont dépanné plusieurs voisins artisans. »</p>
                        <h5 class="mb-1">Marc Dubois</h5>
                        <span class="fst-italic text-primary small">Prêteur certifié · Nantes</span>
                    </div>
                </div>

                <div class="testimonial-item text-center p-4">
                    <div class="testimonial-img position-relative">
                        <img class="img-fluid rounded-circle mx-auto mb-4" src="{{ asset('solartec/img/testimonial-3.jpg') }}" alt="Témoignage Éolien">
                        <div class="btn-square bg-primary rounded-circle">
                            <i class="fa fa-quote-left text-white"></i>
                        </div>
                    </div>
                    <div class="testimonial-text text-center rounded p-4 shadow-sm bg-light">
                        <p class="text-muted">« L'état des lieux numérique et la caution bloquée en ligne nous mettent en totale confiance. Une plateforme indispensable pour accélérer la transition énergétique ! »</p>
                        <h5 class="mb-1">Sarah Ben Salem</h5>
                        <span class="fst-italic text-primary small">Adhérente SolarShare · Tunis</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ==================== TESTIMONIALS END ==================== -->


    <!-- ==================== CALL TO ACTION / BANNER ==================== -->
    <div class="container-fluid bg-primary text-white py-5 my-5 wow fadeIn" data-wow-delay="0.1s" id="contact">
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
                    {{ __('messages.nav.login') }}
                </a>
            </div>
        </div>
    </div>
    <!-- ==================== CALL TO ACTION END ==================== -->

@endsection
