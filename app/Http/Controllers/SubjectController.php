<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::latest()->paginate(10);
        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name',
            'description' => 'nullable|string',
        ]);

        Subject::create($request->all());

        return redirect()->route('subjects.index')->with('success', 'Subjek berhasil ditambahkan.');
    }

    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
            'description' => 'nullable|string',
        ]);

        $subject->update($request->all());

        return redirect()->route('subjects.index')->with('success', 'Subjek berhasil diperbarui.');
    }

    public function destroy(Subject $subject)
    {
        // Peringatan: Menghapus subjek akan menghapus dokumen yang terkait (onDelete cascade)
        $subject->delete();
        
        return redirect()->route('subjects.index')->with('success', 'Subjek berhasil dihapus.');
    }
}