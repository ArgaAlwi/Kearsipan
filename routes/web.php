<?php

use App\Models\Document;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Halaman Utama Publik
Route::get('/', function (Request $request) {
    $query = Document::with(['documentType', 'subject']);
    
    if ($request->filled('keyword')) {
        $query->where(function($q) use ($request) {
            $q->where('title', 'like', '%' . $request->keyword . '%')
              ->orWhere('number', 'like', '%' . $request->keyword . '%')
              ->orWhere('description', 'like', '%' . $request->keyword . '%');
        });
    }

    if ($request->filled('type')) {
        $query->where('document_type_id', $request->type);
    }

    if ($request->filled('year')) {
        $query->where('year', $request->year);
    }

    $documents = $request->anyFilled(['keyword', 'type', 'year']) 
        ? $query->latest()->paginate(10)->withQueryString() 
        : $query->latest()->take(5)->get();

    $types = DocumentType::orderBy('name', 'asc')->get();
    return view('welcome', compact('documents', 'types'));
})->name('home');

// Rute Detail & Akses Publik
Route::get('/dokumen/{document}', function (Document $document) {
    $document->load(['documentType', 'subject', 'attachments']); // <--- Tambahkan 'attachments' di sini
    return view('public-document-show', compact('document'));
})->name('public.document.show');

Route::get('/documents/{document}/preview', [DocumentController::class, 'preview'])->name('documents.preview');
Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

// --- TAMBAHKAN DUA BARIS INI UNTUK MULTI-FILE ---
Route::get('/attachments/{id}/preview', [DocumentController::class, 'previewAttachment'])->name('attachments.preview');
Route::get('/attachments/{id}/download', [DocumentController::class, 'downloadAttachment'])->name('attachments.download');
// ------------------------------------------------

// Rute Auth (Admin/Dashboard)
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Fitur Enterprise
    Route::get('documents/export', [DocumentController::class, 'exportExcel'])->name('documents.export');
    Route::get('documents/trash', [DocumentController::class, 'trash'])->name('documents.trash');
    Route::post('documents/{id}/restore', [DocumentController::class, 'restore'])->name('documents.restore');
    Route::delete('documents/{id}/force-delete', [DocumentController::class, 'forceDelete'])->name('documents.forceDelete');

    // Modul CRUD
    Route::resource('documents', DocumentController::class);

    Route::middleware('role:Admin')->group(function () {
        Route::resource('document-types', DocumentTypeController::class);
        Route::resource('subjects', SubjectController::class);
        Route::resource('users', UserController::class);
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    });
});

require __DIR__.'/auth.php';