<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    public $timestamps = false;

    protected $fillable = [
        'user_id', 'stationery_id', 'transaction_type', 'amount', 'description', 'source_type', 'source_id', 'created_at', 'div_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stationery()
    {
        return $this->belongsTo(Stationery::class);
    }

    public function source()
    {
        return $this->morphTo(null, 'source_type', 'source_id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'div_id');
    }
}
