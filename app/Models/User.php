<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use App\Models\HasTeams;
use Laravel\Jetstream\Jetstream;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    use HasProjects;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        // Fetch the user's role from the pivot table
        $user = Auth::user();
        $isAdmin = $this->hasTeamRole($user->currentTeam, 'admin');
        return $isAdmin;
    }

    public function teams()
    {
        return $this->belongsToMany(Jetstream::teamModel(), Jetstream::membershipModel())
                        ->withPivot('role','department_id')
                        ->withTimestamps()
                        ->as('membership');
    }

    // public function department()
    // {
    //     return $this->belongsToMany(Department::class, 'team_user')->wherePivot('team_id', $this->current_team_id);
    // }

    public function department()
    {
        return $this->belongsToMany(Department::class, 'team_user')->withTimestamps();
    }

    public function own_projects()
    {
        return $this->hasMany(Project::class, 'owner');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_users')
        ->withPivot('role');
    }
}
