<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class AlertBanner extends Component
{
    public $style;
    public $message;
    public $sleepTimer;
    public $showBanner = true;

    // protected $listeners = ['setBanner'];

    #[On('showBanner')]
    public function setBanner($data)
    {
        $this->style = $data['style'];
        $this->message = $data['message'];
        $this->sleepTimer = data_get($data, 'sleepTimer', null);
    }

    public function hide()
    {
        $this->message = '';
    }

    public function render()
    {
        return view('livewire.alert-banner');
    }
}
