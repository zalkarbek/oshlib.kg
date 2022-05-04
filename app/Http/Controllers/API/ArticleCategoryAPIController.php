<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Repositories\ArticleCategoryRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

class ArticleCategoryAPIController extends AppBaseController
{
    /** @var ArticleCategoryRepository */
    private $articleCategoryRepository;

    public function __construct(ArticleCategoryRepository $articleCategoryRepo)
    {
        $this->articleCategoryRepository = $articleCategoryRepo;
    }

    /**
     * Display a listing of the Category.
     * GET|HEAD /categories
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $this->articleCategoryRepository->pushCriteria(new RequestCriteria($request));
            $this->articleCategoryRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $articles = $this->articleCategoryRepository->all();

        return $this->sendResponse($articles->toArray(), 'Categories retrieved successfully');
    }

    /**
     * Display the specified Book.
     * GET|HEAD /categories/{id}
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request)
    {
        try {
            $this->articleCategoryRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $category = $this->articleCategoryRepository->findWithoutFail($id);
        if (empty($category)) {
            return $this->sendError('Category not found');
        }

        return $this->sendResponse($category, 'Category retrieved successfully');
    }
}
