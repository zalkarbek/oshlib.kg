<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Repositories\ArticleCategoryRepository;
use App\Repositories\ArticleRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

class ArticleAPIController extends AppBaseController
{
    /** @var ArticleRepository */
    private $articleRepository;
    /** @var ArticleCategoryRepository */
    private $articleCategoryRepository;

    public function __construct(
        ArticleRepository $articleRepo,
        ArticleCategoryRepository $articleCategoryRepo)
    {
        $this->articleRepository = $articleRepo;
        $this->articleCategoryRepository = $articleCategoryRepo;
    }

    /**
     * Display a listing of the Article.
     * GET|HEAD /categories
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $this->articleRepository->pushCriteria(new RequestCriteria($request));
            $this->articleRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $articles = $this->articleRepository->all();

        return $this->sendResponse($articles, 'Articles retrieved successfully');
    }

    /**
     * Display the specified Article.
     * GET|HEAD /articles/{id}
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request)
    {
        try {
            $this->articleRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $article = $this->articleRepository->findWithoutFail($id);

        if (empty($article)) {
            return $this->sendError('Article not found');
        }

        return $this->sendResponse($article->toArray(), 'Article retrieved successfully');
    }
}
