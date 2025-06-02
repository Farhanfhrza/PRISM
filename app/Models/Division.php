<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Division extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function user()
    {
        return $this->hasMany(User::class, 'div_id');
    }

    public function employee()
    {
        return $this->hasMany(Employee::class, 'div_id');
    }

    public function stationery()
    {
        return $this->hasMany(Stationery::class, 'div_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
            ]) // Field yang ingin dicatat
            ->useLogName('Division')           // Nama kategori log (opsional)
            ->logOnlyDirty()                     // Hanya log jika data berubah
            ->setDescriptionForEvent(fn(string $eventName) => "Division {$eventName}");
    }
}
