<?php

namespace App\Services;

use Illuminate\Http\Request;

class ApiLogService
{
    public function addDefaultFilterings(Request $oRequests)
    {
        $aDefaultStatuses = [
            config('const.ORDER_STATUS_OK'),
            config('const.ORDER_STATUS_OK_BY_ADMIN'),
            config('const.ORDER_STATUS_OK_BY_API'),
        ];

        if (!$oRequests->has('status')) {
            $oRequests->merge(['status' => $aDefaultStatuses]);
        }

        if (!$oRequests->has('cb_start_time')) {
            $oRequests->merge(['cb_start_time' => date('Y/m/d')]);
        }
        if (!$oRequests->has('cb_end_time')) {
            $oRequests->merge(['cb_end_time' => date('Y/m/d')]);
        }
    }
}
