<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Jetstream\Jetstream;

class Department extends Model
{
    use HasFactory;

    public function teams()
    {
        return $this->belongsToMany(Jetstream::teamModel(), Jetstream::membershipModel())
            ->withPivot('role', 'department_id') // Include the 'department_id' in pivot data
            ->withTimestamps()
            ->as('membership');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'team_user')->withTimestamps();
    }

    public function head()
    {
        return $this->belongsTo(User::class, 'hod');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
