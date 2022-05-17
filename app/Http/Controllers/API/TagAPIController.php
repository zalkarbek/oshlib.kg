<?php


namespace App\Http\Controllers\API;

use App\Criteria\Book\OrderBooksCriteria;
use App\Criteria\Book\TagBooksCriteria;
use App\Http\Controllers\AppBaseController;
use App\Models\Tag;
use App\Repositories\BookRepository;
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
    /** @var BookRepository */
    private $bookRepository;

    public function __construct(TagRepository $tagRepo, BookRepository $bookRepository)
    {
        $this->tagRepository = $tagRepo;
        $this->bookRepository = $bookRepository;
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
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request)
    {
        try {
            $this->tagRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $tag = $this->tagRepository->findWithoutFail($id);

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

    /**
     * Display a listing of Category Book.
     * GET|HEAD /tags/{id}/books
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function books($id, Request $request)
    {
        try {
            $this->bookRepository->pushCriteria(new RequestCriteria($request));
            $this->bookRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->bookRepository->pushCriteria(new OrderBooksCriteria($request));
            $this->bookRepository->pushCriteria(new TagBooksCriteria($request, $id));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $books = $this->bookRepository->all();

        return $this->sendResponse($books->toArray(), 'Books retrieved successfully');
    }
}
