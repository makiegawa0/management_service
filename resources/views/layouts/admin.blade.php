<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('const.SERVER_UNIQUE_CODE') }} {{ __('messages.menu') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}" defer></script>
    <script src="{{ asset('js/admin.js'.'?v=20220331') }}" defer></script>
    <script src="{{ asset('js/datepicker/'.__('app.js_datepicker').'?v=20200818') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.css'.'?v=20220402') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin/'.config('const.SERVER_UNIQUE_CODE').'.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container" style="max-width: 1500px;">
                <a class="navbar-brand" href="{{ route('payment-requests.index') }}">
                    {{ config('const.SERVER_UNIQUE_CODE') }} {{ __('messages.menu') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @guest
                        @else
                            @if (config('const.ADMIN_HEADER_NEW_ONLY_FLG'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('payment-requests.index') }}">{{ __('messages.new_order.head') }}</a>
                            </li>
                            @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navOrder" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ __('messages.order.head') }}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navOrder">
                                    <a class="dropdown-item" href="{{ route('admin.order') }}">{{ __('messages.order.head') }}</a>
                                    <a class="dropdown-item" href="{{ route('payment-requests.index') }}">{{ __('messages.new_order.head') }}</a>
                                </div>
                            </li>
                            @endif
                            {{-- @if (Auth::user()->isAdmin() || Auth::user()->isAccountant() || Auth::user()->isCallbackManager() || Auth::user()->isViewer())
                                <li class="nav-item" style="display:none;">
                                    <a class="nav-link" href="{{ route('admin.order-log') }}">{{ __('messages.order_log.head') }}</a>
                                </li>
                            @endif --}}
                            @if (Auth::user()->isAdmin() || Auth::user()->isViewer() || Auth::user()->isCallbackManager() || Auth::user()->isAccountant() || Auth::user()->isManager())
                                {{-- @if (config('const.ADMIN_HEADER_NEW_ONLY_FLG')) --}}
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('api-logs.index') }}">{{ __('messages.new_callback_log.head') }}</a>
                                </li>
                                {{-- @else
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navCallbackLog" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ __('messages.callback_log.head') }}
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navCallbackLog">
                                        <a class="dropdown-item" href="{{ route('admin.callback-log') }}">{{ __('messages.callback_log.head') }}</a>
                                        <a class="dropdown-item" href="{{ route('admin.new-callback-log') }}">{{ __('messages.new_callback_log.head') }}</a>
                                    </div>
                                </li>
                                @endif --}}
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('payins.index') }}">{{ __('messages.callback_request_log.head') }}</a>
                            </li>
                            @if (Auth::user()->isAdmin() || Auth::user()->isAccountant())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('payment-requests.calculate') }}">{{ __('messages.calc.head') }}</a>
                                </li>
                            @endif
                            @if (Auth::user()->isAdmin() || Auth::user()->isAccountant() || Auth::user()->isViewer() || Auth::user()->isManager())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('api-logs.calculate') }}">{{ __('messages.callback_calc.head') }}</a>
                                </li>
                            @endif
                            @if (Auth::user()->isAdmin() || Auth::user()->isOperator() || Auth::user()->isCallbackManager() || Auth::user()->isManager())
                                @if (config('const.ADMIN_HEADER_NEW_ONLY_FLG'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('users.index') }}">{{ __('messages.new_user.head') }}</a>
                                </li>
                                @else
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="navUser" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ __('messages.user.head') }}
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navUser">
                                            <a class="dropdown-item" href="{{ route('admin.user.index') }}">{{ __('messages.user.head') }}</a>
                                            <a class="dropdown-item" href="{{ route('users.index') }}">{{ __('messages.new_user.head') }}</a>
                                        </div>
                                    </li>
                                @endif
                            @endif
                            @if (Auth::user()->isAdmin() || Auth::user()->isAccountant())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('bank-accounts.index') }}">{{ __('messages.transfer_account.head') }}</a>
                                </li>
                            @endif
                            {{-- @if (Auth::user()->isAdmin())
                                <li class="nav-item" style="display:none;">
                                    <a class="nav-link" href="{{ route('admin.ng-word.index') }}">{{ __('messages.ng_word.head') }}</a>
                                </li>
                            @endif --}}
                            {{-- @if (Auth::user()->isAdmin() || Auth::user()->isOperator() || Auth::user()->isCallbackManager())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.maintenance.index') }}">{{ __('messages.maintenance.head') }}</a>
                                </li>
                            @endif --}}
                            {{-- @if (Auth::user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.movie-user.index') }}">{{ __('messages.movie_user.head') }}</a>
                                </li>
                            @endif --}}
                            {{-- @if (Auth::user()->isAdmin()) --}}
                                {{-- <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.api-user.index') }}">{{ __('messages.api_user.head') }}</a>
                                </li> --}}
                                {{-- @if (config('const.ADMIN_HEADER_NEW_ONLY_FLG'))
                                <li class="nav-item" style="display:none;">
                                    <a class="nav-link" href="{{ route('admin.new-contact.index') }}">{{ __('messages.new_contact.head') }}</a>
                                </li>
                                @else
                                <li class="nav-item dropdown" style="display:none;">
                                    <a class="nav-link dropdown-toggle" href="#" id="navUser" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ __('messages.contact.head') }}
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navUser">
                                        <a class="dropdown-item" href="{{ route('admin.contact.index') }}">{{ __('messages.contact.head') }}</a>
                                        <a class="dropdown-item" href="{{ route('admin.new-contact.index') }}">{{ __('messages.new_contact.head') }}</a>
                                    </div>
                                </li>
                                @endif --}}
                            {{-- @endif --}}
                            @if (Auth::user()->isAdmin() || Auth::user()->isCallbackManager() || Auth::user()->isManager())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('users.risk-levels.index') }}">{{ __('messages.alert_user_info.head') }}</a>
                                </li>
                            @endif
                            @if (Auth::user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin-users.index') }}">{{ __('messages.admin_user.head') }}</a>
                                </li>
                            @endif
                            @can('view', \App\Models\Setting::class)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('settings.index') }}">{{ __('messages.settings.title') }}</a>
                                </li>
                            @endcan
                        @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @include('layouts.admin-message')
            @yield('content')
        </main>
        <span style='display:none' id='ajax_fail_message'>{{ __('api.ajax_fail_message') }}</span>

        <span style='display:none' id='ajax_callback_confirm_message'>{{ __('api.callback_confirm') }}</span>
        <span style='display:none' id='ajax_callback_success_message'>{{ __('api.callback_success') }}</span>
        <span style='display:none' id='ajax_callback_failure_message'>{{ __('api.callback_failure') }}</span>

        <span style='display:none' id='ajax_supported_confirm_message'>{{ __('api.supported_confirm') }}</span>
        <span style='display:none' id='ajax_supported_success_message'>{{ __('api.supported_success') }}</span>
        <span style='display:none' id='ajax_supported_failure_message'>{{ __('api.supported_failure') }}</span>
        <span style='display:none' id='ajax_supported_message'>{{ __('api.supported') }}</span>

        <span style='display:none' id='ajax_del_order_process_code_confirm_message'>{{ __('api.del_order_process_code_confirm') }}</span>
        <span style='display:none' id='ajax_del_order_process_code_success_message'>{{ __('api.del_order_process_code_success') }}</span>
        <span style='display:none' id='ajax_del_order_process_code_failure_message'>{{ __('api.del_order_process_code_failure') }}</span>

        <span style='display:none' id='ajax_set_order_process_code_confirm_message'>{{ __('api.set_order_process_code_confirm') }}</span>
        <span style='display:none' id='ajax_set_order_process_code_success_message'>{{ __('api.set_order_process_code_success') }}</span>
        <span style='display:none' id='ajax_set_order_process_code_failure_message'>{{ __('api.set_order_process_code_failure') }}</span>

        <span style='display:none' id='ajax_set_callback_request_log_id_success_message'>{{ __('api.set_callback_request_log_id_success') }}</span>
        <span style='display:none' id='ajax_set_callback_request_log_id_failure_message'>{{ __('api.set_callback_request_log_id_failure') }}</span>
        <span style='display:none' id='ajax_set_callback_request_log_id_not_exist_failure_message'>{{ __('api.set_callback_request_log_id_not_exist_failure') }}</span>

        <span style='display:none' id='ajax_set_name_kana_success_message'>{{ __('api.set_name_kana_success') }}</span>
        <span style='display:none' id='ajax_set_name_kana_failure_message'>{{ __('api.set_name_kana_failure') }}</span>
    </div>
</body>
</html>
