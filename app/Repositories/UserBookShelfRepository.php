<?php


namespace App\Repositories;

use App\Models\UserBookShelf;

class UserBookShelfRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return UserBookShelf::class;
    }
}
