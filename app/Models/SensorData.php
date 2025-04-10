<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    use HasFactory;

    protected $fillable = [
        'building_name',
        'load',
        'deflection',
        'angle_of_deflection',
        'status',
        'user_id',
    ];

    public function notification()
    {
        return $this->hasOne(Notification::class, 'sensor_data_id');
    }
}
