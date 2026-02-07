<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    protected $fillable = ['line_id', 'part_name', 'sap_code', 'min_stock'];

    // Relasi balik ke Line
    public function line()
    {
        return $this->belongsTo(Line::class);
    }

    // Relasi ke detail Serial Number
    public function items()
    {
        return $this->hasMany(SparepartItem::class);
    }

    // LOGIKA WARNA STOK (Tailwind)
    public function getStockStatusInfo()
    {
        $currentStock = $this->items()->where('status', 'available')->count();

        if ($currentStock <= 0) {
            return ['color' => 'bg-red-500', 'label' => 'Habis'];
        } elseif ($currentStock <= $this->min_stock) {
            return ['color' => 'bg-yellow-500', 'label' => 'Stock Warning'];
        } else {
            return ['color' => 'bg-green-500', 'label' => 'Stock Aman'];
        }
    }
}