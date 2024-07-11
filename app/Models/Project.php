<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function project_owner()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_users');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function vms()
    {
        return $this->hasMany(VirtualMachine::class);
    }

}


