<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Satellite extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'ground_station_id',
        'name',
        'country',
        'launch_date',
        'orbit_type',
        'tle_line1',
        'tle_line2',
        'is_active',
        'description',
        'image',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'launch_date' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->setDescriptionForEvent(fn(string $eventName) => "Satellite {$this->name} has been {$eventName}");
    }

    public function groundStation()
    {
        return $this->belongsTo(GroundStation::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/satellite-default.png');
    }
}