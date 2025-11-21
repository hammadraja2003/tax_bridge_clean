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
use App\Http\Controllers\FbrLookupController;
use Illuminate\Support\Facades\Log;
use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Aws\Sts\StsClient;
use App\Http\Controllers\Admin\BusinessController;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCredentialsMail;
use App\Mail\UserRegistarionMail;
/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['web', 'auth:web', 'verified', 'security.headers', 'set.tenant', 'business.configured'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/update-profile/{id}', [ProfileController::class, 'edit_profile'])->name('edit-profile');
    Route::post('/update-profile/{id}', [ProfileController::class, 'update_user_profile'])->name('update-profile');
    //Company OR Bussiness Configuration
    Route::get('/company/configuration', [CompanyController::class, 'index'])->name('company.configuration');
    Route::post('/company/configuration', [CompanyController::class, 'storeOrUpdate'])->name('company.configuration.save');
    //Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::post('/invoices/filter', [InvoiceController::class, 'filter'])->name('invoices.filter');
    Route::post('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::get('/invoices/print/{id}', [InvoiceController::class, 'print'])->name('xero.print');
    Route::get('/invoices/modal-preview/{id}', [InvoiceController::class, 'modalPreview'])->name('invoices.modal.preview');
    Route::post('/invoice', [InvoiceController::class, 'storeOrUpdate'])->name('create-new-invoice');
    Route::post('/invoices/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    Route::put('/invoice/{id}', [InvoiceController::class, 'storeOrUpdate'])->name('invoice.update');
    Route::post('/invoices/delete', [InvoiceController::class, 'delete'])->name('invoices.delete');
    //Excel Import
    // Route::get('/invoices/import', [InvoiceController::class, 'showForm'])->name('invoices.import.form');
    // Route::post('/invoices/import', [InvoiceController::class, 'importFromExcel'])->name('invoices.import.process');
    //Buyers
    Route::get('/buyers', [BuyerController::class, 'index'])->name('buyers.index');
    Route::get('/buyer/create', [BuyerController::class, 'create'])->name('buyer.create');
    Route::post('/buyers/store', [BuyerController::class, 'store'])->name('buyers.store');
    Route::get('/buyers/edit/{id}', [BuyerController::class, 'edit'])->name('buyers.edit');
    Route::post('/buyers/update', [BuyerController::class, 'update'])->name('buyers.update');
    Route::post('/buyers/delete', [BuyerController::class, 'delete'])->name('buyers.delete');
    Route::get('/buyers/fetch/{id}', [BuyerController::class, 'fetch'])->name('buyers.fetch');
    //Items
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items/store', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/edit/{id}', [ItemController::class, 'edit'])->name('items.edit');
    Route::post('/items/update', [ItemController::class, 'update'])->name('items.update');
    Route::post('/items/delete', [ItemController::class, 'delete'])->name('items.delete');
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
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/test-mail', function () {
    try {
        $loginUrl = route('login');
        // Mail::to('hammad.ali@f3technologies.eu')->send(new UserRegistarionMail(
        //     'Hammad Ali',
        //     'hammad.ali@f3technologies.eu',
        //     $loginUrl
        // ));
        // Mail::to('hammad.ali@f3technologies.eu')
        //     ->send(new UserCredentialsMail(
        //         'Hammad Ali',
        //         'hammad.ali@f3technologies.eu',
        //         $loginUrl
        //     ));
        Log::info('✅ Email sent successfully to hammad.ali@f3technologies.eu');
        return "✅ Email sent successfully!";
    } catch (\Exception $e) {
        Log::error('❌ Mail sending exception: ' . $e->getMessage());
        return "❌ Error sending email. Check logs.";
    }
});
/*
|--------------------------------------------------------------------------
| Cloudwatch Logs Routes
|--------------------------------------------------------------------------
*/
// Route::get('/cloudwatch/addlogs', function () {
//     Log::channel('cloudwatch')->info('✅ CloudWatch info log test');
//     return 'Logs sent!';
// });
// Route::get('/cloudwatch/viewlogs', function () {
//     $client = new CloudWatchLogsClient([
//         'region' => env('AWS_DEFAULT_REGION', 'me-south-1'),
//         'version' => 'latest',
//         'credentials' => [
//             'key' => env('AWS_ACCESS_KEY_ID'),
//             'secret' => env('AWS_SECRET_ACCESS_KEY'),
//         ],
//     ]);
//     $groupName = env('CLOUDWATCH_LOG_GROUP', 'laravel-app-log');
//     $streamName = env('CLOUDWATCH_LOG_STREAM', 'production');
//     try {
//         $result = $client->getLogEvents([
//             'logGroupName'  => $groupName,
//             'logStreamName' => $streamName,
//             'limit' => 20, // number of events
//             'startFromHead' => false, // newest first
//         ]);
//         $events = $result->get('events');
//         // Return clean JSON with timestamp and message
//         return response()->json(
//             collect($events)->map(fn($e) => [
//                 'timestamp' => date('Y-m-d H:i:s', $e['timestamp'] / 1000),
//                 'message'   => trim($e['message']),
//             ])
//         );
//     } catch (\Exception $e) {
//         return response()->json(['error' => $e->getMessage()], 500);
//     }
// });
/*
|--------------------------------------------------------------------------
| AWS Connection Routes
|--------------------------------------------------------------------------
*/
// Route::get('/aws-test', function () {
//     try {
//         $client = new StsClient([
//             'version' => 'latest',
//             'region' => env('AWS_DEFAULT_REGION', 'me-south-1'),
//             'credentials' => [
//                 'key' => env('AWS_ACCESS_KEY_ID'),
//                 'secret' => env('AWS_SECRET_ACCESS_KEY'),
//             ],
//         ]);
//         $result = $client->getCallerIdentity();
//         return response()->json([
//             '✅ Connected' => true,
//             'ARN' => $result['Arn'],
//             'Account' => $result['Account'],
//             'UserId' => $result['UserId'],
//         ]);
//     } catch (\Exception $e) {
//         return response()->json([
//             '❌ Connected' => false,
//             'Error' => $e->getMessage(),
//         ]);
//     }
// });
require __DIR__ . '/auth.php';
