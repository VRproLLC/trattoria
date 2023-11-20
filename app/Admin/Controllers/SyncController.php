<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SyncController as HomeSyncController;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class SyncController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title('Синхронизация с iiko')
            ->description(' ')
            ->row(function (Row $row) {
                $row->column(4, function (Column $column) {
                    $column->append('<a href="'.route('admin.synchronization.sync').'" class="btn btn-success">Синхнонизирвать</a>');
                });
            });
    }

    public function sync()
    {
        $sync = new HomeSyncController();
        $sync->sync();

        admin_success('Успешно синхнонизированно');

        return redirect()->back();
    }


}
