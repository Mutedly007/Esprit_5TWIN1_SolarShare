{{--
  SolarShare Admin Dashboard (Faithful reproduction of FreeDash original layout)
  Adheres strictly to Blade inheritance:
  - @extends('layouts.admin')
  - @section('title', ...)
  - @section('content')
--}}
@extends('layouts.admin')

@section('title', 'Dashboard — FreeDash SolarShare')

@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle (Matching FreeDash Mockup) -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    Good Morning {{ auth()->user()->name }}!
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item text-muted active" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 align-self-center">
                <div class="customize-input float-end">
                    <span class="custom-select custom-select-set form-control bg-white border-0 shadow-sm px-3 py-2 text-dark font-weight-medium" style="border-radius: 20px; box-shadow: 0 0 25px 0 rgba(154, 161, 171, 0.18) !important; font-size: 0.88rem;">
                        {{ date('M d') }} <i data-feather="chevron-down" class="svg-icon ms-1 text-muted" style="width: 14px; height: 14px;"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">

        <!-- ============================================================== -->
        <!-- Row 1: 4 KPI Cards (Identical to FreeDash original) -->
        <!-- ============================================================== -->
        <div class="row">
            <!-- Card 1: New Clients -->
            <div class="col-sm-6 col-lg-3">
                <div class="card border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium">236</h2>
                                    <span class="badge bg-primary font-12 text-white font-weight-medium rounded-pill ms-2" style="background-color: #5f76e8 !important;">+18.33%</span>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">New Clients</h6>
                            </div>
                            <div class="ms-auto">
                                <span class="text-muted opacity-7">
                                    <i data-feather="user-plus" class="text-muted" style="width: 28px; height: 28px;"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Earnings of Month -->
            <div class="col-sm-6 col-lg-3">
                <div class="card border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 font-weight-medium">$18,306</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Earnings of Month</h6>
                            </div>
                            <div class="ms-auto">
                                <span class="text-muted opacity-7">
                                    <i data-feather="dollar-sign" class="text-muted" style="width: 28px; height: 28px;"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: New Projects -->
            <div class="col-sm-6 col-lg-3">
                <div class="card border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium">1538</h2>
                                    <span class="badge font-12 text-white font-weight-medium rounded-pill ms-2" style="background-color: #ff4f70 !important;">-18.33%</span>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">New Projects</h6>
                            </div>
                            <div class="ms-auto">
                                <span class="text-muted opacity-7">
                                    <i data-feather="file-text" class="text-muted" style="width: 28px; height: 28px;"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4: Projects -->
            <div class="col-sm-6 col-lg-3">
                <div class="card border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 font-weight-medium">864</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Projects</h6>
                            </div>
                            <div class="ms-auto">
                                <span class="text-muted opacity-7">
                                    <i data-feather="globe" class="text-muted" style="width: 28px; height: 28px;"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- Row 2: 3 Signature Columns (Total Sales / Net Income / Location) -->
        <!-- ============================================================== -->
        <div class="row">
            <!-- Column 1: Total Sales (Donut Chart) -->
            <div class="col-md-12 col-lg-4">
                <div class="card border-0 h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <h4 class="card-title text-dark font-weight-medium mb-3">Total Sales</h4>
                        
                        <!-- Donut Chart -->
                        <div id="campaign-v2" class="mt-2" style="height: 250px; width: 100%;"></div>

                        <!-- Legend List matching FreeDash -->
                        <ul class="list-style-none mb-0 mt-3 pt-2">
                            <li class="d-flex align-items-center justify-content-between py-2">
                                <div class="d-flex align-items-center">
                                    <span class="d-inline-block rounded-circle me-2" style="width: 10px; height: 10px; background-color: #5f76e8;"></span>
                                    <span class="text-muted font-14">Direct Sales</span>
                                </div>
                                <span class="text-dark font-weight-medium font-14">$2346</span>
                            </li>
                            <li class="d-flex align-items-center justify-content-between py-2">
                                <div class="d-flex align-items-center">
                                    <span class="d-inline-block rounded-circle me-2" style="width: 10px; height: 10px; background-color: #ff4f70;"></span>
                                    <span class="text-muted font-14">Referral Sales</span>
                                </div>
                                <span class="text-dark font-weight-medium font-14">$2108</span>
                            </li>
                            <li class="d-flex align-items-center justify-content-between py-2">
                                <div class="d-flex align-items-center">
                                    <span class="d-inline-block rounded-circle me-2" style="width: 10px; height: 10px; background-color: #01caf1;"></span>
                                    <span class="text-muted font-14">Affiliate Sales</span>
                                </div>
                                <span class="text-dark font-weight-medium font-14">$1204</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Column 2: Net Income (Bar Chart) -->
            <div class="col-md-12 col-lg-4">
                <div class="card border-0 h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <h4 class="card-title text-dark font-weight-medium mb-3">Net Income</h4>

                        <!-- Chartist Bar Chart -->
                        <div class="net-income mt-3 position-relative" style="height: 260px;"></div>

                        <div class="text-center text-muted mt-4 mb-2">
                            <span class="font-14">Sales for this month</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column 3: Earning by Location (Vector Map & Bars) -->
            <div class="col-md-12 col-lg-4">
                <div class="card border-0 h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <h4 class="card-title text-dark font-weight-medium mb-3">Earning by Location</h4>

                        <!-- World Map -->
                        <div id="visitbylocate" style="height: 190px;"></div>

                        <!-- Progress Bars by Country matching mockup -->
                        <div class="mt-4 pt-2">
                            <div class="row mb-3 align-items-center">
                                <div class="col-4 text-truncate text-muted font-14">India</div>
                                <div class="col-5">
                                    <div class="progress" style="height: 5px; border-radius: 10px;">
                                        <div class="progress-bar" role="progressbar" style="width: 28%; background-color: #5f76e8;"></div>
                                    </div>
                                </div>
                                <div class="col-3 text-end font-weight-medium text-dark font-14">28%</div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <div class="col-4 text-truncate text-muted font-14">UK</div>
                                <div class="col-5">
                                    <div class="progress" style="height: 5px; border-radius: 10px;">
                                        <div class="progress-bar" role="progressbar" style="width: 21%; background-color: #ff4f70;"></div>
                                    </div>
                                </div>
                                <div class="col-3 text-end font-weight-medium text-dark font-14">21%</div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <div class="col-4 text-truncate text-muted font-14">USA</div>
                                <div class="col-5">
                                    <div class="progress" style="height: 5px; border-radius: 10px;">
                                        <div class="progress-bar" role="progressbar" style="width: 18%; background-color: #01caf1;"></div>
                                    </div>
                                </div>
                                <div class="col-3 text-end font-weight-medium text-dark font-14">18%</div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-4 text-truncate text-muted font-14">China</div>
                                <div class="col-5">
                                    <div class="progress" style="height: 5px; border-radius: 10px;">
                                        <div class="progress-bar" role="progressbar" style="width: 12%; background-color: #22ca80;"></div>
                                    </div>
                                </div>
                                <div class="col-3 text-end font-weight-medium text-dark font-14">12%</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- Row 3: Recent Bookings & Rentals Table -->
        <!-- ============================================================== -->
        <div class="row mt-4" id="rentals">
            <div class="col-12">
                <div class="card border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h4 class="card-title text-dark font-weight-medium mb-1">Recent SolarShare Rentals</h4>
                                <h6 class="card-subtitle text-muted">Dernières réservations d'équipements renouvelables et contrats en cours</h6>
                            </div>
                            <span class="badge text-white font-12 px-3 py-2 rounded-pill" style="background-color: #5f76e8;">Module 2 : Locations</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table no-wrap v-middle mb-0">
                                <thead>
                                    <tr class="border-0">
                                        <th class="border-0 font-14 font-weight-medium text-muted">Locataire</th>
                                        <th class="border-0 font-14 font-weight-medium text-muted">Équipement</th>
                                        <th class="border-0 font-14 font-weight-medium text-muted">Dates</th>
                                        <th class="border-0 font-14 font-weight-medium text-muted">Caution Bloquée</th>
                                        <th class="border-0 font-14 font-weight-medium text-muted">Statut</th>
                                        <th class="border-0 font-14 font-weight-medium text-muted text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border-top-0 px-2 py-3">
                                            <div class="d-flex no-block align-items-center">
                                                <div class="me-3">
                                                    <span class="badge text-white rounded-circle p-2" style="background-color: #5f76e8;">CL</span>
                                                </div>
                                                <div>
                                                    <h5 class="text-dark mb-0 font-14 font-weight-medium">Camille Laurent</h5>
                                                    <span class="text-muted font-12">camille.l@example.com</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="border-top-0 text-muted px-2 py-3 font-14">
                                            <strong class="text-dark">EcoFlow Delta 2</strong> (1024Wh)
                                        </td>
                                        <td class="border-top-0 px-2 py-3">
                                            <span class="text-dark font-14">06 Oct - 10 Oct 2026</span>
                                            <small class="d-block text-muted">4 jours (128 €)</small>
                                        </td>
                                        <td class="border-top-0 px-2 py-3">
                                            <span class="badge font-14" style="background: #edf2f9; color: #1c2d41;">300 € (Vérifiée)</span>
                                        </td>
                                        <td class="border-top-0 px-2 py-3">
                                            <span class="badge text-white" style="background-color: #22ca80;">En cours</span>
                                        </td>
                                        <td class="border-top-0 text-end px-2 py-3">
                                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3">Détails</button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="px-2 py-3">
                                            <div class="d-flex no-block align-items-center">
                                                <div class="me-3">
                                                    <span class="badge text-white rounded-circle p-2" style="background-color: #ff4f70;">MD</span>
                                                </div>
                                                <div>
                                                    <h5 class="text-dark mb-0 font-14 font-weight-medium">Marc Dubois</h5>
                                                    <span class="text-muted font-12">marc.dubois@example.com</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-muted px-2 py-3 font-14">
                                            <strong class="text-dark">SolarSaga 200W Panel</strong>
                                        </td>
                                        <td class="px-2 py-3">
                                            <span class="text-dark font-14">08 Oct - 12 Oct 2026</span>
                                            <small class="d-block text-muted">4 jours (72 €)</small>
                                        </td>
                                        <td class="px-2 py-3">
                                            <span class="badge font-14" style="background: #edf2f9; color: #1c2d41;">150 € (Vérifiée)</span>
                                        </td>
                                        <td class="px-2 py-3">
                                            <span class="badge text-white" style="background-color: #5f76e8;">Confirmée</span>
                                        </td>
                                        <td class="text-end px-2 py-3">
                                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3">Détails</button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="px-2 py-3">
                                            <div class="d-flex no-block align-items-center">
                                                <div class="me-3">
                                                    <span class="badge text-white rounded-circle p-2" style="background-color: #01caf1;">SB</span>
                                                </div>
                                                <div>
                                                    <h5 class="text-dark mb-0 font-14 font-weight-medium">Sarah Ben Salem</h5>
                                                    <span class="text-muted font-12">sarah.b@example.com</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-muted px-2 py-3 font-14">
                                            <strong class="text-dark">Automaxx Wind Kit 400W</strong>
                                        </td>
                                        <td class="px-2 py-3">
                                            <span class="text-dark font-14">01 Oct - 04 Oct 2026</span>
                                            <small class="d-block text-muted">3 jours (75 €)</small>
                                        </td>
                                        <td class="px-2 py-3">
                                            <span class="badge font-14" style="background: #edf2f9; color: #1c2d41;">200 € (Restituée)</span>
                                        </td>
                                        <td class="px-2 py-3">
                                            <span class="badge text-white" style="background-color: #6c757d;">Terminée</span>
                                        </td>
                                        <td class="text-end px-2 py-3">
                                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3">Détails</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->

@endsection
