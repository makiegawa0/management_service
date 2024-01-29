<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        // allow requests as a guest of admin
        $this->middleware('guest:admin')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        // if ( $user->isAdmin() ) {// do your magic here
        //     return redirect()->route('dashboard');
        // }

        return redirect()->route('dashboard');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $oRequest)
    {
        $this->guard()->logout();

        $oRequest->session()->invalidate();

        $oRequest->session()->regenerateToken();

        if ($response = $this->loggedOut($oRequest)) {
            return $response;
        }

        return $oRequest->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/'.config('const.auth.path').'/login');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }
}
