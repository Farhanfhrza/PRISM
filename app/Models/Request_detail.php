<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request_detail extends Model
{
    use HasFactory;
    protected $fillable = [
        'request_id',
        'stationery_id',
        'amount',
    ];

    public function requests()
    {
        return $this->belongsTo(Requests::class);
    }
    
    public function stationery()
    {
        return $this->belongsTo(Stationery::class);
    }
}
