<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RetrieveTiers extends Pivot
{
    use HasFactory;

    public function getTiersData()
    {
        // Example: Retrieve related data based on the pivot id
        $tiersData = LocationEnvironment::where('id', $this->id)->get();
        // dd($tiersData);

        return $tiersData;
    }
}
