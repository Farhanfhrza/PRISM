<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'department',
        'div_id'
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
    
    public function requests()
    {
        return $this->hasMany(Requests::class, 'employee_id');
    }
}
