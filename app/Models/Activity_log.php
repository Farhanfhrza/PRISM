<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public static function log(string $description, string $activity_type, string $activity_category): void
    {
        self::create([
            'user_id' => Auth::id(),
            'activity_type' => $activity_type,
            'activity_category' => $activity_category,
            'description' => $description,
            'timestamp' => now(),
        ]);
    }
}
