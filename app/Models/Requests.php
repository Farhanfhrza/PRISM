<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\DB;


class Requests extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    protected $fillable = [
        'employee_id',
        'submit',
        'approved',
        'status',
        'information',
        'initiated_by'
    ];
    protected $attributes = [
        'status' => 'pending',
        'submit' => null
    ];

    protected $casts = [
        'submit' => 'datetime',
        'approved' => 'datetime',
        'employee_id' => 'integer'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function requestDetails()
    {
        return $this->hasMany(Request_detail::class, 'request_id');
    }

    public function initiator()
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    protected static function booted()
    {
        static::deleting(function ($request) {
            if ($request->isForceDeleting()) { // Hanya jalankan saat forceDelete
                DB::transaction(function () use ($request) {
                    // Ambil data SEBELUM dihapus cascade
                    $details = Request_detail::where('request_id', $request->id)->get();
                    foreach ($details as $detail) {
                        Stationery::where('id', $detail->stationery_id)
                            ->increment('stock', $detail->amount);
                    }
                });
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['employee_id', 'approve', 'status', 'information']) // Field yang ingin dicatat
            ->useLogName('request')           // Nama kategori log (opsional)
            ->logOnlyDirty()                     // Hanya log jika data berubah
            ->setDescriptionForEvent(fn(string $eventName) => "Request {$eventName}");
    }
}
