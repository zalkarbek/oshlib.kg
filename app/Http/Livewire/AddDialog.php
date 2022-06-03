<?php

namespace App\Http\Livewire;

use Livewire\Component;

class AddDialog extends Component
{
    public $id;
    public $title;

    public function render()
    {
        return view('livewire.add-dialog');
    }
}
