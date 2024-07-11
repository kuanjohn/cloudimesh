<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Illuminate\View\View;

class AdminLayout extends Component
{
    // public $id;

    public function __construct()
    {
        // $this->id = Auth::user()->currentTeam->id;
    }
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        // dd($this->id);
        return view('layouts.admin');
    }
}
