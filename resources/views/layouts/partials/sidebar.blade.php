<div class="sidebar-inner mx-3">
    <ul class="nav flex-column mt-4">
        <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="fa-fw fas fa-home mr-2"></i><span>{{ __('Dashboard') }}</span>
            </a>
        </li>
        <li class="nav-item {{ request()->is('*payment-requests*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('payment-requests.index') }}">
                <i class="fa-regular fas fa-circle mr-2"></i><span>{{ __('Deposit Report') }}</span>
            </a>
        </li>
        @can('viewAny', \App\Models\ApiLog::class)
        <li class="nav-item {{ request()->is('*api-logs*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('api-logs.index') }}">
                <i class="fa-fw fas fa-bars-staggered mr-2"></i><span>{{ __('API Logs') }}</span>
            </a>
        </li>
        @endcan
        <li class="nav-item {{ request()->is('*payins*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('payins.index') }}">
                {{-- <i class="fa-solid fa-arrow-down"></i> --}}
                <i class="fa-fw fas fa-arrow-down mr-2"></i><span>{{ __('Pay-Ins') }}</span>
            </a>
        </li>
        {{-- 
        @can('calculate', \App\Models\PaymentRequest::class)
        <li class="nav-item {{ request()->is('*payment-requests*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('payment-requests.calculate') }}">
                <i class="fa-fw fas fa-user mr-2"></i><span>{{ __('Finance') }}</span>
            </a>
        </li>
        @endcan
        @can('calculate', \App\Models\PayIn::class)
        <li class="nav-item {{ request()->is('*payins*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('payins.calculate') }}">
                <i class="fa-fw fas fa-user mr-2"></i><span>{{ __('Deposits') }}</span>
            </a>
        </li>
        @endcan --}}
        @can('viewAny', \App\Models\User::class)
        <li class="nav-item {{ request()->is('*/users*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('users.index') }}">
                <i class="fa-fw fas fa-users mr-2"></i><span>{{ __('User List') }}</span>
            </a>
        </li>
        @endcan
        {{-- @can('viewAny', \App\Models\BankAccount::class)
        <li class="nav-item {{ request()->is('*bank-accounts*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('bank-accounts.index') }}">
                <i class="fa-fw fas fa-paper-plane mr-2"></i><span>{{ __('Bank Accounts') }}</span>
            </a>
        </li>
        @endcan
        @can('viewRiskLevels', \App\Models\User::class)
        <li class="nav-item {{ request()->is('*risk-levels*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('users.risk-levels.index') }}">
                <i class="fa-fw fas fa-envelope mr-2"></i><span>{{ __('Users Risk Levels') }}</span>
            </a>
        </li>
        @endcan --}}
        @can('viewAny', \App\Models\AdminUser::class)
        <li class="nav-item {{ request()->is('*admin-users*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin-users.index') }}">
                <i class="fa-fw fas fa-user-tie mr-2"></i><span>{{ __('Admin Tasks') }}</span>
            </a>
        </li>
        @endcan
        {{-- @can('viewAny', \App\Models\Setting::class)
        <li class="nav-item {{ request()->is('*settings*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('settings.index') }}">
                <i class="fa-fw fas fa-paper-plane mr-2"></i><span>{{ __('Settings') }}</span>
            </a>
        </li>
        @endcan --}}

        {{-- {!! \Sendportal\Base\Facades\Sendportal::sidebarHtmlContent() !!} --}}

    </ul>
</div>
