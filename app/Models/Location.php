<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function environments()
    {
        return $this->belongsToMany(Environment::class, 'location_environments')
            ->withTimestamps()
            ->withPivot('id', 'team_id', 'location_id', 'environment_id', 'vmspec_id');
    }

    public function vmspec()
    {
        return $this->belongsTo(vmspec::class);
    }

    public function storages()
    {
        return $this->belongsToMany(storage::class, 'location_storages')
            ->withTimestamps()
            ->withPivot('id', 'team_id', 'location_id', 'storage_id');
    }

}
