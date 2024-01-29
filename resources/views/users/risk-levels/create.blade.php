@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('messages.alert_user_info.new') }}
                    <div class="float-right">
                        <a href="{{ route('admin.alert-user-info.index') }}" class="btn btn-primary">{{ __('messages.alert_user_info.list') }}</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <small class="col text-right"><span class="text-danger">*</span> {{ __('messages.form.required') }}</small>
                    </div>
                    <form method="POST">
                        @csrf

                        <div class="form-group row">
                            <label for="account_name" class="col-md-4 col-form-label text-md-right">{{ __('messages.alert_user_info.text') }}<span class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <input id="text" type="text" class="form-control @error('text') is-invalid @enderror" name="text" value="{{ old('text') }}" autocomplete="off">

                                @error('text')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="status" class="col-md-4 col-form-label text-md-right pt-0">{{ __('messages.alert_user_info.status') }}<span class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <div class="@error('status') is-invalid @enderror">
                                    @foreach (config('master.statusList') as $iKey => $sValue)
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

                        <div class="form-group row">
                            <label for="level" class="col-md-4 col-form-label text-md-right pt-0">{{ __('messages.alert_user_info.level') }}<span class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <div class="@error('level') is-invalid @enderror">
                                    @foreach (config('master.alertUserInfoLevelList') as $iKey => $sValue)
                                        <div class="form-check">
                                            <input type="radio" id="level{{ $iKey }}" name="level" value="{{ $iKey }}" class="form-check-input @error('level') is-invalid @enderror" {{ old('level', config('const.ALERT_USER_INFO_LEVEL_ALERT')) == $iKey ? "checked" : "" }}>
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

                        <div class="form-group row">
                            <label for="content" class="col-md-4 col-form-label text-md-right">{{ __('messages.alert_user_info.memo') }}</label>

                            <div class="col-md-6">
                                <textarea id="memo" name="memo" class="form-control @error('memo') is-invalid @enderror" rows="5">{{ old('memo') }}</textarea>

                                @error('memo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-9 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('messages.alert_user_info.new_button') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
