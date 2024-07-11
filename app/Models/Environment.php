<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Environment extends Model
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'location_environments')
            ->withTimestamps()
            ->withPivot('id', 'team_id', 'created_by', 'updated_by', 'vmspec_id');
            
    }


    // public function locationEnvironments()
    // {
    //     return $this->belongsToMany(LocationEnvironment::class)
    //         ->withTimestamps()
    //         ->withPivot('id', 'team_id', 'created_by', 'updated_by');
    // }
    // public function tiers()
    // {
    //     return $this->hasManyThrough(
    //         Tier::class,
    //         EnvironmentTier::class,
    //         'location_environment_id',
    //         'id', // Foreign key on the tiers table
    //         'id', // Local key on the environments table
    //         'tier_id' // Foreign key on the environment_tiers table
    //     );
    // }

    // public function tiers()
    // {
    //     return $this->belongsToMany(Tier::class, 'environment_tiers', 'location_environment_id', 'location_environment_id', 'tttid', 'xxxid')->withPivot('id')
    //     ->using(RetrieveTiers::class)
    //     ;
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
