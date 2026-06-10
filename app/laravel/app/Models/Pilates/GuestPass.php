<?php

namespace App\Models\Pilates;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class GuestPass extends Model
{
    use HasUuids;
    protected $connection = 'client_db';
    
    //
}
