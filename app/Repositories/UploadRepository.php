<?php

namespace App\Repositories;

use App\Models\Media;
use App\Models\Uploads;
use Illuminate\Support\Facades\Log;
use App\Repositories\BaseRepository;

/**
 * Class UploadRepository
 * @package App\Repositories
 * @version June 12, 2018, 11:30 am UTC
 *
 * @method Upload findWithoutFail($id, $columns = ['*'])
 * @method Upload find($id, $columns = ['*'])
 * @method Upload first($columns = ['*'])
 */
class UploadRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [

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
        return Uploads::class;
    }

    public function getByUuid($uuid = '')
    {
        $uploadModel = Uploads::query()->where('uuid', $uuid)->first();
        return $uploadModel;
    }

    /**
     * @param $uuid
     */
    public function clear($uuid)
    {
        $uploadModel = $this->getByUuid($uuid);
        return $uploadModel->delete();
    }

    /**
     * clear all uploaded cache
     */
    public function clearAll()
    {
        Uploads::query()->where('id', '>', 0)->delete();
        Media::query()->where('model_type', '=', 'App\Models\Upload')->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function allMedia($collection = null)
    {
        $medias = Media::where('model_type', '=', 'App\Models\Upload');
        if ($collection) {
            $medias = $medias->where('collection_name', $collection);
        }
        $medias = $medias->orderBy('id','desc')->get();
        return $medias;
    }


    public function collectionsNames()
    {
        $medias = Media::all('collection_name')->pluck('collection_name', 'collection_name')->map(function ($c) {
                return ['value' => $c,
                    'title' => title_case(preg_replace('/_/', ' ', $c))
                ];
        })->unique();
        unset($medias['default']);
        $medias->prepend(['value' => 'default', 'title' => 'Default'],'default');
        return $medias;
    }

}
