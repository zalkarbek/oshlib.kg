<?php

namespace App\Repositories;

use App\Models\Review;
use Spatie\Permission\Models\Role;
use App\Repositories\BaseRepository;

/**
 * Class RoleRepository
 * @package App\Repositories
 * @version May 29, 2018, 5:23 pm UTC
 *
 * @method Role findWithoutFail($id, $columns = ['*'])
 * @method Role find($id, $columns = ['*'])
 * @method Role first($columns = ['*'])
*/
class ReviewRepository extends BaseRepository
{

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Review::class;
    }
}
