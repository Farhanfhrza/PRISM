<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsertStock extends Model
{
    protected $table = 'insert_stock';

    protected $fillable = [
        'stationery_id', 'amount', 'inserted_by', 'inserted_at'
    ];

    public function stationery()
    {
        return $this->belongsTo(Stationery::class, 'stationery_id');
    }

    public function insertedBy()
    {
        return $this->belongsTo(User::class, 'inserted_by');
    }
    
}
