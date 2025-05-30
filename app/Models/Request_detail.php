<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Request_detail extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'request_id',
        'stationery_id',
        'amount',
    ];

    public function request()
    {
        return $this->belongsTo(Requests::class);
    }
    
    public function stationery()
    {
        return $this->belongsTo(Stationery::class);
    }
}
