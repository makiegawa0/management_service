<?php

namespace App\Http\Controllers\PayIns;

use App\Http\Controllers\Controller;
use App\Repositories\BankAccountRepository;
use App\Repositories\PayInRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Rap2hpoutre\FastExcel\FastExcel;

class PayInController extends Controller
{
    /** @var PayInRepository */
    private $payinRepo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PayInRepository $payinRepo)
    {
        $this->payinRepo = $payinRepo;
    }

    /**
     * Display a listing of the pay-in data.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $oRequest): View
    {
        $oPayIns = $this->payinRepo->paginate(
            'id',
            ['bank_account', 'bank_account.bank_info'],
            $oRequest->disp_number ?? config('const.default_paginate_number'),
            request()->all()
        )->withQueryString();
        $statuses = $this->payinRepo->getStatuses();
        // dd($statuses);
        $selectedStatuses = ($oRequest->has('statuses') && !empty($oRequest['statuses'])) ? $oRequest['statuses'] : [];

        $oPayInLogs = $this->getPayInLogs();
        // dd($this->payinRepo->all('id',
        // ['bankAccount'],request()->all())->first());

        return view('payins.index', compact('statuses', 'selectedStatuses', 'oPayIns', 'oPayInLogs'));
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
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
     * Download a listing of the pay-in data.
     *
     * @return string|StreamedResponse
     *
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     * @throws Exception
     */
    public function export()
    {
        $oPayIns = $this->payinRepo->all(
            'id',
            ['bankAccount'],
            request()->all()
        );

        if (!$oPayIns->count()) {
            return redirect()->route('payins.index')->withErrors(__('There are no payins to export.'));
        }

        return (new FastExcel($oPayIns))
            ->download(sprintf('payins-%s.csv', date('Y-m-d-H-m-s')), static function ($payin) {
                $sBankAccountName = $payin->bank_type ?? '';
                if ($payin->bankAccount) {
                    $sBankAccountName .= ' / '.$payin->bankAccount->account_name;
                }

                return [
                    'id' => $payin->id,
                    'date' => $payin->date,
                    'process_code' => $payin->process_code,
                    'process_code_org' => $payin->process_code_org,
                    'amount' => $payin->amount,
                    'remainder' => $payin->remainder,
                    'bank_type' => $payin->bank_type,
                    'status' => $payin->status,
                    'supported' => $payin->supported,
                    'mc' => $payin->mc,
                    'api_flg' => $payin->api_flg,
                    'order_process_code' => $payin->order_process_code,
                    'order_uid' => $payin->order_cid,
                    'order_id' => $payin->order_id,
                    'send_type' => $payin->send_type,
                    'created_at' => $payin->created_at,
                    'updated_at' => $payin->updated_at,
                    'deleted_at' => $payin->deleted_at,
                    'bank_account_name' => $sBankAccountName,

                ];
            });

        // return (new FastExcel($oUsers))
        // ->download(sprintf('users-%s.csv', date('Y-m-d-H-m-s')), static function ($user) {
            //     return [
            //         'id' => $user->id,
            //         __('messages.user.user_name') => $user->fname.' '.$user->lname,
            //         __('messages.user.uid') => $user->c_id,
            //         __('messages.user.email') => $user->email,
            //         __('messages.user.tel') => $user->tel,
            //         __('messages.user.process_code') => $user->process_code,
            //         __('messages.user.keyword') => $user->keyword,
            //         __('messages.user.kyc') => $user->kyc,
            //         __('messages.user.user_kind') => $user->user_kind,
            //         __('messages.user.created_at') => $user->created_at,
            //         __('messages.user.last_order_created_at') => $user->last_order_created_at,
            //         __('messages.user.last_order_referrer') => $user->last_order_referrer,
            //     ];
        // });
    }

    private function getPayInLogs()
    {
        $oBankAccounts = (new BankAccountRepository())->getBy([
            'is_active' => 1,
        ]);

        foreach ($oBankAccounts as $oBankAccount) {
            if (Cache::has($oBankAccount->id)) {
                $oPayInLogs[$oBankAccount->account_name . ' (' . $oBankAccount->bank_name . ')'] = Cache::get($oBankAccount->id);
            }
        }
    }
}
