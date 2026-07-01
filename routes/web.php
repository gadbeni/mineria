<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Form101Controller;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerificationController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/verify/certificate/{token}', [VerificationController::class, 'verifyCertificate'])->name('verify.certificate');
Route::get('/verify/{token}', [VerificationController::class, 'verify'])->name('verify.document');

    Route::get('/', function () {
    // return view('welcome');
    return redirect('admin');
});

Route::get('certificates/{id?}/print', [CertificateController::class, 'print'])->name('certificates.print');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    Route::resource('certificates', CertificateController::class);


    // Route::get('companies', [CompanyController::class, 'index'])->name('voyager.companies.index');
    Route::get('companies/create', [CompanyController::class, 'create'])->name('voyager.companies.create');
    Route::post('companies/store', [CompanyController::class, 'store'])->name('companies.store');
    Route::post('companies/{id}/toggle-status', [CompanyController::class, 'toggleStatus'])->name('companies.toggle-status');
    // Route::resource('companies', CompanyController::class);
    Route::get('companies/ajax/list/{search?}', [CompanyController::class, 'list'])->name('companies.ajax.list');
    Route::get('companies/ajax/next-code', [CompanyController::class, 'nextCode'])->name('companies.ajax.next-code');
    Route::get('companies/certificate/list', [CompanyController::class, 'ajaxCompany']);//para obtener las personas o clientes para darles u prestamos


    Route::get('ajax/certificates/code/{code?}', [AjaxController::class, 'code'])->name('ajax-certificate.code');


    Route::resource('form101s', Form101Controller::class);
    Route::get('form101s/ajax/list/{search?}', [Form101Controller::class, 'list']);//Para generar la lista en el index
    Route::get('form101s/prinf/{form?}', [Form101Controller::class, 'prinf'])->name('form101s.prinf');
    Route::get('form101s/preview/{form}', [Form101Controller::class, 'preview'])->name('form101s.preview');
    Route::post('form101s/{id}/confirmar', [Form101Controller::class, 'confirmar'])->name('form101s.confirmar');



    Route::post('signatures/{id}/toggle-status', [AjaxController::class, 'toggleSignatureStatus'])->name('signatures.toggle-status');

    Route::get('download/log/{cad?}', [AjaxController::class, 'downloadLg'])->name('download.log');

    Route::get('reports/form101s',   [ReportController::class, 'form101s'])->name('reports.form101s');
    Route::get('reports/certificates', [ReportController::class, 'certificates'])->name('reports.certificates');
    Route::get('reports/companies',  [ReportController::class, 'companies'])->name('reports.companies');
});

Route::get('/admin/clear-cache', function() {
    Artisan::call('optimize:clear');
    return redirect('/admin')->with(['message' => 'Cache eliminada.', 'alert-type' => 'success']);
})->name('clear.cache');

