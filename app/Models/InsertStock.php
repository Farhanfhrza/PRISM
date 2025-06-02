<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class InsertStock extends Model
{
    use LogsActivity;

    protected $table = 'insert_stock';

    protected $fillable = [
        'stationery_id',
        'amount',
        'inserted_by',
        'inserted_at'
    ];

    public function stationery()
    {
        return $this->belongsTo(Stationery::class, 'stationery_id');
    }

    public function insertedBy()
    {
        return $this->belongsTo(User::class, 'inserted_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'stationery_id',
                'amount',
                'inserted_by',
                'inserted_at'
            ]) // Field yang ingin dicatat
            ->useLogName('Insert Stock')           // Nama kategori log (opsional)
            ->logOnlyDirty()                     // Hanya log jika data berubah
            ->setDescriptionForEvent(fn(string $eventName) => "Insert Stock {$eventName}");
    }
}
