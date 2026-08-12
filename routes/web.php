<?php

use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Halaman Utama + Fitur Pencarian Publik
Route::get('/', function (Illuminate\Http\Request $request) {
    $query = App\Models\Document::with(['documentType', 'subject']);
    
    if ($request->filled('keyword')) {
        $query->where('title', 'like', '%' . $request->keyword . '%')
              ->orWhere('number', 'like', '%' . $request->keyword . '%')
              ->orWhere('description', 'like', '%' . $request->keyword . '%');
    }

    $documents = $request->filled('keyword') 
        ? $query->latest()->paginate(10)->withQueryString() 
        : $query->latest()->take(5)->get();

    return view('welcome', compact('documents'));
})->name('home');

// =========================================================
// RUTE DETAIL PUBLIK (Tambahkan blok ini)
// =========================================================
Route::get('/dokumen/{document}', function (App\Models\Document $document) {
    $document->load(['documentType', 'subject']);
    return view('public-document-show', compact('document'));
})->name('public.document.show');
// =========================================================

// Route untuk Preview dan Download
Route::get('/documents/{document}/preview', [App\Http\Controllers\DocumentController::class, 'preview'])->name('documents.preview');
Route::get('/documents/{document}/download', [App\Http\Controllers\DocumentController::class, 'download'])->name('documents.download');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('documents', DocumentController::class);

    Route::middleware('role:Admin')->group(function () {
        Route::resource('document-types', DocumentTypeController::class);
        Route::resource('subjects', SubjectController::class);
        Route::resource('users', UserController::class);
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    });
});

require __DIR__.'/auth.php';