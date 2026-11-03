<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\RenterChatbotController;
use Illuminate\Support\Facades\Route;

// Multilingual language switcher (FR, EN, AR)
Route::get('/lang/{locale}', [LocalizationController::class, 'switch'])
    ->whereIn('locale', ['fr', 'en', 'ar'])
    ->name('lang.switch');

Route::get('/', function () {
    $featuredEquipment = [
        [
            'name' => 'EcoFlow Delta 2',
            'capacity' => '1024Wh',
            'status' => 'Available today',
            'status_style' => null,
            'dot_style' => null,
            'distance' => '1.2 miles away',
            'rating' => '4.96',
            'reviews' => 47,
            'price' => 32,
            'svg' => '<svg width="100" height="90" viewBox="0 0 100 90" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <rect x="15" y="15" width="70" height="60" rx="8" fill="#1E293B"/>
                  <rect x="25" y="26" width="50" height="18" rx="3" fill="#070F1A"/>
                  <text x="32" y="39" fill="#10B981" font-family="\'Sora\', sans-serif" font-size="9" font-weight="700">100%</text>
                  <circle cx="32" cy="58" r="4" fill="#475569"/>
                  <circle cx="48" cy="58" r="4" fill="#475569"/>
                  <rect x="62" y="54" width="12" height="8" rx="2" fill="#FFB020"/>
                </svg>',
        ],
        [
            'name' => 'SolarSaga 200W Panel',
            'capacity' => '200W',
            'status' => 'Available today',
            'status_style' => null,
            'dot_style' => null,
            'distance' => '0.9 miles away',
            'rating' => '4.91',
            'reviews' => 33,
            'price' => 18,
            'svg' => '<svg width="110" height="90" viewBox="0 0 110 90" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <rect x="10" y="20" width="40" height="55" rx="4" fill="#0F5C6B" stroke="#38BDF8" stroke-width="1.5"/>
                  <rect x="58" y="20" width="40" height="55" rx="4" fill="#0F5C6B" stroke="#38BDF8" stroke-width="1.5"/>
                  <line x1="10" y1="47" x2="50" y2="47" stroke="#38BDF8" stroke-width="1"/>
                  <line x1="58" y1="47" x2="98" y2="47" stroke="#38BDF8" stroke-width="1"/>
                  <circle cx="54" cy="47" r="3" fill="#FFB020"/>
                </svg>',
        ],
        [
            'name' => 'Automaxx Wind Kit',
            'capacity' => '400W',
            'status' => 'Tomorrow',
            'status_style' => 'color: var(--ss-deep-teal);',
            'dot_style' => 'background: var(--ss-deep-teal);',
            'distance' => '2.4 miles away',
            'rating' => '4.88',
            'reviews' => 18,
            'price' => 25,
            'svg' => '<svg width="100" height="90" viewBox="0 0 100 90" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <line x1="50" y1="35" x2="50" y2="80" stroke="#64748B" stroke-width="3"/>
                  <circle cx="50" cy="35" r="6" fill="#FFB020"/>
                  <path d="M50 35 L50 8 L53 18 Z" fill="#0F5C6B"/>
                  <path d="M50 35 L75 48 L65 42 Z" fill="#0F5C6B"/>
                  <path d="M50 35 L25 48 L35 42 Z" fill="#0F5C6B"/>
                </svg>',
        ],
        [
            'name' => 'BioLite SolarHome 620+',
            'capacity' => '33Wh/6W',
            'status' => 'Available today',
            'status_style' => null,
            'dot_style' => null,
            'distance' => '0.5 miles away',
            'rating' => '4.98',
            'reviews' => 62,
            'price' => 8,
            'svg' => '<svg width="100" height="90" viewBox="0 0 100 90" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <path d="M40 25 H60 L57 38 H43 Z" fill="#334155"/>
                  <path d="M43 38 L35 65 H65 L57 38 Z" fill="rgba(255, 176, 32, 0.3)" stroke="#FFB020" stroke-width="2"/>
                  <circle cx="50" cy="50" r="8" fill="#FFE3C2"/>
                  <rect x="33" y="65" width="34" height="10" rx="3" fill="#1E293B"/>
                </svg>',
        ],
    ];

    $stats = [
        [
            'target' => 12480,
            'value' => '12,480',
            'is_float' => false,
            'unit' => null,
            'label' => 'kWh Clean Energy Shared',
        ],
        [
            'target' => 8.2,
            'value' => '8.2',
            'is_float' => true,
            'unit' => 't',
            'label' => 'Tons CO₂ Emissions Avoided',
        ],
        [
            'target' => 3150,
            'value' => '3,150',
            'is_float' => false,
            'unit' => null,
            'label' => 'Active Neighborhood Members',
        ],
        [
            'target' => 940,
            'value' => '940',
            'is_float' => false,
            'unit' => null,
            'label' => 'Pieces of Equipment Circulating',
        ],
    ];

    return view('pages.front.solartec_home', [
        'featuredEquipment' => $featuredEquipment,
        'stats' => $stats,
    ]);
})->name('home');

// Public Marketplace (Catalogue & Equipment details)
Route::get('/marketplace', [\App\Http\Controllers\MarketplaceController::class, 'index'])->name('marketplace');
Route::get('/marketplace/{equipment}', [\App\Http\Controllers\MarketplaceController::class, 'show'])->name('marketplace.show');

// AI Assistant for Borrowers (Gemini Chatbot)
Route::post('/renter/chat', [RenterChatbotController::class, 'ask'])->name('renter.chat');

Route::get('/login', [AuthController::class, 'show'])->defaults('tab', 'login')->name('login');
Route::get('/register', [AuthController::class, 'show'])->defaults('tab', 'register')->name('register');

Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
});

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])->middleware('throttle:6,1')->name('verification.send');
});

Route::get('/auth/{provider}/redirect', [AuthController::class, 'redirectToProvider'])
    ->whereIn('provider', ['google', 'github'])
    ->name('oauth.redirect');

Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])
    ->whereIn('provider', ['google', 'github'])
    ->name('oauth.callback');

// Post-social-signup onboarding: pick a role (rent / lend / both) and set password
Route::middleware('auth')->group(function () {
    Route::get('/onboarding/role', [AuthController::class, 'showRoleSelection'])->name('onboarding.role');
    Route::post('/onboarding/role', [AuthController::class, 'storeRoleSelection'])->name('onboarding.role.store');
    Route::get('/onboarding/password', [AuthController::class, 'showPasswordSelection'])->name('onboarding.password');
    Route::post('/onboarding/password', [AuthController::class, 'storePasswordSelection'])->name('onboarding.password.store');
    Route::post('/onboarding/password/skip', [AuthController::class, 'skipPasswordSelection'])->name('onboarding.password.skip');

    // User Profile Management (Modification informations, mot de passe, avatar, suppression compte)
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [\App\Http\Controllers\ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [\App\Http\Controllers\ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Convenient /dashboard shortcut
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if (auth()->user()->isLender()) {
            return redirect()->route('lender.dashboard');
        }
        return redirect()->route('rentals.index');
    })->name('dashboard');

    // Lender Space (Category & Equipment CRUD - Protected for 'lend', 'both', or 'admin' roles)
    Route::middleware('lender')->group(function () {
        Route::get('/lender', [\App\Http\Controllers\LenderDashboardController::class, 'index'])->name('lender.dashboard');
        Route::resource('categories', \App\Http\Controllers\CategoryController::class);
        Route::post('equipment/analyze-photo', [\App\Http\Controllers\EquipmentController::class, 'analyzePhoto'])->name('equipment.analyze-photo');
        Route::resource('equipment', \App\Http\Controllers\EquipmentController::class);
        Route::get('/lender/rentals', [\App\Http\Controllers\LenderRentalController::class, 'index'])->name('lender.rentals.index');
        Route::patch('/lender/pack-reservations/{reservation}/status', [\App\Http\Controllers\LenderRentalController::class, 'updateKitReservationStatus'])->name('lender.kit-reservations.status');
    });

    // Rentals & Inspections (Cycle de location - Developer 2 Module)
    Route::middleware('renter')->group(function () {
        Route::get('/rentals/create', [\App\Http\Controllers\RentalController::class, 'create'])->name('rentals.create');
        Route::post('/rentals', [\App\Http\Controllers\RentalController::class, 'store'])->name('rentals.store');
    });

    Route::resource('rentals', \App\Http\Controllers\RentalController::class)->except(['create', 'store']);
    Route::post('/rentals/{rental}/confirm', [\App\Http\Controllers\RentalController::class, 'confirm'])->name('rentals.confirm');
    Route::post('/rentals/{rental}/cancel', [\App\Http\Controllers\RentalController::class, 'cancel'])->name('rentals.cancel');

    // Inspection Reports (Check-in & Check-out)
    Route::get('/rentals/{rental}/inspections/create', [\App\Http\Controllers\InspectionReportController::class, 'create'])->name('inspections.create');
    Route::post('/rentals/{rental}/inspections', [\App\Http\Controllers\InspectionReportController::class, 'store'])->name('inspections.store');
    Route::get('/inspections/{inspection}', [\App\Http\Controllers\InspectionReportController::class, 'show'])->name('inspections.show');
    Route::get('/inspections/{inspection}/edit', [\App\Http\Controllers\InspectionReportController::class, 'edit'])->name('inspections.edit');
    Route::put('/inspections/{inspection}', [\App\Http\Controllers\InspectionReportController::class, 'update'])->name('inspections.update');
    Route::post('/inspections/{inspection}/validate', [\App\Http\Controllers\InspectionReportController::class, 'validateReport'])->name('inspections.validate');
    Route::delete('/inspections/{inspection}', [\App\Http\Controllers\InspectionReportController::class, 'destroy'])->name('inspections.destroy');

    // Energy Kits CRUD & Equipment Management (Developer 3 Module - Restricted to Lenders & Admins)
    Route::get('/packs/create', [\App\Http\Controllers\EnergyKitController::class, 'create'])->middleware('lender')->name('energy-kits.create');
    Route::post('/packs', [\App\Http\Controllers\EnergyKitController::class, 'store'])->middleware('lender')->name('energy-kits.store');
    Route::get('/packs/{energyKit}/edit', [\App\Http\Controllers\EnergyKitController::class, 'edit'])->middleware('lender')->name('energy-kits.edit');
    Route::put('/packs/{energyKit}', [\App\Http\Controllers\EnergyKitController::class, 'update'])->middleware('lender')->name('energy-kits.update');
    Route::delete('/packs/{energyKit}', [\App\Http\Controllers\EnergyKitController::class, 'destroy'])->middleware('lender')->name('energy-kits.destroy');
    Route::post('/packs/{energyKit}/attach', [\App\Http\Controllers\EnergyKitController::class, 'attachEquipment'])->middleware('lender')->name('energy-kits.attach');
    Route::delete('/packs/{energyKit}/equipment/{equipment}', [\App\Http\Controllers\EnergyKitController::class, 'detachEquipment'])->middleware('lender')->name('energy-kits.detach');
    Route::post('/packs/{energyKit}/reserve', [\App\Http\Controllers\EnergyKitController::class, 'reserve'])->middleware('renter')->name('energy-kits.reserve');
    Route::post('/packs/{energyKit}/toggle', [\App\Http\Controllers\EnergyKitController::class, 'toggle'])->middleware('lender')->name('energy-kits.toggle');
});

// Public Packs Solaires (Energy Kits)
Route::get('/energy-kits', [\App\Http\Controllers\EnergyKitController::class, 'index'])->name('energy-kits.index');
Route::get('/energy-kits/{energyKit}', [\App\Http\Controllers\EnergyKitController::class, 'show'])->name('energy-kits.show');

// Public Points Relais & Hubs Solaires (Developer 4 Module)
Route::get('/hubs', [\App\Http\Controllers\CollectionHubController::class, 'index'])->name('hubs.index');
Route::get('/hubs/{hub}', [\App\Http\Controllers\CollectionHubController::class, 'show'])->name('hubs.show');

// Admin Back-Office (Protected: auth + admin role)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('pages.admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    });

    // Module 1: Équipements & Validation (Inventaire & Détails)
    Route::get('/equipment', [\App\Http\Controllers\AdminEquipmentController::class, 'index'])->name('admin.equipment.index');
    Route::get('/equipment/{equipment}', [\App\Http\Controllers\AdminEquipmentController::class, 'show'])->name('admin.equipment.show');
    Route::post('/equipment/{equipment}/approve', [\App\Http\Controllers\AdminEquipmentController::class, 'approve'])->name('admin.equipment.approve');
    Route::post('/equipment/{equipment}/reject', [\App\Http\Controllers\AdminEquipmentController::class, 'reject'])->name('admin.equipment.reject');
    Route::post('/equipment/{equipment}/toggle-availability', [\App\Http\Controllers\AdminEquipmentController::class, 'toggleAvailability'])->name('admin.equipment.toggle-availability');
    Route::delete('/equipment/{equipment}', [\App\Http\Controllers\AdminEquipmentController::class, 'destroy'])->name('admin.equipment.destroy');

    Route::get('/rentals', [\App\Http\Controllers\AdminRentalController::class, 'index'])->name('admin.rentals.index');
    Route::get('/energy-kits', [\App\Http\Controllers\AdminEnergyKitController::class, 'index'])->name('admin.energy-kits.index');
    Route::post('/energy-kits/{energyKit}/toggle', [\App\Http\Controllers\AdminEnergyKitController::class, 'toggle'])->name('admin.energy-kits.toggle');

    // Module 4: Hubs de Retrait Locaux & Créneaux de Permanence
    Route::resource('hubs', \App\Http\Controllers\AdminCollectionHubController::class)->names('admin.hubs');
    Route::post('/hubs/{hub}/toggle', [\App\Http\Controllers\AdminCollectionHubController::class, 'toggle'])->name('admin.hubs.toggle');
    Route::post('/hubs/{hub}/slots', [\App\Http\Controllers\AdminCollectionHubController::class, 'storeSlot'])->name('admin.hubs.slots.store');
    Route::put('/slots/{slot}', [\App\Http\Controllers\AdminCollectionHubController::class, 'updateSlot'])->name('admin.hubs.slots.update');
    Route::delete('/slots/{slot}', [\App\Http\Controllers\AdminCollectionHubController::class, 'destroySlot'])->name('admin.hubs.slots.destroy');
    Route::post('/slots/{slot}/toggle', [\App\Http\Controllers\AdminCollectionHubController::class, 'toggleSlot'])->name('admin.hubs.slots.toggle');

    // Administration des Utilisateurs
    Route::get('/users', [\App\Http\Controllers\AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}', [\App\Http\Controllers\AdminUserController::class, 'show'])->name('admin.users.show');
    Route::patch('/users/{user}/role', [\App\Http\Controllers\AdminUserController::class, 'updateRole'])->name('admin.users.update-role');
    Route::delete('/users/{user}', [\App\Http\Controllers\AdminUserController::class, 'destroy'])->name('admin.users.destroy');
});

