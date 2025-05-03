<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'submit',
        'approved',
        'status',
        'information'
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
    
    public function request_detail()
    {
        return $this->hasMany(Request_detail::class, 'request_id');
    }
}
