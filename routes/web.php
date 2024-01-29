<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Auth
Route::prefix(config('const.auth.path'))->namespace('Auth')->group(
    static function (Router $authRouter) {
        // Login
        $authRouter->get('/login', 'LoginController@showLoginForm')->name('login');
        $authRouter->post('/login', 'LoginController@login');
    }
);

Route::prefix(config('const.auth.path'))->middleware('auth:admin')->group(
    static function (Router $appRouter) {
        $appRouter->get('/', 'DashboardController@index')->name('dashboard');

        // PaymentRequests
        $appRouter->resource('payment-requests', 'PaymentRequestsController')->except(['show']);
        $appRouter->name('payment-requests.')->prefix('payment-requests')->group(static function (
            Router $paymentRequestRouter
        ) {
            $paymentRequestRouter->get('calculator', 'PaymentRequestsController@calculate')->name('calculate');
            $paymentRequestRouter->get('{id}', 'PaymentRequestsController@show')->name('show');
        });

        // API Log
        $appRouter->name('api-logs.')->prefix('api-logs')->group(static function (
            Router $apiLogRouter
        ) {
            $apiLogRouter->get('calculator', 'ApiLogController@calculate')->name('calculate');
        });
        $appRouter->resource('api-logs', 'ApiLogController');

        // PayIns
        $appRouter->name('payins.')->prefix('payins')->namespace('PayIns')->group(static function (
            Router $payinRouter
        ) {
            $payinRouter->get('import', 'PayInsImportController@index')->name('import');
            $payinRouter->post('import/preview', 'PayInsImportController@show')->name('import.show');        

            $payinRouter->post('import', 'PayInsImportController@store')->name('import.store');
            
            $payinRouter->get('export', 'PayInController@export')->name('export');
        });
        $appRouter->resource('payins', 'PayIns\PayInController');

        // Users
        $appRouter->name('users.')->prefix('users')->namespace('Users')->group(static function (
            Router $userRouter
        ) {
            $userRouter->get('import', 'UsersImportController@show')->name('import');
            $userRouter->get('export', 'UsersController@export')->name('export');
            $userRouter->get('closed', 'UsersController@closed')->name('closed');
            $userRouter->middleware('non-prod-env')->delete('all', 'UsersController@destroyAll')->name('destroy.all');

            $userRouter->get('risk-levels', 'UsersRiskLevelController@index')->name('risk-levels.index');
            $userRouter->get('risk-levels/{id}/edit', 'UsersRiskLevelController@edit')->name('risk-levels.edit');
            $userRouter->get('risk-levels/{id}/show', 'UsersRiskLevelController@show')->name('risk-levels.show');
            $userRouter->post('/risk-levels/notes', 'UsersRiskLevelController@notes')->name('risk-levels.notes');
        });
        $appRouter->resource('users', 'Users\UsersController');

        // Bank Accounts
        // $appRouter->resource('bank-accounts', 'BankAccountsController')->except(['update']);
        // $appRouter->prefix('bank-accounts')->put('{id}', 'BankAccountsController@update')->name('bank-accounts.update');

        // Admin Users
        $appRouter->resource('admin-users', 'AdminUserController')->except(['edit']);
        $appRouter->name('admin-users.')->prefix('admin-users')->group(static function (
            Router $adminUserRouter
        ) {
            $adminUserRouter->get('{id}/edit', 'AdminUserController@edit')->name('edit');
            // $adminUserRouter->delete('', 'AdminUserController@destroy')->name('destroy');
        });

        // Settings
        // $appRouter->resource('settings', 'SettingsController')->only(['index']);

        $appRouter->namespace('Auth')->group(
            static function (Router $authRouter) {
                // Logout
                $authRouter->post('logout', 'LoginController@logout')->name('logout');

                // Profile. TODO: add middleware('verified') to increase security
                $authRouter->middleware('auth:admin')->name('profile.')->prefix('profile')->group(
                    static function (
                        Router $profileRouter
                    ) {
                        $profileRouter->get('/', 'ProfileController@show')->name('show');
                        $profileRouter->get('/edit', 'ProfileController@edit')->name('edit');
                        $profileRouter->put('/', 'ProfileController@update')->name('update');

                        // Password
                        $profileRouter->name('password.')->prefix('password')->group(
                            static function (
                                Router $passwordRouter
                            ) {
                                $passwordRouter->get('/edit', 'ChangePasswordController@edit')->name('edit');
                                $passwordRouter->put('/', 'ChangePasswordController@update')->name('update');
                            }
                        );
                    }
                );
            }
        );
        

    }
);

    



