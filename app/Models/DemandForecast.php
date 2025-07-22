<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandForecast extends Model
{
    protected $fillable = [
        'date', 'stationery_name', 'division',
        'predicted_demand', 'lower_bound', 'upper_bound'
    ];
}
