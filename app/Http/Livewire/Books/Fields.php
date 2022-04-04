<?php

namespace App\Http\Livewire\Books;

use App\Models\Attribute;
use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;
use Livewire\Component;

class Fields extends Component
{
    public $book;

    public $categories;
    public $authors;
    public $publishers;
    public $attributes;

    public $bookAttributes;

    public $selectedCategoryId;
    public $selectedAuthorId;
    public $selectedPublisherId;

    public $name;
    public $description;
    public $releaseDate;
    public $file;

    protected $listeners = [
        'selectCategory' => 'changeCategory',
        'selectAuthor' => 'changeAuthor',
        'selectPublisher' => 'changePublisher',
        'filesUploaded' => 'setFilesUploaded'
    ];

    public function mount()
    {
        $this->categories = Category::all()->pluck('name', 'id');
        $this->authors = Author::all()->pluck('name', 'id');
        $this->publishers = Publisher::all()->pluck('name', 'id');
        $this->attributes = Attribute::all()->pluck('name', 'id');

        $this->bookAttributes = [];

        if ($this->book) {
            $this->name = $this->book->name;
            $this->description = $this->book->description;
            $this->releaseDate = $this->book->release_date;
            $this->selectedCategoryId = $this->book->category_id;
            $this->selectedAuthorId = $this->book->author_id;
            $this->selectedPublisherId = $this->book->publisher_id;

            foreach($this->book->attributes as $bookAttribute) {
                $attribute = $bookAttribute->attribute;
                array_push($this->bookAttributes,[
                    "id" => $attribute->id,
                    "key" => $attribute->key,
                    "value" => $attribute->value,
                    "title" => $attribute->title,
                ]);
            }
        }
    }

    public function render()
    {
        return view('livewire.books.fields');
    }

    public function changeCategory($id)
    {
        $this->selectedCategoryId = $id;
    }

    public function changeAuthor($id)
    {
        $this->selectedAuthorId = $id;
    }

    public function changePublisher($id)
    {
        $this->selectedPublisherId = $id;
    }
}
