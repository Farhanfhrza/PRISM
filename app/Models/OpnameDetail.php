<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpnameDetail extends Model
{
    protected $table = 'opname_detail';

    protected $fillable = [
        'opname_id', 'stationery_id', 'system_stock', 'actual_stock', 'difference', 'note'
    ];

    public function opname()
    {
        return $this->belongsTo(StockOpname::class, 'opname_id');
    }

    public function stationery()
    {
        return $this->belongsTo(Stationery::class, 'stationery_id');
    }
}
