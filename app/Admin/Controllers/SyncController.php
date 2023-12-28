<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\SynchronizationIikoJob;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Support\Facades\Cache;

class SyncController extends Controller
{
    public function index(Content $content): Content
    {
        return $content
            ->title('Синхронизация с iiko')
            ->description(' ')
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    if(\Cache::get('synchronization')){
                        $column->append('<div class="alert alert-success alert-dismissable">
                                    <h4><i class="icon fa fa-check"></i>Синхронизация запущена, ожидайте. Примерно 3-5 минут.</h4>
                                     <p></p>
                                 </div>');
                    } else $column->append('<a href="'.route('admin.synchronization.sync').'" class="btn btn-success">Синхнонизирвать</a>');
                });
            });
    }

    public function sync(): \Illuminate\Http\RedirectResponse
    {
        SynchronizationIikoJob::dispatch();
        Cache::set('synchronization', true);
        return redirect()->back();
    }
}
