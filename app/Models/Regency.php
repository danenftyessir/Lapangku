<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Regency (Kabupaten/Kota)
 */
class Regency extends Model
{
    use HasFactory;

    protected $fillable = ['province_id', 'name', 'code'];

    public $timestamps = false;

    /**
     * relasi ke province
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * relasi ke problems
     */
    public function problems()
    {
        return $this->hasMany(Problem::class);
    }
}