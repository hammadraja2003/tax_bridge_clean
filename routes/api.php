<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\API\APIAuthController;
use App\Http\Controllers\API\ApiTwoFactorController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\FbrPostErrorController;

Route::prefix('api')->group(function () {
    Route::get('/testroute', function () {
        return response()->json([
            'success' => true,
            'message' => 'API works!',
        ]);
    });
    Route::post('/login', [APIAuthController::class, 'apiLogin']);
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::put('/reset-password', [ResetPasswordController::class, 'reset']);
    Route::post('2fa/verify-login', [ApiTwoFactorController::class, 'verifyDuringLogin']);

    Route::middleware(['auth:sanctum', 'tenant.api', 'check.token.expire'])->group(function () {
        Route::post('/refresh-token', [APIAuthController::class, 'refreshToken']);
        //Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        //Buyers
        Route::get('/buyers', [BuyerController::class, 'index'])->name('buyers.index');
        Route::post('/buyers/store', [BuyerController::class, 'store'])->name('buyers.store');
        Route::post('/buyers/fetch', [BuyerController::class, 'fetchBuyer'])->name('buyers.fetch');
        Route::post('/buyers/update', [BuyerController::class, 'update'])->name('buyers.update');
        Route::post('/buyers/delete', [BuyerController::class, 'delete'])->name('buyers.delete');
        //Items
        Route::get('/items', [ItemController::class, 'index']);
        Route::post('/items/store', [ItemController::class, 'store']);
        Route::post('/items/fetch', [ItemController::class, 'fetchItem']);
        Route::post('/items/update', [ItemController::class, 'update']);
        Route::post('/items/delete', [ItemController::class, 'delete']);
        //Company OR Bussiness Configuration
        Route::post('/company/fetch-configuration', [CompanyController::class, 'fetchConfiguration']);
        Route::post('/company/update-configuration', [CompanyController::class, 'updateConfiguration']);
        //Invoices
        Route::get('/invoices', [InvoiceController::class, 'index']);
        Route::post('/invoices/filter', [InvoiceController::class, 'filter']);
        Route::post('/invoices/create', [InvoiceController::class, 'create']);
        Route::post('/invoices/delete', [InvoiceController::class, 'delete']);
        Route::post('/invoices/edit', [InvoiceController::class, 'edit']);
        Route::post('/invoices/save-or-post', [InvoiceController::class, 'saveOrPost']);
        //ActivityLog
        Route::get('/activity-logs', [ActivityLogController::class, 'index']);
        //AuditLog
        Route::get('/audit-logs', [AuditLogController::class, 'index']);
        //FBR Error Logs
        Route::get('/fbr-errors', [FbrPostErrorController::class, 'showErrors']);
        //Logout
        Route::post('/logout', [APIAuthController::class, 'apiLogout']);
        //2FA
        Route::get('2fa/setup', [ApiTwoFactorController::class, 'setup']);
        Route::post('2fa/enable', [ApiTwoFactorController::class, 'enable']);
        Route::post('2fa/disable', [ApiTwoFactorController::class, 'disable']);
    });
});
