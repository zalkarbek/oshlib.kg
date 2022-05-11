<?php

namespace App\Http\Livewire;

use App\Models\Book;
use App\Models\BookSelection;
use App\Models\Selection;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Prettus\Validator\Exceptions\ValidatorException;
use Flash;

class BookSelectionFields extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $bookSelection;

    public $name;
    public $description;
    public $comment;

    /** @var TemporaryUploadedFile */
    public $image;

    public $offset = 0;
    public $search;
    public $isSaving = false;

    public $selectedBooks = [];

    protected $listeners = [];

    public function mount()
    {
        if ($this->bookSelection) {
            $this->name = $this->bookSelection->name;
            $this->description = $this->bookSelection->description;
            $this->comment = $this->bookSelection->comment;
            foreach ($this->bookSelection->books as $index => $book) {
                $this->selectedBooks[$index] = $book->id;
            }
        }
    }

    public function render()
    {
        return view('livewire.book-selection-fields', [
            'books' => Book::where('name', 'like', '%'.$this->search.'%')->paginate(3),
        ]);
    }

    public function save()
    {
        $this->isSaving = true;
        $selection = new Selection;
        if ($this->bookSelection) {
            $selection = $this->bookSelection;
        }

        $selection->name = $this->name;
        $selection->comment = $this->comment;
        $selection->description = $this->description;

        $selection->save();
        if ($this->image) {
            $selection->clearMediaCollection();
            $selection->addMedia($this->image->getRealPath())
                ->toMediaCollection();
            $this->cleanupOldUploads();
        }
        if ($this->selectedBooks) {
            BookSelection::where('selection_id', $selection->id)->delete();
            foreach ($this->selectedBooks as $book) {
                $bookSelection = new BookSelection;
                $bookSelection->selection_id = $selection->id;
                $bookSelection->book_id = $book;
                $bookSelection->save();
            }
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.selection')]));

        $this->isSaving = false;

        $this->redirect('/console/selections');
    }
}
