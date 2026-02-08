<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparepartRequest extends Model
{
    protected $fillable = [
        'nik', 'nama', 'sparepart_id', 'qty', 'remark', 'type', 'status', 'user_id', 'approved_at'
    ];

    /**
     * Casting kolom approved_at agar Laravel mengenalinya sebagai objek Carbon.
     * Ini krusial supaya fungsi ->format() di Blade tidak error.
     */
    protected $casts = [
        'approved_at' => 'datetime',
    ];

    // Relasi untuk mengambil nama sparepart dari tabel stock
    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }

    // Relasi untuk tahu siapa yang buat request
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}