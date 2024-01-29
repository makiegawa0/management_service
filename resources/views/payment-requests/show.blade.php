@extends('layouts.app')

@section('title', __('Payment Request') . " : {$oPaymentRequest->user->full_name}")

@section('heading', __('Payment Requests'))

@section('content')
	{{-- <div class="row">
		<div class="col-sm-6"> --}}
			<div class="card">
				<div class="card-table">
					<div class="table">
						<table class="table">
							<tr>
								<td><b>{{ __('ID') }}</b></td>
								<td>{{ $oPaymentRequest->id }}</td>
							</tr>
							<tr>
								<td><b>{{ __('Created at') }}</b></td>
								<td>{{ $oPaymentRequest->created_at }}</td>
							</tr>
							<tr>
								<td><b>{{ __('Status') }}</b></td>
								<td>
									@if($oPaymentRequest->auto || $oPaymentRequest->manual)
										<span class="badge badge-success">{{ __($oPaymentRequest->status->name) }}</span>
										<span class="text-muted">{{ \App\Facades\Helper::displayDate($oPaymentRequest->confirm_callback_at) }}</span>
										
									@else
										<span class="badge badge-danger">{{ __($oPaymentRequest->status->name) }}</span>
									@endif
								</td>
							</tr>
							<tr>
								<td><b>{{ __('Payment Request Unique Code') }}</b></td>
								<td>{{ $oPaymentRequest->payment_request_unique_code }}</td>
							</tr>
							<tr>
								<td><b>{{ __('Amount') }}</b></td>
								<td>{{ number_format($oPaymentRequest->amount) }}</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		{{-- </div>
	</div> --}}
	
	{{-- <div class="row">
		<div class="col-sm-6"> --}}
			<div class="card">
				<div class="card-table">
					<div class="table">
						<table class="table">
							<tr>
								<td><b>{{ __('User name') }}</b></td>
								<td>{{ $oPaymentRequest->user->full_name }}</td>
							</tr>
							<tr>
								<td><b>{{ __('User Code') }}</b></td>
								<td>{{ $oPaymentRequest->user->user_code }}</td>
							</tr>
							<tr>
								<td><b>{{ __('Payment Code') }}</b></td>
								<td>{{ $oPaymentRequest->user->payment_code }}</td>
							</tr>
							<tr>
								<td><b>{{ __('Email') }}</b></td>
								<td>{{ $oPaymentRequest->user->email }}</td>
							</tr>
							<tr>
								<td><b>{{ __('Phone Number') }}</b></td>
								<td>{{ $oPaymentRequest->user->telephone }}</td>
							</tr>
							<tr>
								<td><b>{{ __('Alert Level') }}</b></td>
								<td>{{ $oPaymentRequest->user->alert_level->name }}</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		{{-- </div>
	</div> --}}
@endsection