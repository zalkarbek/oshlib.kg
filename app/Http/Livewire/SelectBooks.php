<?php

namespace App\Http\Livewire;

use App\Models\Book;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class SelectBooks extends DataTableComponent
{
    protected $model = Book::class;

    public function render()
    {
        return view('livewire.select-books');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),
            Column::make('Name', 'name'),
            Column::make('Image', 'image')->label(
                function($row, Column $column) {
                    return getMediaColumn($row);
                }
            ),
        ];
    }
}
