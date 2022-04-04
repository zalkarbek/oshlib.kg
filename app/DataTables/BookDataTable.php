<?php


namespace App\DataTables;

use App\Models\Book;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Barryvdh\DomPDF\Facade as PDF;

class BookDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        $columns = array_column($this->getColumns(), 'data');
        $dataTable = $dataTable
            /*->editColumn('image', function ($model) {
                return getMediaColumn($model, 'default');
            })*/
            ->editColumn('category_name', function ($model) {
                return 'name';// $model->category->name;
            })
            ->editColumn('updated_at', function ($model) {
                return getDateColumn($model, 'updated_at');
            })
            ->addColumn('action', 'books.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            /*[
                'data' => 'image',
                'title' => trans('lang.book_image'),
                'searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false,
            ],*/
            [
                'data' => 'name',
                'title' => trans('lang.book_name'),
            ],
            [
                'data' => 'category_name',
                'title' => trans('lang.category'),
            ],
            [
                'data' => 'updated_at',
                'title' => trans('lang.category_updated_at'),
                'searchable' => false,
            ]
        ];
    }

    /**
     * Get query source of dataTable.
     *
     * @param Book $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Book $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['title'=>trans('lang.actions'),'width' => '80px', 'printable' => false, 'responsivePriority' => '100'])
            ->parameters(array_merge(['dom'          => 'Bfrtip'],
                config('datatables-buttons.parameters'), [
                    'language' => json_decode(
                        file_get_contents(base_path('resources/lang/' . app()->getLocale() . '/datatable.json')
                        ), true)
                ]
            ));
    }

    /**
     * Export PDF using DOMPDF
     * @return mixed
     */
    public function pdf()
    {
        $data = $this->getDataForPrint();
        $pdf = PDF::loadView($this->printPreview, compact('data'));
        return $pdf->download($this->filename() . '.pdf');
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'booksdatatable_' . time();
    }
}
