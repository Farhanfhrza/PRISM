<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stationery extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category',
        'stock',
        'description',
        'div_id'
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
    
    public function request_detail()
    {
        return $this->hasMany(Request_detail::class, 'stationery_id');
    }
}
