@extends('layouts.app')

@section('title', __('API Logs'))

@section('heading')
    {{ __('API Logs') }}
@endsection

@section('content')
    @component('layouts.partials.actions')

    @slot('left')
        <a class="btn btn-primary btn-md btn-flat" data-toggle="collapse" data-target="#collapse_search" aria-expanded="false" aria-controls="collapse_search">
            <i class="fas fa-search mr-1"></i> {{ __('Search') }}
        </a>
        <div class="collapse {{ request()->all() ? 'show' : '' }}" id="collapse_search">
            <div class="card">
                <div class="card-header">
                    {{ __('Search') }}
                </div>
                <div class="card-body">
                    <form class="form-horizontal">
                        @csrf
                        @include('api-log.partials.form')
                    </form>
                </div>
            </div>
        </div>
    @endslot

    @endcomponent

    <div class="card">
        <div class="card-table table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Payment Request Unique Code') }}</th>
                        <th>{{ __('Processed by') }}</th>
                        <th>{{ __('Result') }}</th>
                        {{-- <th>{{ __('Actions') }}</th> --}}
                    </tr>
                </thead>
                <tbody>
                @forelse ($oApiLogs as $oApiLog)
                    <tr>
                        <td class="align-middle text-center">{{ $oApiLog->id }}</td>
                        <td>{{ $oApiLog->created_at }}</td>
                        <td>{{ number_format($oApiLog->payment_request->amount) }}</td>
                        <td>
                            @include('api-log.partials.status')
                        </td>
                        <td>{{ $oApiLog->payment_request->payment_request_unique_code }}</td>
                        <td>
                            @if ($oApiLog->payment_request->auto)
                                <span class="badge badge-success">{{ $oApiLog->payment_request->status->name }}</span>
                            @elseif ($oApiLog->payment_request->manual)
                                <span class="badge badge-info">{{ $oApiLog->payment_request->status->name }}</span>
                            @endif
                        </td>
                        
                        <td>{{ $oApiLog->response }}</td>
                        
                        {{-- <td>
                            <a href="{{ route('api-logs.show', $oApiLog->id) }}" class="btn btn-sm btn-light">{{ __('Detail') }}</a>
                        </td> --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%">
                            <p class="empty-table-text">{{ __('No API Logs Found') }}</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('layouts.partials.pagination', ['records' => $oApiLogs])

</div>
@endsection
