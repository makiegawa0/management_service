{{-- @extends('layouts.admin') --}}
@extends('layouts.app')

@section('title', __('Payment Requests'))

@section('heading')
    {{ __('Payment Requests') }}
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
                            @include('payment-requests.partials.form')
						</form>
					</div>
				</div>
            </div>
        @endslot

        @slot('right')
            @can('download', \App\Models\PaymentRequest::class)
                @if (request()->start_time || request()->end_time || request()->cb_start_time || request()->cb_end_time )
                    <div class="btn-group mr-2">
                        <button class="btn btn-md btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                            <i class="fa fa-bars color-gray-400"></i>
                        </button>
                        <div class="dropdown-menu">
                            {{-- <a href="{{ route('sendportal.paymentRequests.import') }}" class="dropdown-item">
                                <i class="fa fa-upload color-gray-400 mr-2"></i> {{ __('Import paymentRequests') }}
                            </a> --}}
                            <a href="{{ route('admin.new-order.csv-download', request()->query()) }}" class="dropdown-item"> <i class="fa fa-upload color-gray-400 mr-2"></i> {{ __('Import Payment Requests') }}</a>
                            {{-- <a href="{{ route('sendportal.paymentRequests.export') }}" class="dropdown-item">
                                <i class="fa fa-download color-gray-400 mr-2"></i> {{ __('Export paymentRequests') }}
                            </a> --}}

                        </div>
                    </div>
                @else
                    <small class="text-muted">{{ __('Please specify a date range to download as CSV.') }}</small>
                    {{-- <a href="#" class="btn btn-success disabled" tabindex="-1" role="button" aria-disabled="true">{{ __('messages.order.csv_download') }}</a> --}}
                @endif
                {{-- <a class="btn btn-light btn-md mr-2" href="{{ route('sendportal.tags.index') }}">
                    <i class="fa fa-tag color-gray-400 mr-1"></i> {{ __('Tags') }}
                </a> --}}
                {{-- <a class="btn btn-primary btn-md btn-flat" href="{{ route('sendportal.paymentRequests.create') }}">
                    <i class="fa fa-plus mr-1"></i> {{ __('New paymentRequest') }}
                </a> --}}
            @endcan
            @if(request()->anyFilled(['user_code', 'payment_code']))
                <a class="btn btn-dark btn-md btn-flat float-right" id="issue-cb">{{ __('Callback') }}</a>

                {{-- <button class="btn btn-primary btn-md btn-flat">{{ __('messages.order.reverse_cb_modal_title') }}</button> --}}
            @endif
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-table table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Registration time') }}</th>
                        <th>{{ __('User name (uid)') }}</th>
                        <th>{{ __('Alert Level') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Payment Request Unique Code') }}</th>
                        <th>{{ __('Payment Code') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Payin ID') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($oPaymentRequests as $paymentRequest)
                    <tr>
                        <td class="align-middle text-center">{{ $paymentRequest->id }}</td>
                        {{-- <td>
                            <a href="{{ route('sendportal.paymentRequests.show', $paymentRequest->id) }}">
                                {{ $paymentRequest->email }}
                            </a>
                        </td> --}}
                        <td>{{ $paymentRequest->created_at }}</td>
                        {{-- <td>
                            <a href="{{ route('payment-requests.index') }}?keyword={{ $oOrder->user->fname}}">{{ $oOrder->user->fname}}</a>
                            <a href="{{ route('payment-requests.index') }}?keyword={{ $oOrder->user->lname }}">{{ $oOrder->user->lname }}</a>
                            <br />
                            <a href="{{ route('payment-requests.index') }}?c_id={{ $oOrder->user->c_id }}">({{ $oOrder->user->c_id }})</a>
                            @if (isset($oOrder->orderRequestLog->request_param))
                            <br />
                            (
                                @if (isset($oOrder->orderRequestLog->request_param->kyc))
                                    {{ config('master.kycList')[$oOrder->orderRequestLog->request_param->kyc] ?? '' }}
                                @endif
                                 /
                                @if (isset($oOrder->orderRequestLog->request_param->user_kind))
                                    {{ config('master.userKindList')[$oOrder->orderRequestLog->request_param->user_kind] ?? '' }}
                                @endif
                            )
                            @endif
                        </td> --}}
                        <td>{{ $paymentRequest->user->full_name }} ({{ $paymentRequest->user->user_code }})</td>
                        <td>
                            @include('payment-requests.partials.alert-level')
                        </td>
                        <td>{{ number_format($paymentRequest->amount) }}</td>
                        <td>{{ $paymentRequest->payment_request_unique_code }}</td>
                        <td>{{ $paymentRequest->user->payment_code }}</td>
                        <td>
                            @include('payment-requests.partials.status')
                        </td>
                        <td>
                        @if($paymentRequest->payin)
                            {{ $paymentRequest->payin->id }}
                        @endif
                        @if($paymentRequest->unprocessed)
                            <input type="text" name="payin_id_to_callback" id="payin-id-input-{{ $paymentRequest->id }}" class="form-control" autocomplete="off" value="{{ old('payin_id_to_callback') }}">
                            {{-- <span id="payin-id-static-{{ $paymentRequest->id }}">{{ $oNewOrder->incoming_log_id }}</span> --}}
                            
                            <a href="#" data-payment-request-id="{{ $paymentRequest->id }}" data-url="{{ route('api.payment-requests.update', $paymentRequest->id) }}" class="btn btn-md  btn-primary set-payin-id-button">{{ __('Set') }}</a>
                            <a href="#" data-payment-request-id="{{ $paymentRequest->id }}" data-url="{{ route('api.payment-requests.update', $paymentRequest->id) }}" style="display:none;" class="btn btn-secondary btn-preview btn-md unset-payin-id-button">{{ __('Unset') }}</a>
                            <a href="#" data-payment-request-id="{{ $paymentRequest->id }}" data-url="{{ route('api.payment-requests.update', $paymentRequest->id) }}" style="display:none;" class="btn btn-primary btn-md callback-button">{{ __('Callback') }}</a>
                        @else
                            {{ '-' }}
                        @endif
                        </td>
                        {{-- <td>
                            @forelse($paymentRequest->tags as $tag)
                                <span class="badge badge-light">{{ $tag->name }}</span>
                            @empty
                                -
                            @endforelse --}}
                        {{-- <td><span
                                title="{{ $paymentRequest->created_at }}">{{ $paymentRequest->created_at->diffForHumans() }}</span>
                        </td>
                        <td>
                            @if($paymentRequest->unsubscribed_at)
                                <span class="badge badge-danger">{{ __('Unsubscribed') }}</span>
                            @else
                                <span class="badge badge-success">{{ __('Subscribed') }}</span>
                            @endif
                        </td> --}}
                        <td>
                            <a href="{{ route('payment-requests.show', $paymentRequest->id) }}" class="btn btn-sm btn-light">
                                {{ __('Detail') }}
                            </a>
                            {{-- <button type="submit"
                            class="btn btn-xs btn-light delete-subscriber">{{ __('Delete') }}</button> --}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%">
                            <p class="empty-table-text">{{ __('No Payment Requests Found') }}</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('layouts.partials.pagination', ['records' => $oPaymentRequests])


<input type="hidden" name="id" value="" id="set-order-id">
{{-- <input type="hidden" id="set-order-memo-url" value="{{ route('admin.set-new-order-memo') }}" />
<div class="modal fade" id="setOrderMemoModal" tabindex="-1" role="dialog" aria-labelledby="setOrderMemoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setOrderMemoModalLabel">{{ __('messages.order.memo_modal_title') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <textarea id="memo" name="memo" class="form-control" rows="5"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('messages.modal.close_button') }}</button>
                <button type="button" class="btn btn-primary set-order-memo-button">{{ __('messages.modal.edit_button') }}</button>
            </div>
        </div>
    </div>
</div> --}}
@if (request()->anyFilled(['user_code', 'payment_code']))
<input type="hidden" id="userId" value="{{ $paymentRequest->user->id }}" />
<div class="modal fade" id="setCBModal" tabindex="-1" role="dialog" aria-labelledby="setCBModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setCBModalLabel">{{ __('Sned CallBack') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">UID</label>

                    <div class="col-md-6 col-form-label">
                        {{ $paymentRequest->user->user_code }}
                    </div>
                </div>
                <div class="form-group row">
                    <label for="payinId" class="col-md-4 col-form-label text-md-right">Payin ID</label>

                    <div class="col-md-6">
                        <input id="payinId" type="text" autocomplete="off" class="form-control @error('Payin ID') is-invalid @enderror" name="payinId">

                        @error('Payin ID')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        
                    </div>
                </div>  
                <div class="form-group row">
                    <label for="amount" class="col-md-4 col-form-label text-md-right">Amount</label>

                    <div class="col-md-6">
                        <input id="amount" type="text" autocomplete="off" class="form-control @error('Amount') is-invalid @enderror" name="amount">

                        @error('Amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer text-right">
                <div class="offset-2 col-10">
                    <button class="btn btn-md btn-secondary btn-preview" data-dismiss="modal">{{ __('Close') }}</button>
                    <a href="#" data-url="{{ route('api.payment-requests.store') }}" class="btn btn-primary btn-md" id="send-callback">{{ __('Send') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.12/dist/css/bootstrap-select.min.css">
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.12/dist/js/bootstrap-select.min.js"></script>
@endpush