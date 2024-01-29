<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Rap2hpoutre\FastExcel\FastExcel;

class UsersController extends Controller
{
    /** @var UserRepository */
    private $userRepo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     *  @throws Exception
     */
    public function index(Request $oRequest): View
    {
        $this->authorize('viewAny', User::class);

        $oUsers = $this->userRepo->paginate(
            'id',
            [],
            $oRequest->disp_number ?? config('const.default_paginate_number'),
            request()->all()
        )->withQueryString();
        $setting = Setting::first();
        $alertLevels = $this->userRepo->getAlertLevels();
        // dd($statuses);
        $selectedAlertLevels = ($oRequest->has('alertLevels') && !empty($oRequest['alertLevels'])) ? $oRequest['alertLevels'] : [];

        return view('users.index', compact('oUsers', 'setting', 'alertLevels', 'selectedAlertLevels'));
    }

    /**
     * @throws Exception
     */
    public function closed(): View
    {
        $this->authorize('viewAny', User::class);

        $params = request()->all();
        $params['closed'] = true;
        $oUsers = $this->userRepo->paginate(
            'idDesc',
            [],
            config('const.ADMIN_NEW_USER_DISPLAY_MAX'),
            $params
        );

        return view('users.index', compact('oUsers'));
    }

    /**
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
        $this->authorize('download', User::class);

        $oUsers = $this->userRepo->all('id');

        if (! $oUsers->count()) {
            return redirect()->route('users.index')->withErrors(__('There are no users to export.'));
        }

        return (new FastExcel($oUsers))
            ->download(sprintf('users-%s.csv', date('Y-m-d-H-m-s')), static function ($user) {
                return [
                    'id' => $user->id,
                    __('messages.user.user_name') => $user->fname.' '.$user->lname,
                    __('messages.user.uid') => $user->c_id,
                    __('messages.user.email') => $user->email,
                    __('messages.user.tel') => $user->tel,
                    __('messages.user.process_code') => $user->process_code,
                    __('messages.user.keyword') => $user->keyword,
                    __('messages.user.kyc') => $user->kyc,
                    __('messages.user.user_kind') => $user->user_kind,
                    __('messages.user.created_at') => $user->created_at,
                    __('messages.user.last_order_created_at') => $user->last_order_created_at,
                    __('messages.user.last_order_referrer') => $user->last_order_referrer,
                ];
            });
    }

    /**
     * Remove the user from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', User::class);

        $oUser = User::find($id);
        // if (is_null($oUser)) {
        //     session()->flash('flashFailure', __('flash.user.unfound'));

        //     return redirect()->route('users.index');
        // }

        $oUser->delete();

        // try {
        //     DB::beginTransaction();
        //     $oUser->delete();
        //     // NewOrder::where('user_id', $oUser->id)->delete();
        //     // AlertUserInfo::where('user_id', $oUser->id)->delete();
        //     DB::commit();
        //     session()->flash('flashSuccess', __('flash.user.destroy.success'));
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     Log::error(__('flash.user.destroy.error_log').$e->getMessage());
        //     session()->flash('flashFailure', __('flash.user.destroy.failure').$e->getMessage());
        // }

        return redirect()->route('users.index')->withSuccess('User deleted');
    }

    public function destroyAll()
    {
        try {
            User::query()->delete();
            session()->flash('flashSuccess', __('flash.destroy_all.destroy.success'));
        } catch (\Exception $e) {
            Log::error(__('flash.user.destroy.error_log').$e->getMessage());
            session()->flash('flashFailure', __('flash.destroy_all.destroy.failure').$e->getMessage());
        }

        return redirect()->route('users.index');
    }
}
