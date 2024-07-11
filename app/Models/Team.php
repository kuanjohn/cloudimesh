<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Team as JetstreamTeam;
use Illuminate\Database\Eloquent\Builder;

class Team extends JetstreamTeam
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'personal_team'];

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'personal_team' => 'boolean',
        ];
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function users()
    {
        return $this->belongsToMany(Jetstream::userModel(), Jetstream::membershipModel())->withPivot('role', 'department_id')->withTimestamps()->as('membership');
    }

    public function locationEnvironments()
    {
        return $this->hasMany(LocationEnvironment::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function environments()
    {
        return $this->hasMany(Environment::class);
    }

    public function tiers()
    {
        return $this->hasMany(Tier::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function operating_systems()
    {
        return $this->hasMany(OperatingSystem::class);
    }
}
