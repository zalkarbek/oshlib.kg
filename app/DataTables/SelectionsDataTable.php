<?php


namespace App\DataTables;

use App\Models\Selection;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Barryvdh\DomPDF\Facade as PDF;

class SelectionsDataTable extends DataTable
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
            ->editColumn('comment', function ($model) {
                if ($model->comment) return $model->comment;
                return 'Нет';
            })
            ->editColumn('books_count', function ($model) {
                return $model->bookSelections()->count();
            })
            ->editColumn('updated_at', function ($model) {
                return getDateColumn($model, 'updated_at');
            })
            ->addColumn('action', 'selections.datatables_actions')
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
            [
                'data' => 'name',
                'title' => trans('lang.selection_name'),
            ],
            [
                'data' => 'comment',
                'title' => trans('lang.comment'),
            ],
            [
                'data' => 'books_count',
                'title' => trans('lang.selection_books_count'),
                'searchable' => false,
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
     * @param Selection $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Selection $model)
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
        return 'attributesdatatable_' . time();
    }
}
