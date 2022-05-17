<?php

namespace App\Http\Controllers\API;

use App\Criteria\Book\OrderBooksCriteria;
use App\Criteria\Category\OrderCategoryCriteria;
use App\Http\Controllers\AppBaseController;
use App\Models\Category;
use App\Repositories\BookRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class CategoryController
 * @package App\Http\Controllers\API
 */
class CategoryAPIController extends AppBaseController
{
    /** @var  CategoryRepository */
    private $categoryRepository;
    /** @var  BookRepository */
    private $bookRepository;

    public function __construct(CategoryRepository $categoryRepo, BookRepository $bookRepo)
    {
        $this->categoryRepository = $categoryRepo;
        $this->bookRepository = $bookRepo;
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
            $this->categoryRepository->pushCriteria(new RequestCriteria($request));
            $this->categoryRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->categoryRepository->pushCriteria(new OrderCategoryCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $categories = $this->categoryRepository->all();

        return $this->sendResponse($categories->toArray(), 'Categories retrieved successfully');
    }

    public function tree(Request $request)
    {
        $categories = $this->categoryRepository->whereNull('parent_id')->get();
        $categories->filter(function ($item) {
            getCategoryChildren($item);
        });

        return $this->sendResponse($categories->toArray(), 'Categories retrieved successfully');
    }

    /**
     * Display the specified Category.
     * GET|HEAD /categories/{id}
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request)
    {
        /** @var Category $category */
        try {
            $this->categoryRepository->pushCriteria(new RequestCriteria($request));

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $category = $this->categoryRepository->findWithoutFail($id);
        if ($request->has('parents')) {
            allParents($category);
        }

        if (empty($category)) {
            return $this->sendError('Category not found');
        }

        return $this->sendResponse($category->toArray(), 'Category retrieved successfully');
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        try {
            $category = $this->categoryRepository->create($input);
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $category->addMediaFromRequest('image')
                    ->toMediaCollection();
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($category->toArray(), __('lang.saved_successfully', ['operator' => __('lang.category')]));
    }

    /**
     * Update the specified Category in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            return $this->sendError('Category not found');
        }
        $input = $request->all();
        try {
            $category = $this->categoryRepository->update($input, $id);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $category->clearMediaCollection();
                $category->addMediaFromRequest('image')
                    ->toMediaCollection();
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($category->toArray(), __('lang.updated_successfully', ['operator' => __('lang.category')]));

    }

    /**
     * Remove the specified Category from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            return $this->sendError('Category not found');
        }

        $category = $this->categoryRepository->delete($id);

        return $this->sendResponse($category, __('lang.deleted_successfully', ['operator' => __('lang.category')]));
    }

    /**
     * Display a listing of Category Book.
     * GET|HEAD /categories/{id}/books
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
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $books = $this->bookRepository->findByField('category_id', $id);

        return $this->sendResponse($books->toArray(), 'Books retrieved successfully');
    }
}
