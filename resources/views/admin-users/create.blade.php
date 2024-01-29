@extends('layouts.admin')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">{{ __('messages.admin_user.new') }}
					<div class="float-right">
						<a href="{{ route('admin.admin-user.index') }}" class="btn btn-primary">{{ __('messages.admin_user.list') }}</a>
					</div>
				</div>

				<div class="card-body">
					<div class="row">
						<small class="col text-right"><span class="text-danger">*</span> {{ __('messages.form.required') }}</small>
					</div>
					<form method="POST">
						@csrf

						<div class="form-group row">
							<label for="name" class="col-md-4 col-form-label text-md-right">{{ __('messages.admin_user.user_name') }}<span class="text-danger">*</span></label>

							<div class="col-md-6">
								<input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name">

								@error('name')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label for="email" class="col-md-4 col-form-label text-md-right">{{ __('messages.admin_user.email') }}<span class="text-danger">*</span></label>

							<div class="col-md-6">
								<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">

								@error('email')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label for="password" class="col-md-4 col-form-label text-md-right">{{ __('messages.admin_user.password') }}<span class="text-danger">*</span></label>

							<div class="col-md-6">
								<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">

								@error('password')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('messages.admin_user.password_confirm') }}<span class="text-danger">*</span></label>

							<div class="col-md-6">
								<input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
							</div>
						</div>

						<div class="form-group row">
							<label for="status" class="col-md-4 col-form-label text-md-right pt-0">{{ __('messages.admin_user.status') }}<span class="text-danger">*</span></label>

							<div class="col-md-6">
								<div class="@error('status') is-invalid @enderror">
									@foreach ($aStatus as $iKey => $sValue)
										<div class="form-check form-check-inline">
											<input type="radio" id="status{{ $iKey }}" name="status" value="{{ $iKey }}" class="form-check-input @error('status') is-invalid @enderror" {{ old('status', config('const.STATUS_OK')) == $iKey ? "checked" : "" }}>
											<label for="status{{ $iKey }}" class="form-check-label">{{ __($sValue) }}</label>
										</div>
									@endforeach
								</div>

								@error('status')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>

						@if ($oAdminUserLogin->isAdmin())
							<div class="form-group row">
								<label for="level" class="col-md-4 col-form-label text-md-right pt-0">{{ __('messages.admin_user.level') }}<span class="text-danger">*</span></label>

								<div class="col-md-6">
									<div class="@error('level') is-invalid @enderror">
										@foreach ($aAdminUserLevel as $iKey => $sValue)
											<div class="form-check">
												<input type="radio" id="level{{ $iKey }}" name="level" value="{{ $iKey }}" class="form-check-input @error('level') is-invalid @enderror" {{ old('level', config('const.ADMIN_USER_LEVEL_ADMIN')) == $iKey ? "checked" : "" }}>
												<label for="level{{ $iKey }}" class="form-check-label">{{ __($sValue) }}</label>
											</div>
										@endforeach
									</div>

									@error('level')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
						@else
							<input type="hidden" name="level" value="{{ config('const.ADMIN_USER_LEVEL_VIEWER') }}">
						@endif

						<div class="form-group row mb-0">
							<div class="col-md-9 offset-md-4">
								<button type="submit" class="btn btn-primary">
									{{ __('messages.admin_user.new_button') }}
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>

            <div class="card mt-3">
				<div class="card-body">
					<div class="form-group row">
						<label for="generate-password" class="col-md-4 col-form-label text-md-right">{{ __('messages.admin_user.password_generate') }}</label>

						<div class="col-md-6">
							<input id="generate-password" type="text" class="form-control" name="generate-password" value="" autocomplete="off">
						</div>
					</div>

					<div class="form-group row mb-0">
						<div class="col-md-9 offset-md-4">
							<button id="generate" type="button" class="btn btn-primary">
								{{ __('messages.admin_user.generate_button') }}
							</button>
						</div>
					</div>
				</div>
            </div>
		</div>
	</div>
</div>

@endsection
