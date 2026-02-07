<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparepartItem extends Model
{
    protected $fillable = ['sparepart_id', 'serial_number', 'status', 'next_cleaning_at'];

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
}