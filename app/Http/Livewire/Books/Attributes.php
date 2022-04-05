<?php

namespace App\Http\Livewire\Books;

use App\Models\Attribute;
use Livewire\Component;

class Attributes extends Component
{
    public $book;
    public $attributes;
    public $bookAttributes;

    protected $listeners = [
        'addAttribute',
        'attributeChanged',
        'attributeValueChanged',
    ];

    public function mount()
    {
        $this->attributes = Attribute::all()->pluck('title', 'id');
        $this->bookAttributes = [];

        if ($this->book) {
            foreach($this->book->attributes as $bookAttribute) {
                $attribute = $bookAttribute->attribute;
                array_push($this->bookAttributes,[
                    "id" => $attribute->id,
                    "key" => $attribute->key,
                    "value" => $bookAttribute->value,
                    "title" => $attribute->title,
                ]);
            }
        }
    }

    public function render()
    {
        return view('livewire.books.attributes');
    }

    public function addAttribute()
    {
        array_push($this->bookAttributes, ['id' => null, 'value' => null, 'key' => null]);
    }

    public function attributeChanged($index, $val)
    {
        $this->bookAttributes[$index]['id'] = $val;
    }

    public function attributeValueChanged($index, $val)
    {
        $this->bookAttributes[$index]['value'] = $val;
    }
}
