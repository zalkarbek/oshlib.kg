<?php

namespace App\Http\Controllers;

use App\DataTables\ArticleCategoryDataTable;
use App\Models\ArticleCategory;
use App\Repositories\ArticleCategoryRepository;
use Illuminate\Http\Request;
use Flash;
use Prettus\Validator\Exceptions\ValidatorException;

class ArticleCategoryController extends Controller
{
    /** @var  ArticleCategoryRepository */
    private $articleCategoryRepository;

    public function __construct(ArticleCategoryRepository $articleCategoryRepo)
    {
        $this->articleCategoryRepository = $articleCategoryRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ArticleCategoryDataTable $dataTable
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ArticleCategoryDataTable $dataTable)
    {
        return $dataTable->render('articles.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('articles.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        try {
            $category = $this->articleCategoryRepository->create($input);
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $category->addMediaFromRequest('file')
                    ->toMediaCollection();
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.category')]));

        return redirect(route('articles.categories.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = $this->articleCategoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('articles.categories.index'));
        }

        return view('articles.categories.show')->with('category', $category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = $this->articleCategoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.category')]));

            return redirect(route('articles.categories.index'));
        }

        $categories = $this->articleCategoryRepository->all()->pluck('name', 'id')->prepend('Нет', '');

        return view('articles.categories.edit')->with('category', $category)->with('categories', $categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = $this->articleCategoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error('Category not found');
            return redirect(route('articles.categories.index'));
        }
        $input = $request->all();
        try {
            $category = $this->articleCategoryRepository->update($input, $id);

            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $category->clearMediaCollection();
                $category->addMediaFromRequest('file')
                    ->toMediaCollection();
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.category')]));

        return redirect(route('articles.categories.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = $this->articleCategoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('articles.categories.index'));
        }

        $this->articleCategoryRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.category')]));

        return redirect(route('articles.categories.index'));
    }

    /**
     * Remove Media of Category
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $category = $this->articleCategoryRepository->findWithoutFail($input['id']);
        try {
            if ($category->hasMedia($input['collection'])) {
                $category->clearMediaCollection($input['collection']);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
