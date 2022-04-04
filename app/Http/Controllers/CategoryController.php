<?php

namespace App\Http\Controllers;

use App\DataTables\CategoryDataTable;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Repositories\UploadRepository;
use Illuminate\Http\Request;
use App\Repositories\CategoryRepository;
use App\Repositories\CustomAttributeRepository;
use Flash;
use Prettus\Validator\Exceptions\ValidatorException;

class CategoryController extends AppBaseController
{
    /** @var  CategoryRepository */
    private $categoryRepository;
    /** @var UploadRepository */
    private $uploadRepository;

    public function __construct(
        CategoryRepository $categoryRepo,
        UploadRepository $uploadRepo)
    {
        $this->categoryRepository = $categoryRepo;
        $this->uploadRepository = $uploadRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param CategoryDataTable $categoryDataTable
     * @return \Illuminate\Http\Response
     */
    public function index(CategoryDataTable $categoryDataTable)
    {
        return $categoryDataTable->render('categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = $this->categoryRepository->all()->pluck('name', 'id')->prepend('Нет', '');

        return view('categories.create')->with('categories', $categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryRequest $request)
    {
        $input = $request->all();
        try {
            $category = $this->categoryRepository->create($input);
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $category->addMediaFromRequest('file')
                    ->toMediaCollection();
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.category')]));

        return redirect(route('categories.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('categories.index'));
        }

        return view('categories.show')->with('category', $category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.category')]));

            return redirect(route('categories.index'));
        }

        $categories = $this->categoryRepository->all()->pluck('name', 'id')->prepend('Нет', '');

        return view('categories.edit')->with('category', $category)->with('categories', $categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCategoryRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error('Category not found');
            return redirect(route('categories.index'));
        }
        $input = $request->all();
        try {
            $category = $this->categoryRepository->update($input, $id);

            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $category->clearMediaCollection();
                $category->addMediaFromRequest('file')
                    ->toMediaCollection();
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.category')]));

        return redirect(route('categories.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('categories.index'));
        }

        $this->categoryRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.category')]));

        return redirect(route('categories.index'));
    }

    /**
     * Remove Media of Category
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $category = $this->categoryRepository->findWithoutFail($input['id']);
        try {
            if ($category->hasMedia($input['collection'])) {
                $category->clearMediaCollection($input['collection']);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
