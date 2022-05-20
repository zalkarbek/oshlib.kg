<?php


namespace App\DataTables;

use App\Models\RentedBooks;
use Carbon\Carbon;
use DateTime;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Barryvdh\DomPDF\Facade as PDF;

class RentedBookDataTable extends DataTable
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

            ->editColumn('issue_date', function ($model) {
                return getDateColumn($model, 'issue_date');
            })
            ->editColumn('return_date', function ($model) {
                if ($model->issue_date && $model->return_date) {
                    $issueDate = new Carbon($model->issue_date);
                    $returnDate = new Carbon($model->return_date);

                    $daysLeft = $issueDate->diff($returnDate)->days;
                    return $daysLeft == 0 ? "0" : "$daysLeft дней осталось";
                }

                return $model->return_date;
            })
            ->addColumn('action', 'rented-books.datatables_actions')
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
                'data' => 'id',
                'title' => '№',
            ],
            [
                'data' => 'book_name',
                'title' => 'Книга',
            ],
            [
                'data' => 'author_name',
                'title' => 'Автор',
            ],
            [
                'data' => 'issue_date',
                'title' => 'Дата выдачи',
            ],
            [
                'data' => 'return_date',
                'title' => 'Дата возврата',
            ]
        ];
    }

    /**
     * Get query source of dataTable.
     *
     * @param RentedBooks $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(RentedBooks $model)
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

}
