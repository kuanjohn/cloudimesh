<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tier extends Model
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function locationEnvironments()
    {
        return $this->belongsToMany(LocationEnvironment::class, EnvironmentTier::class)
            ->withTimestamps()
            ->withPivot('id', 'team_id', 'created_by', 'updated_by');
    }

    // public function environments()
    // {
    //     return $this->belongsToMany(Environment::class, 'location_environments')
    //         ->withTimestamps()
    //         ->withPivot('id', 'team_id', 'created_by', 'updated_by');
    // }

    // public function locations()
    // {
    //     return $this->belongsToMany(Location::class, 'location_environments')
    //         ->withTimestamps()
    //         ->withPivot('id', 'team_id', 'created_by', 'updated_by');
    // }

    // public function environmentTier()
    // {
    //     return $this->belongsToMany(EnvironmentTier::class,  LocationEnvironment::class);
    // }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
