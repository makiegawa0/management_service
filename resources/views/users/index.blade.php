@extends('layouts.app')

@section('title', __('Users'))

@section('heading')
    {{ __('Users') }}
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
							@include('users.partials.form')
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
						<th>{{ __('User Name') }}</th>
						<th>{{ __('User Code') }}</th>
						<th>{{ __('Hurigana') }}</th>
						<th>{{ __('Alert Level') }}</th>
						<th>{{ __('Payment Code') }}</th>
						<th>{{ __('Actions') }}</th>
					</tr>
				</thead>
				<tbody>
					@forelse($oUsers as $oUser)
						<tr>
							<td>{{ $oUser->id }}</td>
							<td>{{ $oUser->first_name }} {{ $oUser->last_name }}</td>
							<td>
								{{ $oUser->user_code }}
								{{-- (
									@if (isset($oUser->kyc) && $oUser->kyc)
										{{ $oUser->kyc }}
									@endif
									/
									@if (isset($oUser->user_kind) && $oUser->user_kind)
										{{ $oUser->user_kind }}
									@endif
								) --}}
							</td>
							<td>
								<input type="text" class="form-control user-hurigana" data-url="{{ route('api.users.update', $oUser->id) }}" autocomplete="off" value="{{ $oUser->hurigana }}">
							</td>
							<td>
								@include('users.partials.alert-level')
							</td>
							<td>
								{{ $oUser->payment_code }}
							</td>
							<td>
								<form action="{{ route('users.destroy', $oUser) }}" method="POST" id="delete-user">
									@csrf
									@method('DELETE')

									@can('update', \App\Models\User::class)
										<a class="btn btn-sm btn-light"
										href="{{ route('users.edit', $oUser->id) }}">{{ __('Edit') }}</a>
									@endcan

									@if ($setting->user_individual_deletion)
										@can('delete', \App\Models\User::class)
											<button type="submit" class="btn btn-xs btn-light">{{ __('Delete') }}</button>
										@endcan
									@endif
								</form>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="100%">
								<p class="empty-table-text">{{ __('No Admin User Found') }}</p>
							</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>

	@include('layouts.partials.pagination', ['records' => $oUsers])

@endsection

{{-- <form method="post" action="{{ route('users.destroy') }}" id="delete-form">
	{{ csrf_field() }}
	@method('DELETE')
	<input type="hidden" name="id" value="" id="delete-user-id">
	<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
					<div class="modal-content">
							<div class="modal-header">
									<h5 class="modal-title" id="deleteUserModalLabel">{{ __('messages.modal.delete_title') }}</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
							</div>
							<div class="modal-body">
									{{ __('messages.modal.delete_content') }}
							</div>
							<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('messages.modal.close_button') }}</button>
									<button type="submit" class="btn btn-primary">{{ __('messages.modal.delete_button') }}</button>
							</div>
					</div>
			</div>
	</div>
</form>

<form action="{{ route('users.destroy.all') }}" method="POST" id="delete-form">
	@csrf
	@method('DELETE')
	<div class="modal fade" id="deleteAllUsersModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
				<div class="modal-content">
						<div class="modal-header">
								<h5 class="modal-title" id="deleteUserModalLabel">{{ __('messages.modal.delete_title') }}</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
						</div>
						<div class="modal-body">
								Are you sure to delete all users?
						</div>
						<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('messages.modal.close_button') }}</button>
								<button type="submit" class="btn btn-primary">{{ __('messages.modal.delete_button') }}</button>
						</div>
				</div>
		</div>
	</div>
</form> --}}
