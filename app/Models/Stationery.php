<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stationery extends Model
{
    use HasFactory;
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
}
