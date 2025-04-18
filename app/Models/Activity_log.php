<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity_log extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'activity_type',
        'activity_category',
        'description',
        'timestamp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
