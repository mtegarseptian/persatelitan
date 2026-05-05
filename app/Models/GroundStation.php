<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class GroundStation extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'location',
        'country',
        'latitude',
        'longitude',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->setDescriptionForEvent(fn(string $eventName) => "Ground Station {$this->name} has been {$eventName}");
    }

    public function satellites()
    {
        return $this->hasMany(Satellite::class);
    }
}