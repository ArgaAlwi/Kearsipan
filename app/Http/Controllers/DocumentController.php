<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $types = DocumentType::orderBy('name', 'asc')->get();
        $subjects = Subject::orderBy('name', 'asc')->get();

        // Mengambil data dokumen beserta relasi lampirannya
        $query = Document::with(['documentType', 'subject', 'uploader', 'attachments'])->latest();

        $query->when($request->keyword, function ($q, $keyword) {
            $q->where(function($subQuery) use ($keyword) {
                $subQuery->where('title', 'like', "%{$keyword}%")
                         ->orWhere('description', 'like', "%{$keyword}%")
                         ->orWhere('number', 'like', "%{$keyword}%");
            });
        });

        $query->when($request->document_type_id, function ($q, $typeId) {
            $q->where('document_type_id', $typeId);
        });

        $query->when($request->subject_id, function ($q, $subjectId) {
            $q->where('subject_id', $subjectId);
        });

        $query->when($request->number, function ($q, $number) {
            $q->where('number', 'like', "%{$number}%");
        });

        $query->when($request->year, function ($q, $year) {
            $q->where('year', $year);
        });

        $documents = $query->paginate(10)->withQueryString();

        return view('documents.index', compact('documents', 'types', 'subjects'));
    }

    public function create()
    {
        abort_if(Auth::user()->role?->name === 'Pegawai', 403);
        $types = DocumentType::all();
        $subjects = Subject::all();
        return view('documents.create', compact('types', 'subjects'));
    }

    public function store(Request $request)
    {
        abort_if(Auth::user()->role?->name === 'Pegawai', 403);

        $request->validate([
            'document_type_id' => 'required',
            'subject_id' => 'required',
            'number' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'year' => 'required|integer',
            'document_date' => 'required|date',
            'status' => 'required|in:Berlaku,Dicabut,Mengubah',
            // Validasi file menjadi array, multi-format, batas 1GB (1048576 KB)
            'document_files' => 'nullable|array',
            'document_files.*' => 'file|mimes:pdf,tif,txt,xlsx,docx,jpg,jpeg,png|max:1048576', 
        ]);

        // 1. Simpan Data Dokumen Utama
        $document = Document::create([
            'document_type_id' => $request->document_type_id,
            'subject_id' => $request->subject_id,
            'number' => $request->number,
            'title' => $request->title,
            'year' => $request->year,
            'document_date' => $request->document_date,
            'description' => $request->description,
            'status' => $request->status,
            'uploaded_by' => Auth::id(),
        ]);

        // 2. Looping (Perulangan) untuk menyimpan banyak lampiran sekaligus
        if ($request->hasFile('document_files')) {
            foreach ($request->file('document_files') as $file) {
                // Tambahkan uniqid agar nama file tidak bentrok
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('documents', $fileName, 'public');

                $document->attachments()->create([
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        ActivityLog::create([
            'user_id' => Auth::id(), 'action' => 'Tambah Dokumen',
            'description' => 'Menambahkan dokumen: ' . $request->number . ' beserta lampirannya.'
        ]);

        return redirect()->route('documents.index')->with('success', 'Dokumen dan seluruh lampirannya berhasil ditambahkan.');
    }

    public function show(Document $document)
    {
        // Panggil relasi attachments agar bisa ditampilkan di halaman Detail
        $document->load(['documentType', 'subject', 'uploader', 'attachments']);
        return view('documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        abort_if(Auth::user()->role?->name === 'Pegawai', 403);
        $types = DocumentType::all();
        $subjects = Subject::all();
        return view('documents.edit', compact('document', 'types', 'subjects'));
    }

    public function update(Request $request, Document $document)
    {
        abort_if(Auth::user()->role?->name === 'Pegawai', 403);

        $request->validate([
            'document_type_id' => 'required',
            'subject_id' => 'required',
            'number' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'year' => 'required|integer',
            'document_date' => 'required|date',
            'status' => 'required|in:Berlaku,Dicabut,Mengubah',
            'document_files' => 'nullable|array',
            'document_files.*' => 'file|mimes:pdf,tif,txt,xlsx,docx,jpg,jpeg,png|max:1048576', 
        ]);

        $document->update([
            'document_type_id' => $request->document_type_id,
            'subject_id' => $request->subject_id,
            'number' => $request->number,
            'title' => $request->title,
            'year' => $request->year,
            'document_date' => $request->document_date,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        // Tambahkan lampiran baru tanpa menghapus yang lama
        if ($request->hasFile('document_files')) {
            foreach ($request->file('document_files') as $file) {
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('documents', $fileName, 'public');

                $document->attachments()->create([
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        ActivityLog::create([
            'user_id' => Auth::id(), 'action' => 'Edit Dokumen',
            'description' => 'Memperbarui dokumen: ' . $request->number
        ]);

        return redirect()->route('documents.index')->with('success', 'Data dokumen berhasil diperbarui.');
    }

    public function destroy(Document $document)
    {
        abort_if(Auth::user()->role?->name !== 'Admin', 403);

        $document->delete(); // Soft delete
        
        ActivityLog::create([
            'user_id' => Auth::id(), 'action' => 'Hapus Dokumen',
            'description' => 'Memindahkan dokumen ke tong sampah: ' . $document->number
        ]);

        return redirect()->route('documents.index')->with('success', 'Dokumen dipindahkan ke Tong Sampah.');
    }

    // ==========================================
    // FUNGSI PREVIEW & DOWNLOAD FILE LAMA
    // ==========================================
    public function preview(Document $document)
    {
        if (!$document->file_path) abort(404, 'File tidak dilampirkan.');
        $path = storage_path('app/public/' . $document->file_path);
        if (!file_exists($path)) abort(404, 'File fisik tidak ditemukan.');
        return response()->file($path);
    }

    public function download(Document $document)
    {
        if (!$document->file_path) abort(404, 'File tidak dilampirkan.');
        $path = storage_path('app/public/' . $document->file_path);
        if (!file_exists($path)) abort(404, 'File fisik tidak ditemukan.');
        return response()->download($path, $document->file_name);
    }
// ==========================================
    // FUNGSI PREVIEW & DOWNLOAD LAMPIRAN BARU (MULTI-FILE)
    // ==========================================
    public function previewAttachment($id)
    {
        $attachment = \App\Models\DocumentAttachment::findOrFail($id);
        $path = storage_path('app/public/' . $attachment->file_path);
        if (!file_exists($path)) abort(404, 'File fisik lampiran tidak ditemukan.');
        
        // Memaksa browser menampilkan PDF/Gambar, tapi mendownload file lain (Word/Excel)
        return response()->file($path);
    }

    public function downloadAttachment($id)
    {
        $attachment = \App\Models\DocumentAttachment::findOrFail($id);
        $path = storage_path('app/public/' . $attachment->file_path);
        if (!file_exists($path)) abort(404, 'File fisik lampiran tidak ditemukan.');
        return response()->download($path, $attachment->file_name);
    }
    // ==========================================
    // FITUR ENTERPRISE (EXPORT & TONG SAMPAH)
    // ==========================================
    public function exportExcel()
    {
        $documents = Document::with(['documentType', 'subject', 'uploader'])->latest()->get();
        $filename = "Rekap_JDIH_" . date('Ymd_His') . ".csv";
        
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use($documents) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['NOMOR', 'JUDUL / PERIHAL', 'JENIS DOKUMEN', 'SUBJEK', 'TAHUN', 'STATUS', 'TANGGAL DITETAPKAN', 'DIINPUT OLEH']);
            
            foreach ($documents as $doc) {
                fputcsv($file, [
                    $doc->number, $doc->title, $doc->documentType->name ?? '-', $doc->subject->name ?? '-',
                    $doc->year, $doc->status, $doc->document_date, $doc->uploader->name ?? '-'
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function trash()
    {
        $documents = Document::onlyTrashed()->with(['documentType'])->get();
        return view('documents.trash', compact('documents'));
    }

    public function restore($id)
    {
        $document = Document::onlyTrashed()->findOrFail($id);
        $document->restore();
        
        ActivityLog::create([
            'user_id' => Auth::id(), 'action' => 'Restore Dokumen',
            'description' => 'Memulihkan dokumen: ' . $document->number
        ]);
        
        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        // Temukan dokumen yang ada di tong sampah (termasuk relasi lampirannya)
        $document = Document::onlyTrashed()->with('attachments')->findOrFail($id);
        
        // 1. Hapus file fisik lama (jika aplikasi masih menggunakan sistem lama)
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        // 2. Hapus SEMUA file fisik dari tabel lampiran (sistem baru)
        foreach($document->attachments as $attachment) {
            if ($attachment->file_path && Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }

        // 3. Basmi data dari database
        $document->forceDelete();
        
        return redirect()->route('documents.trash')->with('success', 'Dokumen dan seluruh file lampirannya berhasil dibasmi permanen.');
    }
}