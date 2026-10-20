<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $types = DocumentType::orderBy('name', 'asc')->get();
        $subjects = Subject::orderBy('name', 'asc')->get();

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
            'document_files' => 'nullable|array',
            'document_files.*' => 'file|mimes:pdf,tif,txt,xlsx,docx,jpg,jpeg,png|max:1048576', 
        ]);

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

        if ($request->hasFile('document_files')) {
            foreach ($request->file('document_files') as $file) {
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                
                // OTOMATIS MEMBUAT FOLDER: documents/Tahun/Nomor-Dokumen
                $nomorAman = Str::slug($request->number);
                $folderPath = 'documents/' . $request->year . '/' . $nomorAman;
                
                $filePath = $file->storeAs($folderPath, $fileName, 'lenovo_san');

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

        return redirect()->route('documents.index')->with('success', 'Dokumen dan seluruh lampirannya berhasil ditambahkan ke Lenovo.');
    }

    public function show(Document $document)
    {
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

        if ($request->hasFile('document_files')) {
            foreach ($request->file('document_files') as $file) {
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                
                // OTOMATIS MEMBUAT FOLDER: documents/Tahun/Nomor-Dokumen
                $nomorAman = Str::slug($request->number);
                $folderPath = 'documents/' . $request->year . '/' . $nomorAman;
                
                $filePath = $file->storeAs($folderPath, $fileName, 'lenovo_san');

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

        return redirect()->route('documents.index')->with('success', 'Data dokumen berhasil diperbarui ke Lenovo.');
    }

    public function destroy(Document $document)
    {
        abort_if(Auth::user()->role?->name !== 'Admin', 403);

        $document->delete(); 
        
        ActivityLog::create([
            'user_id' => Auth::id(), 'action' => 'Hapus Dokumen',
            'description' => 'Memindahkan dokumen ke tong sampah: ' . $document->number
        ]);

        return redirect()->route('documents.index')->with('success', 'Dokumen dipindahkan ke Tong Sampah.');
    }

    public function preview(Document $document)
    {
        if (!$document->file_path) abort(404, 'File tidak dilampirkan.');
        
        if (Storage::disk('lenovo_san')->exists($document->file_path)) {
            return response()->file(Storage::disk('lenovo_san')->path($document->file_path));
        } elseif (Storage::disk('public')->exists($document->file_path)) {
            return response()->file(Storage::disk('public')->path($document->file_path));
        }

        abort(404, 'File fisik tidak ditemukan.');
    }

    public function download(Document $document)
    {
        if (!$document->file_path) abort(404, 'File tidak dilampirkan.');
        
        if (Storage::disk('lenovo_san')->exists($document->file_path)) {
            return response()->download(Storage::disk('lenovo_san')->path($document->file_path), $document->file_name);
        } elseif (Storage::disk('public')->exists($document->file_path)) {
            return response()->download(Storage::disk('public')->path($document->file_path), $document->file_name);
        }

        abort(404, 'File fisik tidak ditemukan.');
    }

    public function previewAttachment($id)
    {
        $attachment = \App\Models\DocumentAttachment::findOrFail($id);
        
        if (Storage::disk('lenovo_san')->exists($attachment->file_path)) {
            return response()->file(Storage::disk('lenovo_san')->path($attachment->file_path));
        } elseif (Storage::disk('public')->exists($attachment->file_path)) {
            return response()->file(Storage::disk('public')->path($attachment->file_path));
        }
        
        abort(404, 'File fisik lampiran tidak ditemukan.');
    }

    public function downloadAttachment($id)
    {
        $attachment = \App\Models\DocumentAttachment::findOrFail($id);
        
        if (Storage::disk('lenovo_san')->exists($attachment->file_path)) {
            return response()->download(Storage::disk('lenovo_san')->path($attachment->file_path), $attachment->file_name);
        } elseif (Storage::disk('public')->exists($attachment->file_path)) {
            return response()->download(Storage::disk('public')->path($attachment->file_path), $attachment->file_name);
        }

        abort(404, 'File fisik lampiran tidak ditemukan.');
    }

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
        $document = Document::onlyTrashed()->with('attachments')->findOrFail($id);
        
        if ($document->file_path) {
            if (Storage::disk('lenovo_san')->exists($document->file_path)) {
                Storage::disk('lenovo_san')->delete($document->file_path);
            } elseif (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
        }

        foreach($document->attachments as $attachment) {
            if ($attachment->file_path) {
                if (Storage::disk('lenovo_san')->exists($attachment->file_path)) {
                    Storage::disk('lenovo_san')->delete($attachment->file_path);
                } elseif (Storage::disk('public')->exists($attachment->file_path)) {
                    Storage::disk('public')->delete($attachment->file_path);
                }
            }
        }

        $document->forceDelete();
        
        return redirect()->route('documents.trash')->with('success', 'Dokumen dan seluruh file lampirannya berhasil dibasmi permanen.');
    }
}