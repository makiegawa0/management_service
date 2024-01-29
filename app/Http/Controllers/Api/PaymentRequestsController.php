<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\PaymentRequestStoreRequest;
use App\Http\Requests\Api\PaymentRequestUpdateRequest;
use App\Http\Resources\CommonResource;
use App\Library\Front;
use App\Services\PaymentRequests\ApiPaymentRequestService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentRequestResource;
use App\Models\PayinStatus;
use App\Models\PaymentRequest;
use App\Models\PaymentRequestStatus;
use App\Repositories\PayInRepository;
use App\Repositories\PaymentRequestRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class PaymentRequestsController extends Controller
{
    /** @var ApiPaymentRequestService */
    private $service;

    private $paymentRequest;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ApiPaymentRequestService $service, PaymentRequestRepository $paymentRequest)
    {
        // $this->middleware('auth:admin');
        $this->service = $service;
        $this->paymentRequest = $paymentRequest;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\PaymentRequestStoreRequest  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentRequestStoreRequest $request): ?PaymentRequestResource
    {
        // $oAdminUserLogin = AdminUser::find(Auth::user()->id);
        // $oAdminUserLogin->isAdmin();
        // \Log::info(auth('admin')->check());
        // \Log::info(Auth::user());
        $this->authorize('store', PaymentRequest::class);
        $data = $request->validated();
        
        // TODO: validate request
        // make payment request and connect with payin 
        $paymentRequest = $this->service->store($data);

        return $paymentRequest ? new PaymentRequestResource($paymentRequest) : null;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentRequestUpdateRequest $request, $id): ?PaymentRequestResource
    {
        // TODO: validate request
        // link payin id
        // unlink payin id
        // issue cb
        // check the payin id is not linked with any other paymetn request
        // \Log::info($id);
        
        $this->authorize('update', PaymentRequest::class);
        // $data = $request->validated();
        // \Log::info(auth('admin')->check());
        if($request->filled('issue_callback')) {
            // notify to merchants
            $callback_succeeded = false;
            if (true) { // unsuccessful
                $callback_succeeded = true;
            } 
            $paymentRequest = $this->service->update($id, null, $callback_succeeded);
        } else {
            $paymentRequest = $this->service->update($id, $request->has('payin_id') ? $request->get('payin_id') : null);
        }

        // dd($request);
        // $workspaceId = Sendportal::currentWorkspaceId();
        // $paymentRequest = $this->subscribers->update($request->id, $request->validated());
        
        // return new PaymentRequestResource($paymentRequest);
        return $paymentRequest ? new PaymentRequestResource($paymentRequest) : null;
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
}
