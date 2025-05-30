<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Stationery extends Model
{
    use HasFactory, LogsActivity;
    protected $fillable = [
        'name',
        'category',
        'stock',
        'initial_stock',
        'unit',
        'description',
        'div_id'
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function requestDetails()
    {
        return $this->hasMany(Request_detail::class, 'stationery_id');
    }

    // Dalam model Product (atau model terkait)
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->initial_stock = $model->stock;
        });
    }

    public function getGlobalSearchResultTitle(): string
    {
        return "{$this->name} - {$this->category}";
    }

    public function getGlobalSearchResultDetails(): array
    {
        return [
            'Kategori' => $this->category,
            'Stok' => $this->stock,
        ];
    }

    public function getGlobalSearchResultUrl(): string
    {
        return \App\Filament\Resources\StationeryResource::getUrl('edit', ['record' => $this]);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'stock', 'unit']) // Field yang ingin dicatat
            ->useLogName('stationery')           // Nama kategori log (opsional)
            ->logOnlyDirty()                     // Hanya log jika data berubah
            ->setDescriptionForEvent(fn(string $eventName) => "Stationery {$eventName}");
    }
}
