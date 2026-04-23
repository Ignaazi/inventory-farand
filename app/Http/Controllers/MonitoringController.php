<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        // Isi dengan angka 0 agar tidak error saat halaman di-load
        $stats = [
            'cleaning'  => 0,
            'pending'   => 0,
            'in_today'  => 0,
            'out_today' => 0,
        ];

        // Buat koleksi kosong agar saat di-loop di view tidak error
        $recent = collect([]); 

        return view('monitoring.index', compact('stats', 'recent'));
    }
}
