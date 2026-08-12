<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use App\Models\DocumentType;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung total data
        $totalDocuments = Document::count();
        $totalUsers = User::count();
        $totalDocumentTypes = DocumentType::count();

        // Mengambil 5 dokumen terbaru
        $recentDocuments = Document::with('documentType')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalDocuments', 
            'totalUsers', 
            'totalDocumentTypes',
            'recentDocuments'
        ));
    }
}