<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Section extends Model
{
    use HasUuids;
    protected $fillable = [
        'key',
        'label',
    ];
    public function admins():BelongsToMany
    {
        return $this->belongsToMany(Admin::class);
    }
}
