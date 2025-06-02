<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class StockOpname extends Model
{
    use LogsActivity;

    protected $table = 'stock_opname';

    protected $fillable = [
        'initiated_by', 'approved_by', 'opname_date', 'opname_status', 'description', 'div_id'
    ];

    public function details()
    {
        return $this->hasMany(OpnameDetail::class, 'opname_id');
    }

    public function initiator()
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'div_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'initiated_by', 'approved_by', 'opname_date', 'opname_status', 'description', 'div_id'
            ]) // Field yang ingin dicatat
            ->useLogName('Stock Opname')           // Nama kategori log (opsional)
            ->logOnlyDirty()                     // Hanya log jika data berubah
            ->setDescriptionForEvent(fn(string $eventName) => "Stock Opname {$eventName}");
    }
}
