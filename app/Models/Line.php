<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    protected $fillable = ['name'];

    public function spareparts()
    {
        return $this->hasMany(Sparepart::class);
    }
}