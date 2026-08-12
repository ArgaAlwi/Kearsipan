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

        $query = Document::with(['documentType', 'subject', 'uploader'])->latest();

        $query->when($request->keyword, function ($q, $keyword) {
            $q->where(function($subQuery) use ($keyword) {
                $subQuery->where('title', 'like', "%{$keyword}%")
                         ->orWhere('description', 'like', "%{$keyword}%");
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
    
public function show(Document $document)
    {
        // Memuat relasi agar nama jenis dan subjek bisa ditampilkan
        $document->load(['documentType', 'subject', 'uploader']);
        return view('documents.show', compact('document'));
    }

    public function create()
    {
        abort_if(Auth::user()->role?->name === 'Pegawai', 403, 'Akses Ditolak: Hanya Admin dan Operator yang dapat menambah dokumen.');

        $types = DocumentType::all();
        $subjects = Subject::all();
        return view('documents.create', compact('types', 'subjects'));
    }

    public function store(Request $request)
    {
        abort_if(Auth::user()->role?->name === 'Pegawai', 403);

        // PERBAIKAN 1: document_file diubah menjadi nullable (tidak wajib)
        $request->validate([
            'document_type_id' => 'required',
            'subject_id' => 'required',
            'number' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'year' => 'required|integer',
            'document_date' => 'required|date',
            'document_file' => 'nullable|mimes:pdf|max:10240', 
        ]);

        // Siapkan data dasar terlebih dahulu
        $data = [
            'document_type_id' => $request->document_type_id,
            'subject_id' => $request->subject_id,
            'number' => $request->number,
            'title' => $request->title,
            'year' => $request->year,
            'document_date' => $request->document_date,
            'description' => $request->description,
            'uploaded_by' => Auth::id(),
        ];

        // PERBAIKAN 2: Hanya proses file JIKA user benar-benar mengunggahnya
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public');

            $data['file_name'] = $fileName;
            $data['file_path'] = $filePath;
            $data['file_size'] = $file->getSize();
            $data['mime_type'] = $file->getMimeType();
        }

        Document::create($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tambah Dokumen',
            'description' => 'Menambahkan dokumen baru dengan Nomor: ' . $request->number . ' - ' . $request->title
        ]);

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil ditambahkan.');
    }

    public function edit(Document $document)
    {
        abort_if(Auth::user()->role?->name === 'Pegawai', 403, 'Akses Ditolak: Hanya Admin dan Operator yang dapat mengedit dokumen.');

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
            'document_file' => 'nullable|mimes:pdf|max:10240', 
        ]);

        $data = [
            'document_type_id' => $request->document_type_id,
            'subject_id' => $request->subject_id,
            'number' => $request->number,
            'title' => $request->title,
            'year' => $request->year,
            'document_date' => $request->document_date,
            'description' => $request->description,
        ];

        if ($request->hasFile('document_file')) {
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $file = $request->file('document_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public');

            $data['file_name'] = $fileName;
            $data['file_path'] = $filePath;
            $data['file_size'] = $file->getSize();
            $data['mime_type'] = $file->getMimeType();
        }

        $document->update($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Edit Dokumen',
            'description' => 'Memperbarui dokumen Nomor: ' . $request->number . ' - ' . $request->title
        ]);

        return redirect()->route('documents.index')->with('success', 'Data dokumen berhasil diperbarui.');
    }

    public function destroy(Document $document)
    {
        abort_if(Auth::user()->role?->name !== 'Admin', 403, 'Akses Ditolak: Hanya Admin yang dapat menghapus dokumen.');

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
        
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Hapus Dokumen',
            'description' => 'Menghapus dokumen Nomor: ' . $document->number . ' - ' . $document->title
        ]);

        $document->delete();
        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil dihapus.');
    }

    // ==========================================
    // FUNGSI PREVIEW DAN DOWNLOAD
    // ==========================================
    
    public function preview(Document $document)
    {
        // PERBAIKAN 3: Cek apakah dokumen ini punya lampiran
        if (!$document->file_path) {
            abort(404, 'File PDF tidak dilampirkan pada dokumen ini.');
        }

        $path = storage_path('app/public/' . $document->file_path);
        
        if (!file_exists($path)) {
            abort(404, 'Maaf, file fisik PDF tidak ditemukan di server.');
        }

        return response()->file($path);
    }

    public function download(Document $document)
    {
        // PERBAIKAN 4: Cek apakah dokumen ini punya lampiran
        if (!$document->file_path) {
            abort(404, 'File PDF tidak dilampirkan pada dokumen ini.');
        }

        $path = storage_path('app/public/' . $document->file_path);
        
        if (!file_exists($path)) {
            abort(404, 'Maaf, file fisik PDF tidak ditemukan di server.');
        }

        return response()->download($path, $document->file_name);
    }
}