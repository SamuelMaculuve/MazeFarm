<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InsuranceCardController;
use App\Http\Controllers\InsuranceClaimController;
use App\Http\Controllers\InsuranceCompanyController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('welcome');
});

// Language switcher (no auth required so language can be set before login)
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── Stock / Inventário ─────────────────────────────────────────────────
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/',                      [ProductController::class, 'index'])->name('index');
        Route::get('/create',                [ProductController::class, 'create'])->name('create');
        Route::post('/',                     [ProductController::class, 'store'])->name('store');
        Route::get('/{product}',             [ProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit',        [ProductController::class, 'edit'])->name('edit');
        Route::patch('/{product}',           [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}',          [ProductController::class, 'destroy'])->name('destroy');
        Route::post('/batches/{batch}/adjust', [ProductController::class, 'adjustStock'])->name('batches.adjust');
    });

    // ── PDV / Point of Sale ───────────────────────────────────────────────
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/',       fn() => view('pos.index'))->name('index');
        Route::get('/sales',  [SaleController::class, 'index'])->name('sales');
        Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
        Route::get('/sales/{sale}/print', [SaleController::class, 'printReceipt'])->name('sales.print');
        Route::patch('/sales/{sale}/cancel', [SaleController::class, 'cancel'])->name('sales.cancel');
    });

    // ── Compras / Purchases ───────────────────────────────────────────────
    Route::prefix('purchases')->name('purchases.')->group(function () {
        Route::get('/',                           [PurchaseOrderController::class, 'index'])->name('index');
        Route::get('/create',                     [PurchaseOrderController::class, 'create'])->name('create');
        Route::post('/',                          [PurchaseOrderController::class, 'store'])->name('store');
        Route::get('/{purchaseOrder}',            [PurchaseOrderController::class, 'show'])->name('show');
        Route::patch('/{purchaseOrder}/submit',   [PurchaseOrderController::class, 'submit'])->name('submit');
        Route::post('/{purchaseOrder}/receive',   [PurchaseOrderController::class, 'receive'])->name('receive');
    });

    // Suppliers (sub-section of purchases)
    Route::prefix('suppliers')->name('suppliers.')->group(function () {
        Route::get('/',             [SupplierController::class, 'index'])->name('index');
        Route::get('/create',       [SupplierController::class, 'create'])->name('create');
        Route::post('/',            [SupplierController::class, 'store'])->name('store');
        Route::get('/{supplier}/edit',   [SupplierController::class, 'edit'])->name('edit');
        Route::patch('/{supplier}',      [SupplierController::class, 'update'])->name('update');
        Route::delete('/{supplier}',     [SupplierController::class, 'destroy'])->name('destroy');
    });

    // ── Clientes / Customers ──────────────────────────────────────────────
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/',               [CustomerController::class, 'index'])->name('index');
        Route::get('/create',         [CustomerController::class, 'create'])->name('create');
        Route::post('/',              [CustomerController::class, 'store'])->name('store');
        Route::get('/{customer}',     [CustomerController::class, 'show'])->name('show');
        Route::get('/{customer}/edit',[CustomerController::class, 'edit'])->name('edit');
        Route::patch('/{customer}',   [CustomerController::class, 'update'])->name('update');
        Route::delete('/{customer}',  [CustomerController::class, 'destroy'])->name('destroy');
        Route::post('/{customer}/settle-credit', [CustomerController::class, 'settleCredit'])->name('settle-credit');
    });

    // ── Seguradoras / Insurance ───────────────────────────────────────────
    Route::prefix('insurance')->name('insurance.')->group(function () {
        // Companies
        Route::get('/companies',                          [InsuranceCompanyController::class, 'index'])->name('companies.index');
        Route::get('/companies/create',                   [InsuranceCompanyController::class, 'create'])->name('companies.create');
        Route::post('/companies',                         [InsuranceCompanyController::class, 'store'])->name('companies.store');
        Route::get('/companies/{company}',                [InsuranceCompanyController::class, 'show'])->name('companies.show');
        Route::get('/companies/{company}/edit',           [InsuranceCompanyController::class, 'edit'])->name('companies.edit');
        Route::patch('/companies/{company}',              [InsuranceCompanyController::class, 'update'])->name('companies.update');
        Route::delete('/companies/{company}',             [InsuranceCompanyController::class, 'destroy'])->name('companies.destroy');
        Route::post('/companies/{company}/rules',         [InsuranceCompanyController::class, 'storeCoverageRule'])->name('companies.rules.store');
        Route::delete('/rules/{rule}',                    [InsuranceCompanyController::class, 'destroyCoverageRule'])->name('rules.destroy');

        // Cards
        Route::get('/cards',              [InsuranceCardController::class, 'index'])->name('cards.index');
        Route::get('/cards/create',       [InsuranceCardController::class, 'create'])->name('cards.create');
        Route::post('/cards',             [InsuranceCardController::class, 'store'])->name('cards.store');
        Route::get('/cards/{card}/edit',  [InsuranceCardController::class, 'edit'])->name('cards.edit');
        Route::patch('/cards/{card}',     [InsuranceCardController::class, 'update'])->name('cards.update');
        Route::delete('/cards/{card}',    [InsuranceCardController::class, 'destroy'])->name('cards.destroy');

        // Claims
        Route::get('/claims',              [InsuranceClaimController::class, 'index'])->name('claims.index');
        Route::get('/claims/{claim}',      [InsuranceClaimController::class, 'show'])->name('claims.show');
        Route::patch('/claims/{claim}/status', [InsuranceClaimController::class, 'updateStatus'])->name('claims.status');
    });

    // Default /insurance route → companies index
    Route::get('/insurance', fn() => redirect()->route('insurance.companies.index'))->name('insurance.index');

    // ── Relatórios / Reports ──────────────────────────────────────────────
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/',          [ReportController::class, 'index'])->name('index');
        Route::get('/sales',     [ReportController::class, 'sales'])->name('sales');
        Route::get('/stock',     [ReportController::class, 'stock'])->name('stock');
        Route::get('/insurance', [ReportController::class, 'insurance'])->name('insurance');
    });

    // ── Configurações / Settings ──────────────────────────────────────────
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/',                        [SettingsController::class, 'index'])->name('index');
        Route::post('/branches',              [SettingsController::class, 'storeBranch'])->name('branches.store');
        Route::patch('/branches/{branch}',    [SettingsController::class, 'toggleBranch'])->name('branches.toggle');
        Route::post('/categories',            [SettingsController::class, 'storeCategory'])->name('categories.store');
        Route::delete('/categories/{category}', [SettingsController::class, 'destroyCategory'])->name('categories.destroy');
    });
});

require __DIR__ . '/auth.php';
