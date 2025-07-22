<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Employee extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'employees';
    protected $fillable = [
        'name',
        'department',
        'div_id'
    ];

    public function division()
    {
        return $this->belongsTo(Division::class, 'div_id');
    }

    public function requests()
    {
        return $this->hasMany(Requests::class, 'employee_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'department',
                'div_id'
            ]) // Field yang ingin dicatat
            ->useLogName('employee')           // Nama kategori log (opsional)
            ->logOnlyDirty()                     // Hanya log jika data berubah
            ->setDescriptionForEvent(fn(string $eventName) => "Employee {$eventName}");
    }
}
