<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    /**
     * Sesuai migration terbaru: line_id dan sap_code sudah dihapus.
     * Kolom yang diizinkan untuk diisi (Mass Assignment).
     */
    protected $fillable = [
        'part_name', 
        'min_stock',
    ];

    /**
     * Relasi ke detail item (Tabel sparepart_items).
     * Digunakan untuk menghitung stok fisik (Serial Number).
     */
    public function items()
    {
        return $this->hasMany(SparepartItem::class);
    }

    /**
     * Logika warna status stok (Tailwind CSS classes).
     * Membandingkan jumlah item 'available' dengan min_stock.
     */
    public function getStockStatusInfo()
{
    $currentStock = $this->items()->where('status', 'available')->count();

    if ($currentStock <= 0) {
        // Kotak Merah
        return ['color' => 'bg-red-500 rounded-md', 'label' => 'Habis', 'shape' => 'w-5 h-5'];
    } elseif ($currentStock <= $this->min_stock) {
        // Kotak Kuning
        return ['color' => 'bg-yellow-500 rounded-md', 'label' => 'Warning', 'shape' => 'w-5 h-5'];
    } else {
        // Bulat Hijau
        return ['color' => 'bg-green-500 rounded-full', 'label' => 'Aman', 'shape' => 'w-3 h-3'];
    }
}
}