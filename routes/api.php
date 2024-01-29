<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('api.')->namespace('Api')->group(static function (Router $apiRouter) {
    $apiRouter->middleware('web', 'auth:admin')->apiResource('payment-requests', 'PaymentRequestsController');
    $apiRouter->middleware('web', 'auth:admin')->apiResource('users', 'UsersController');
        // ->except(['update']);
    // $apiRouter->put('payment-requests', 'PaymentRequestsController@update')
    //     ->name('payment-requests.update');
});

// Route::middleware('web')->name('api.')->namespace('Api')->post('test', function (Request $request) {
//     \Log::info(request()->user());
//     \Log::info(auth('admin')->user());
//     return 'test succeeded!';
// })->name('test');