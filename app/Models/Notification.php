<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'sensor_data_id',
        'state',
        'status',
    ];

    public function sensorData()
    {
        return $this->belongsTo(SensorData::class);
    }
}
