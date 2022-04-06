<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\Tag;
use App\Repositories\TagRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class TagAPIController
 * @package App\Http\Controllers\API
 */
class TagAPIController extends AppBaseController
{
    /** @var  TagRepository */
    private $tagRepository;

    public function __construct(TagRepository $tagRepo)
    {
        $this->tagRepository = $tagRepo;
    }

    /**
     * Display a listing of the Tag.
     * GET|HEAD /tags
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $this->tagRepository->pushCriteria(new RequestCriteria($request));
            $this->tagRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $tags = $this->tagRepository->all();

        return $this->sendResponse($tags->toArray(), 'Tags retrieved successfully');
    }

    /**
     * Display the specified Tag.
     * GET|HEAD /tags/{id}
     *
     * @param Tag $tag
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($tag, Request $request)
    {
        try {
            $this->tagRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $tag = $this->tagRepository->findWithoutFail($tag->id);

        if (empty($tag)) {
            return $this->sendError('Tag not found');
        }

        return $this->sendResponse($tag->toArray(), 'Tag retrieved successfully');
    }

    /**
     * Display a listing of Tag by type genre.
     * GET|HEAD /genres
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function genres(Request $request)
    {
        try {
            $this->tagRepository->pushCriteria(new RequestCriteria($request));
            $this->tagRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $tags = $this->tagRepository->findByField('type', 'genre');

        return $this->sendResponse($tags->toArray(), 'Genres retrieved successfully');
    }

    /**
     * Display a listing of Tag by type theme.
     * GET|HEAD /themes
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function themes(Request $request)
    {
        try {
            $this->tagRepository->pushCriteria(new RequestCriteria($request));
            $this->tagRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $tags = $this->tagRepository->findByField('type', 'theme');

        return $this->sendResponse($tags->toArray(), 'Themes retrieved successfully');
    }
}
