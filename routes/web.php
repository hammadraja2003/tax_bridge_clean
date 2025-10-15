<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FbrPostErrorController;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\FbrLookupController;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    //  return phpinfo();
    return view('welcome');
});

// routes/web.php
Route::get('/logs', function () {
    Log::channel('cloudwatch')->info('✅ CloudWatch info log test');
    Log::channel('cloudwatch')->warning('⚠️ CloudWatch warning log test');
    Log::channel('cloudwatch')->error('❌ CloudWatch error log test');

    Log::channel('single')->info('🧾 Local log test');
    return '✅ Logs sent (check CloudWatch + storage/logs/laravel.log)';
});


Route::middleware(['secret.key'])->group(function () {
    Route::get('/db/clone', [DatabaseController::class, 'showCloneForm'])->name('db.clone.form');
    Route::post('/db/clone', [DatabaseController::class, 'clone'])->name('db.clone');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'security.headers', 'set.tenant', 'business.configured'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/update-profile/{id}', [ProfileController::class, 'edit_profile'])->name('edit-profile');
    Route::post('/update-profile/{id}', [ProfileController::class, 'update_user_profile'])->name('update-profile');
    //Company OR Bussiness Configuration
    Route::get('/company/configuration', [CompanyController::class, 'index'])->name('company.configuration');
    Route::post('/company/configuration', [CompanyController::class, 'storeOrUpdate'])->name('company.configuration.save');
    //Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::post('/invoices/filter', [InvoiceController::class, 'filter'])->name('invoices.filter');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::get('/invoices/print/{id}', [InvoiceController::class, 'print'])->name('xero.print');
    Route::get('/invoices/modal-preview/{id}', [InvoiceController::class, 'modalPreview'])->name('invoices.modal.preview');
    Route::post('/invoicedddd', [InvoiceController::class, 'storeOrUpdate'])->name('create-new-invoice');
    Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    Route::put('/invoice/{id}', [InvoiceController::class, 'storeOrUpdate'])->name('invoice.update');
    //Excel Import
    // Route::get('/invoices/import', [InvoiceController::class, 'showForm'])->name('invoices.import.form');
    // Route::post('/invoices/import', [InvoiceController::class, 'importInvoice'])->name('invoices.import.process');
    //Buyers
    Route::get('/buyers', [BuyerController::class, 'index'])->name('buyers.index');
    Route::get('/buyer/create', [BuyerController::class, 'create'])->name('buyer.create');
    Route::post('/buyers/store', [BuyerController::class, 'store'])->name('buyers.store');
    Route::get('/buyers/edit/{id}', [BuyerController::class, 'edit'])->name('buyers.edit');
    Route::post('/buyers/update/{id}', [BuyerController::class, 'update'])->name('buyers.update');
    Route::delete('/buyers/delete/{id}', [BuyerController::class, 'delete'])->name('buyers.delete');
    Route::get('/buyers/fetch/{id}', [BuyerController::class, 'fetch'])->name('buyers.fetch');
    //Items
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items/store', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/edit/{id}', [ItemController::class, 'edit'])->name('items.edit');
    Route::post('/items/update/{id}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/delete/{id}', [ItemController::class, 'delete'])->name('items.delete');
    //ActivityLog
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity.logs');
    //AuditLog
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit_logs.index');
    Route::get('/audit-logs/{id}', [AuditLogController::class, 'show'])->name('audit_logs.show');
    //FBR Error Logs
    Route::get('/fbr-errors', [FbrPostErrorController::class, 'showErrors'])->name('fbr.errors');

    //FBR Lookups
    Route::get('/fbr', function () {
        return view('fbr');
    })->name('fbr.view');
    Route::post('/fbr/fetch', [FbrLookupController::class, 'fetch'])->name('fbr.fetch');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
