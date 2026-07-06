<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Carbon\Carbon;

class Location extends Model
{
    use HasUuids;
    protected $connection = 'client_db';

    protected $fillable = [
        'name',
        'address',
        'map_url',
        'base_fee',
        'is_active',
        'is_bookable',
        'price_addon_per_session'
    ];

    protected $casts = [
        'is_active'=>'boolean',
        'is_bookable'=>'boolean',
    ];

    public function reservations()
    {
        return $this->belongsToMany('reservation_location','location_id','reservation_id');
    }
}