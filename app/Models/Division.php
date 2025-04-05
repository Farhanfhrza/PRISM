<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
