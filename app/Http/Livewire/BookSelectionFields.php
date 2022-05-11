<?php

namespace App\Http\Livewire;

use App\Models\Book;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class BookSelectionFields extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $bookSelection;

    public $name;
    public $description;
    public $comment;

    public $image;

    public $offset = 0;

    public $selectedBooks = [];

    protected $listeners = [];

    public function mount()
    {
        if ($this->bookSelection) {

        }
    }

    public function render()
    {
        return view('livewire.book-selection-fields', [
            'books' => Book::paginate(10),
        ]);
    }

    public function save()
    {

    }
}
