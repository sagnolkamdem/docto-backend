<?php

namespace Modules\LogActivity\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\LogActivity\Entities\LogActivity;

class LogActivityController extends CoreController
{
    public function show($id)
    {
        $logs = LogActivity::query()->where('user_id', $id)->latest()->get();
        return $this->successResponse(__('Got user\'s logs successfully'), [
            'logs' => $logs
        ]);
    }
}
