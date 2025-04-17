<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuditController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('audits', AuditController::class);
Route::get('audits/{audit}/details', [AuditController::class, 'details'])->name('audits.details');
Route::post('audits/{audit}/details', [AuditController::class, 'storeDetails'])->name('audits.details.store');
Route::get('audits/{audit}/segment/{segment}', [AuditController::class, 'segment'])->name('audits.questions.segment');
Route::get('audits/{audit}/questions', [AuditController::class, 'questions'])->name('audits.questions.create');
Route::post('audits/{audit}/questions', [AuditController::class, 'storeQuestions'])->name('audits.questions.store');
Route::post('audits/{audit}/attachments', [AuditController::class, 'storeAttachment'])->name('audits.attachments.store');
Route::delete('audits/{audit}/attachments/{attachment}', [AuditController::class, 'destroyAttachment'])->name('audits.attachments.destroy');

Route::get('/dashboard', [AuditController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/key-safety-behaviour', [AuditController::class, 'keySafetyBehaviour'])->middleware(['auth', 'verified'])->name('key-safety-behaviour');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::get('admin/audits/export', [AuditController::class, 'export'])->name('admin.audits.export');
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
