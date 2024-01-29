<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UsersRiskLevelController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $oRequests)
    {
        $this->authorize('viewRiskLevels', User::class);

        $alertLevels = [
            config('const.ALERT_USER_INFO_LEVEL_ALERT'),
            config('const.ALERT_USER_INFO_LEVEL_BLOCK'),
        ];

        if (! $oRequests->has('alert_level')) {
            $oRequests->merge(['alert_level' => $alertLevels]);
        }

        $oUsers = $this->userRepo->paginate(
            'id',
            ['alertUserInfo'],
            config('const.ADMIN_ALERT_USER_INFO_DISPLAY_MAX'),
            $oRequests->all()
        );//->withQueryString();

        return view('users.risk-levels.index')
            ->with(compact(
                'oUsers',
            ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
}
