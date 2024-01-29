<?php

namespace App\Services\PaymentRequests;

use App\Models\User;
use App\Models\AdminUserLevel;
use App\Models\PaymentRequest;
use App\Models\PaymentRequestStatus;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentRequestService
{
    public function getUser(Request $oRequests): bool
    {
        if (Setting::first()->reverse_cb) {
            // c_idやprocess_codeが分かる場合のみ、マニュアルリバースCB機能を表示
            if (Auth::user()->isAdmin()) {
                return User::where('user_code', $oRequests->user_code)
                    ->orWhere('payment_code', $oRequests->payment_code)
                    ->first();
            }
        }

        return false;
    }

    public function addDefaultFilterings(Request $oRequests)
    {
        $aDefaultStatuses = [
            PaymentRequestStatus::AUTO,
            PaymentRequestStatus::MANUAL
        ];

        if (! $oRequests->has('status_id')) {
            $oRequests->merge(['status_id' => $aDefaultStatuses]);
        }

        if (! $oRequests->has('start_time')) {
            $oRequests->merge(['start_time' => date('Y/m/d')]);
        }
        if (! $oRequests->has('end_time')) {
            $oRequests->merge(['end_time' => date('Y/m/d')]);
        }
    }
}
