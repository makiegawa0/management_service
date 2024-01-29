<?php

namespace App\Http\Controllers;

use App\Models\ApiLog;
use App\Repositories\ApiLogRepository;
use App\Services\ApiLogService;
use Illuminate\Http\Request;

class ApiLogController extends Controller
{
    /** @var ApiLogRepository */
    private $apiLogRepo;

    /** @var ApiLogService */
    private $apiLogService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ApiLogRepository $apiLogRepo, ApiLogService $apiLogService)
    {
        $this->apiLogRepo = $apiLogRepo;
        $this->apiLogService = $apiLogService;
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
        $this->authorize('viewAny', ApiLog::class);

        $oApiLogs = $this->apiLogRepo->paginate(
            'id',
            [],
            $oRequest->disp_number ?? config('const.default_paginate_number'),
            request()->all()
        )->withQueryString();
        $statuses = $this->apiLogRepo->getStatuses();
        // dd($statuses);
        $selectedStatuses = ($oRequest->has('statuses') && !empty($oRequest['statuses'])) ? $oRequest['statuses'] : [];
        // dd($oApiLogs);

        return view('api-log.index')
            ->with(compact(
                'statuses',
                'selectedStatuses',
                'oApiLogs',
            ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
