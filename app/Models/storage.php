<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class storage extends Model
{
    use HasFactory;

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'location_storages')
            ->withTimestamps()
            ->withPivot('id', 'team_id', 'location_id', 'storage_id');
    }
}
