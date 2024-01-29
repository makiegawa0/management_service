<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class UsersImportController extends Controller
{
    /**
     * @throws Exception
     */
    public function show(): View
    {
        $this->authorize('create', User::class);

        return view('users.import');
    }
}
