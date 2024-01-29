<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequestRequest;
use App\Models\AccessLog;
use App\Models\AdminUser;
use App\Models\PaymentRequest;
use App\Repositories\PaymentRequestRepository;
use App\Services\PaymentRequests\PaymentRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class PaymentRequestsController extends Controller
{
    /** @var PaymentRequestRepository */
    private $paymentRequestRepo;

    /** @var PaymentRequestService */
    private $paymentRequestService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PaymentRequestRepository $paymentRequestRepo, PaymentRequestService $paymentRequestService)
    {
        $this->paymentRequestRepo = $paymentRequestRepo;
        $this->paymentRequestService = $paymentRequestService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $oRequest
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $oRequest)
    {
        // dd(Gate::authorize('store', PaymentRequest::class));
        // \Log::info(auth('admin')->user());
        // dd($oRequest->query());
        // $oLoggedInUser = AdminUser::find(Auth::user()->id);
        // TODO: is ['user', 'orderRequestLog'] necessary?
        $oPaymentRequests = $this->paymentRequestRepo->paginate(
            'id',
            ['user', 'user.alert_level', 'status', 'payin'],
            $oRequest->disp_number ?? config('const.default_paginate_number'),
            request()->all()
        )->withQueryString();
        $statuses = $this->paymentRequestRepo->getStatuses();
        
        // dd($statuses);
        $selectedStatuses = ($oRequest->has('statuses') && !empty($oRequest['statuses'])) ? $oRequest['statuses'] : [];
        // dd($selectedStatuses);

        $oUser = $this->paymentRequestService->getUser($oRequest);

        return view('payment-requests.index', compact('statuses', 'selectedStatuses', 'oPaymentRequests', 'oUser'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // try {
        //     $oRequest = new Request;
        //     $oRequest->merge([
        //         'price' => $iAmount,
        //         'expid' => 'tmp_expid_'.Common::getRandStr(16),
        //     ]);
        //     $this->paymentRequestRepo->store()
        //     // $oNewOrder = Front::createOrder($oRequest, $oNewUser);
        //     $oNewOrder->cb_send_type = config('const.NEW_ORDER_CB_SEND_TYPE_REVERSE'); // prevents from concurrent CB actions as this would eliminate itself from linkable orders for CBs
        //     $oNewOrder->save();
        // } catch(\Exception $e) {
        //     if ($bIsManual) {
        //         $sLogMsg = 'manual reverse cb mode failed to create order.';
        //     } else {
        //         $sLogMsg = 'reverse cb mode failed to create order.';
        //     }
        //     Log::channel('post_jwt_error')->info($sLogMsg);
        //     $sErrMsg = 'user_id:' . ($oNewUser->id ?? '') . ' incoming_log_id:' . (isset($oIncomingLog) ? $oIncomingLog->id : '');
        //     Log::channel('post_jwt_error')->info($sErrMsg);
        //     Log::channel('post_jwt_error')->info('Error message: ' . $e->getMessage());
        //     return false;
        // }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $oPaymentRequest = $this->paymentRequestRepo->find(
            $id,
            ['user', 'status']
        );
        // $oAccessLog = AccessLog::where('order_id', $id)->first();

        return view('payment-requests.show')
            ->with(compact(
                'oPaymentRequest'
            ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentRequest $order)
    {
        // $this->authorize('viewAny', PaymentRequest::class);

        // $statuses = $this->paymentRequestRepo->getAllStatusesAsArray();

        // return view('payment-requests.edit')
        //     ->with(compact(
        //         'order',
        //         'statuses'
        //     ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentRequestRequest $oRequest, $id)
    {
        // $oAdminUserLogin = AdminUser::find(Auth::user()->id);
        // $bRedirect = true;
        // if ($oAdminUserLogin->isAdmin() || $oAdminUserLogin->isOperator()) {
        //     $bRedirect = false;
        // }
        // if ($bRedirect) {
        //     return redirect()->route('payment-requests.index');
        // }

        // $aStatus = config('master.orderStatusList');

        // return view('admin.new_order.repayment')
        //     ->with(compact(
        //         'aStatus'
        //     ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    /**
     * Compute payment requests stats.
     *
     * @param  \Illuminate\Http\Request  $oRequest
     */
    public function calculate(Request $oRequest): View
    {
        // $this->authorize('viewAny', PaymentRequest::class);

        // $this->paymentRequestService->addDefaultFilterings($oRequest);

        // [$nCount, $nTotalAmountOfOrders] = $this->paymentRequestRepo->getCountAndTotalAmount(request()->all());

        // return view('payment-requests.calculate')
        //     ->with(compact(
        //         'nCount',
        //         'nTotalAmountOfOrders'
        //     ));
    }
}
