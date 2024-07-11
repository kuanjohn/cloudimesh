<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class LocationEnvironment extends Model
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function tiers()
    {
        return $this->belongsToMany(Tier::class, 'environment_tiers')
            ->withPivot('id', 'team_id', 'created_by', 'updated_by')
            ->withTimestamps();
    }

    // public function getTiersAttribute()
    // {
    //     // Assuming you have a 'tiers' relationship defined in the Environment model
    //     return 'here';
    // }

    public function tier()
    {
        return $this->belongsTo(Tier::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function environment()
    {
        return $this->belongsTo(Environment::class);
    }

    public function vmspec()
    {
        return $this->belongsTo(VMspec::class);
    }
}
