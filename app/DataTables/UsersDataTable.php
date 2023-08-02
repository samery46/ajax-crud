<?php

        namespace App\DataTables;

        use App\User;
        use Yajra\DataTables\Html\Button;
        use Yajra\DataTables\Html\Column;
        use Yajra\DataTables\Html\Editor\Editor;
        use Yajra\DataTables\Html\Editor\Fields;
        use Yajra\DataTables\Services\DataTable;

        class UsersDataTable extends DataTable
        {
            /**
             * It is used to Build a class of DataTable.
             *
             * @param mixed $query Results from query() method.
             * @return \Yajra\DataTables\DataTableAbstract
             */
            public function dataTable($query)
            {
                return datatables()
                    ->eloquent($query);
            }

            /**
             * It is used to get query sources of dataTable.
             *
             * @param \App\User $model
             * @return \Illuminate\Database\Eloquent\Builder
             */
            public function query(User $model)
            {
                return $model->newQuery();
            }

            /**
             * If you want to use html builder, it is the optional method.
             *
             * @return \Yajra\DataTables\Html\Builder
             */
            public function html()
            {
                return $this->builder()
                            ->setTableId('users-table')
                            ->columns($this->getColumns())
                            ->minifiedAjax()
                            ->orderBy(1)
                            ->parameters([
                                'dom'          => 'Bfrtip',
                                'buttons'      => ['excel', 'csv'],
                            ]);
            }

            /**
             * It is used to get columns.
             *
             * @return array
             */
            protected function getColumns()
            {
                return [
                    Column::make('id'),
                    Column::make('name'),
                    Column::make('email'),
                ];
            }

            /**
             * It is used to get the filename for export.
             *
             * @return string
             */
            protected function filename(): string
            {
                return 'Users_' . date('YmdHis');
            }
        }
