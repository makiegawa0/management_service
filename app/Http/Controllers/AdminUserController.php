<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUserRequest;
use App\Models\AdminUserLevel;
use App\Repositories\AdminUserRepository;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /** @var AdminUserRepository */
    private $adminUserRepo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AdminUserRepository $adminUserRepo)
    {
        $this->adminUserRepo = $adminUserRepo;
        // $this->paymentRequestService = $paymentRequestService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $oRequest)
    {
        $this->authorize('viewAny', AdminUser::class);

        $oAdminUsers = $this->adminUserRepo->paginate(
            'id',
            ['level'],
            $oRequest->disp_number ?? config('const.default_paginate_number'),
            request()->all()
        )->withQueryString();

        // dd($oAdminUsers);

        $levels = $this->adminUserRepo->getLevels();
        // dd($statuses);
        $selectedLevels = ($oRequest->has('levels') && !empty($oRequest['levels'])) ? $oRequest['levels'] : [];

        return view('admin-users.index')
            ->with(compact(
                'oAdminUsers',
                'levels', 'selectedLevels'
            ));
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
    public function edit(Request $oRequest, $id)
    {
        // $emailServiceTypes = $this->emailServices->getEmailServiceTypes()->pluck('name', 'id');
        $adminUser = $this->adminUserRepo->find($id);
        $levels = $this->adminUserRepo->getLevels();
        // dd($statuses);
        $selectedLevels = ($oRequest->has('levels') && !empty($oRequest['levels'])) ? $oRequest['levels'] : [];
        // $emailServiceType = $this->emailServices->findType($emailService->type_id);

        return view('admin-users.edit', compact('adminUser', 'levels', 'selectedLevels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(AdminUserRequest $request, $id)
    {
        $adminUser = $this->adminUserRepo->update($id, $request->validated());

        // $settings = $request->get('settings');

        // $adminUser->name = $request->name;
        // $emailService->settings = $settings;
        // $adminUser->save();

        return redirect()->route('admin-users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $adminUser = $this->adminUserRepo->find($id);

        $adminUser->delete();

        return redirect()->route('admin-users.index')->withSuccess('Admin User deleted');
    }
}
