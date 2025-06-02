<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class OpnameDetail extends Model
{
    use LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'opname_id', 'stationery_id', 'system_stock', 'actual_stock', 'difference', 'note'
            ]) // Field yang ingin dicatat
            ->useLogName('Opname Setail')           // Nama kategori log (opsional)
            ->logOnlyDirty()                     // Hanya log jika data berubah
            ->setDescriptionForEvent(fn(string $eventName) => "Opname Setail {$eventName}");
    }
}
