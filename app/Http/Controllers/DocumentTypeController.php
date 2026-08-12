<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    public function index()
    {
        $types = DocumentType::latest()->paginate(10);
        return view('document-types.index', compact('types'));
    }

    public function create()
    {
        return view('document-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:document_types,name',
            'description' => 'nullable|string',
        ]);

        DocumentType::create($request->all());

        return redirect()->route('document-types.index')->with('success', 'Jenis dokumen berhasil ditambahkan.');
    }

    public function edit(DocumentType $documentType)
    {
        return view('document-types.edit', compact('documentType'));
    }

    public function update(Request $request, DocumentType $documentType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:document_types,name,' . $documentType->id,
            'description' => 'nullable|string',
        ]);

        $documentType->update($request->all());

        return redirect()->route('document-types.index')->with('success', 'Jenis dokumen berhasil diperbarui.');
    }

    public function destroy(DocumentType $documentType)
    {
        // Peringatan: Karena relasi onDelete('cascade') di migration, 
        // menghapus jenis dokumen akan menghapus semua dokumen yang terkait.
        $documentType->delete();
        
        return redirect()->route('document-types.index')->with('success', 'Jenis dokumen berhasil dihapus.');
    }
}