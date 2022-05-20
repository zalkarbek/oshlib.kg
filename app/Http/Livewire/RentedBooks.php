<?php

namespace App\Http\Livewire;

use App\Models\Book;
use Livewire\Component;
use Livewire\WithPagination;

class RentedBooks extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $rentedBook;
    public $userId;
    public $inventoryNumber;
    public $department;
    public $issueDate;
    public $returnDate;
    public $bailReceived;
    public $note;
    public $bookName;
    public $authorName;

    public $offset = 0;
    public $search;

    public function mount()
    {
        if ($this->rentedBook) {

        }
    }

    public function render()
    {
        return view('livewire.rented-books', [
            'books' => Book::where('name', 'like', '%'.$this->search.'%')->paginate(10),
        ]);
    }
}
