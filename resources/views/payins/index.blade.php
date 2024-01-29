@extends('layouts.app')

@section('title', __('Pay-In'))

@section('heading')
    {{ __('Pay-In') }}
@endsection

@section('content')

    @include('layouts.partials.loading')

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
                            @include('payins.partials.form')
						</form>
					</div>
				</div>
            </div>

            {{-- <form action="{{ route('sendportal.paymentRequests.index') }}" method="GET" class="form-inline mb-3 mb-md-0">
                <input class="form-control form-control-sm" name="name" type="text" value="{{ request('name') }}"
                        placeholder="{{ __('Search...') }}">

                <div class="mr-2">
                    <select name="status" class="form-control form-control-sm">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>{{ __('All') }}</option>
                        <option
                            value="subscribed" {{ request('status') == 'subscribed' ? 'selected' : '' }}>{{ __('Subscribed') }}</option>
                        <option
                            value="unsubscribed" {{ request('status') == 'unsubscribed' ? 'selected' : '' }}>{{ __('Unsubscribed') }}</option>
                    </select>
                </div>

                @if(count($tags))
                    <div id="tagFilterSelector" class="mr-2">
                        <select multiple="" class="selectpicker form-control form-control-sm" name="tags[]" data-width="auto">
                            @foreach($tags as $tagId => $tagName)
                                <option value="{{ $tagId }}" @if(in_array($tagId, request()->get('tags') ?? [])) selected @endif>{{ $tagName }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <button type="submit" class="btn btn-light btn-md">{{ __('Search') }}</button>

                @if(request()->anyFilled(['name', 'status']))
                    <a href="{{ route('sendportal.paymentRequests.index') }}"
                        class="btn btn-md btn-light">{{ __('Clear') }}</a>
                @endif
            </form> --}}
        @endslot

        @slot('right')
            <div class="btn-group mr-2">
                <button class="btn btn-md btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                    <i class="fa fa-bars color-gray-400"></i>
                </button>
                <div class="dropdown-menu">
                    @can('import', \App\Models\Payin::class)
                        <a href="{{ route('payins.import') }}" class="dropdown-item">
                            <i class="fa fa-upload color-gray-400 mr-2"></i> {{ __('Import PayIns') }}
                        </a>
                    @endcan
                    @can('export', \App\Models\Payin::class)
                        <a href="{{ route('payins.export', request()->query()) }}" class="dropdown-item">
                            <i class="fa fa-download color-gray-400 mr-2"></i> {{ __('Export PayIns') }}</a>
                    @endcan

                </div>
            </div>
            
        @endslot
        
    @endcomponent

    <div class="card">
        <div class="card-table table-responsive">
            @if ($oPayInLogs)
                <div class="col-md-12 row">
                @foreach ($oPayInLogs as $key => $value)
                    
                    <div class="col-md-2">{{ $key }} : </div>
                    <div class="col-md-10">{{ $value }}</div>
                @endforeach
                </div>
            @endif
            {{-- <input type="hidden" id="set-order-process-code" value="{{ route('api.payins.update') }}" /> --}}
            {{-- <input type="hidden" id="set-api-log-lock" value="{{ route('admin.set-api-log-lock') }}" /> --}}
            {{-- <input type="hidden" id="other-merchant-status" value="{{ __(config('master.incomingLogStatusList.'.config('const.INCOMING_LOG_STATUS_OTHER_MERCHANT'))) }}" /> --}}
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Registration time') }}</th>
                        <th>{{ __('Status') }}</th>

                        {{-- <th>{{ __('BPI Code') }}</th> --}}
                        {{-- <th>{{ __('TX No.') }}</th> --}}

                        <th>{{ __('Payment Code') }}</th>
                        <th>{{ __('Input') }}</th>
                        <th>{{ __('Amount') }}</th>
                        
                        {{-- <th>{{ __(' Actions') }}</th> --}}
                        
                    </tr>
                </thead>
                <tbody>
                    @forelse ($oPayIns as $oPayIn)
                        <tr>
                            <td class="align-middle text-center">{{ $oPayIn->id }}
                                <br />
                                @include('payins.partials.bank')
                                <br /> 
                                {{-- <br />({{ $oPayIn->bank_account->bank_info->name }})<br /> --}}
                                {{ $oPayIn->bank_account->account_name }}
                            </td>
                            <td>{{ $oPayIn->created_at }}</td>
                            <td>
                                @include('payins.partials.status')
                            </td>
                            <td>{{ $oPayIn->payment_request ? $oPayIn->payment_request->user->payment_code : '' }}</td>
                            <td>{{ $oPayIn->input }}</td>
                            <td>{{ number_format($oPayIn->amount) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%">
                                <p class="empty-table-text">{{ __('No PayIns Found') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('layouts.partials.pagination', ['records' => $oPayIns])

@endsection