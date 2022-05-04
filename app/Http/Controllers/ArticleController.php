<?php

namespace App\Http\Controllers;

use App\DataTables\ArticleDataTable;
use App\Models\Article;
use App\Repositories\ArticleCategoryRepository;
use App\Repositories\ArticleRepository;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;
use Flash;

class ArticleController extends Controller
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
     * Display a listing of the resource.
     *
     * @param ArticleDataTable $dataTable
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ArticleDataTable $dataTable)
    {
        return $dataTable->render('articles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = $this->articleCategoryRepository->pluck('name', 'id');

        return view('articles.create')->with('categories', $categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        try {
            $input['user_id'] = auth()->id();
            $article = $this->articleRepository->create($input);
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $article->addMediaFromRequest('image')
                    ->toMediaCollection();
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.article')]));

        return redirect(route('articles.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return view('articles.show')->with('article', $article);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        $categories = $this->articleCategoryRepository->pluck('name', 'id');

        return view('articles.edit')
            ->with('article', $article)
            ->with('categories', $categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $input = $request->all();
        try {
            $article = $this->articleRepository->update($input, $article->id);
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $article->clearMediaCollection();
                $article->addMediaFromRequest('image')
                    ->toMediaCollection();
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.article')]));

        return redirect(route('articles.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.article')]));

        return redirect(route('articles.index'));
    }
}
