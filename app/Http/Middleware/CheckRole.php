<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Menggunakan Facade Auth
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Pastikan user sudah login (Gunakan Auth::check agar terbaca oleh VS Code)
        if (!Auth::check()) {
            return redirect('login');
        }

        // 2. Ambil nama role user dari relasi database
        // Menambahkan PHPDoc agar Intelephense tahu ini adalah Model User kita
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $userRole = $user->role->name;

        // 3. Cek apakah role user ada di dalam daftar role yang diizinkan
        foreach ($roles as $role) {
            if ($userRole === $role) {
                return $next($request);
            }
        }

        // 4. Jika tidak memiliki akses, tampilkan error 403
        abort(403, 'Akses Ditolak: Anda tidak memiliki hak akses untuk halaman ini.');
    }
}