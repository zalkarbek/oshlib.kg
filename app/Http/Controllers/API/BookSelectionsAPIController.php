<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\AppBaseController;
use App\Models\Book;
use App\Repositories\SelectionRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

class BookSelectionsAPIController extends AppBaseController
{
    /** @var SelectionRepository */
    private $selectionRepository;

    public function __construct(SelectionRepository $selectionRepository)
    {
        $this->selectionRepository = $selectionRepository;
    }

    public function index(Request $request)
    {
        try {
            $this->selectionRepository->pushCriteria(new RequestCriteria($request));
            $this->selectionRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $selections = $this->selectionRepository->all();
        return $this->sendResponse($selections, 'Selections retrieved successfully');
    }

    /**
     * Display the specified Book.
     * GET|HEAD /selections/{id}
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request)
    {
        try {
            $this->selectionRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $selection = $this->selectionRepository->findWithoutFail($id);
        if (empty($selection)) {
            return $this->sendError('Selection not found');
        }

        return $this->sendResponse($selection, 'Selection retrieved successfully');
    }
}
