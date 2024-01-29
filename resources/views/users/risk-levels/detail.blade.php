@extends('layouts.admin')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">{{ __('messages.alert_user_info_detail.title') }}
					<div class="float-right">
						<a href="{{ route('admin.alert-user-info.index') }}" class="btn btn-primary">{{ __('messages.alert_user_info.list') }}</a>
					</div>
				</div>

				<div class="card-body">
					<div class="form-group row">
						<label class="col-md-3 col-form-label text-md-right">ID</label>

						<div class="col-md-9 col-form-label">
							{{ $oAlertUserInfo->id }}
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-3 col-form-label text-md-right">{{ __('messages.alert_user_info.created_at') }}</label>

						<div class="col-md-9 col-form-label">
							{{ $oAlertUserInfo->created_at }}
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-3 col-form-label text-md-right">{{ __('messages.user.uid') }}</label>

						<div class="col-md-9 col-form-label">
							{{ $oAlertUserInfo->user->c_id }}
						</div>
					</div>
          <div class="form-group row">
						<label class="col-md-3 col-form-label text-md-right">{{ __('messages.user.user_name') }}</label>

						<div class="col-md-9 col-form-label">
							{{ $oAlertUserInfo->user->fname}} {{ $oAlertUserInfo->user->lname }}
						</div>
					</div>
          <div class="form-group row">
						<label class="col-md-3 col-form-label text-md-right">{{ __('messages.user.email') }}</label>

						<div class="col-md-9 col-form-label">
							{{ $oAlertUserInfo->user->email}}
						</div>
					</div>
          <div class="form-group row">
						<label class="col-md-3 col-form-label text-md-right">{{ __('messages.order_detail.tel') }}</label>

						<div class="col-md-9 col-form-label">
							{{ $oAlertUserInfo->user->tel}}
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-3 col-form-label text-md-right">{{ __('messages.alert_user_info.level') }}</label>

						<div class="col-md-9 col-form-label">
							{{ __(config('master.alertUserInfoLevelList.'.$oAlertUserInfo->level)) }}
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-3 col-form-label text-md-right">{{ __('messages.alert_user_info.memo') }}</label>

						<div style="white-space:pre;" class="col-md-9 col-form-label">{{ $oAlertUserInfo->memo }}</div>
					</div>
				</div>
			</div>

			@if ($oLoggedInUser->isAdmin() || $oLoggedInUser->isManager())
			<div class="card mt-5">
				<div class="card-header">{{ __('messages.alert_user_info_log.title') }}</div>

				<div class="card-body">
					{{ __('messages.alert_user_info_log.count') }}: {{ $oAlertUserInfo->log->count() }}
				</div>
        <div class="mx-auto">
          {{ $aAlertUserInfoLogs->appends(request()->query())->links() }}
        </div>
      <table class="table">
          <thead>
              <tr>
                  <th>{{ __('messages.alert_user_info_log.admin_user_name') }}</th>
                  <th>{{ __('messages.alert_user_info_log.old_alert_level') }}</th>
                  <th>{{ __('messages.alert_user_info_log.new_alert_level') }}</th>
                  <th>{{ __('messages.alert_user_info_log.created_at') }}</th>
              </tr>
          </thead>
          <tbody>
              @if ($aAlertUserInfoLogs->isNotEmpty())
                  @foreach ($aAlertUserInfoLogs as $oAlertUserInfoLog)
                      <tr>
                          <td class="align-middle">{{ $oAlertUserInfoLog->admin_user->name }}</td>
                          <td class="align-middle">@foreach(json_decode($oAlertUserInfoLog->before) as $sKey => $sItem)
                            <p style="white-space:pre;">{{ $sKey }}: {{ $sItem }}</p>
                            @endforeach</td>
                          <td class="align-middle">
                          @foreach(json_decode($oAlertUserInfoLog->after) as $sKey => $sItem)
                            <p style="white-space:pre;">{{ $sKey }}: {{ $sItem }}</p>
                          @endforeach
                          </td>
                          <td class="align-middle">{{ $oAlertUserInfoLog->created_at }}</td>
                      </tr>
                  @endforeach
              @else
                  <tr>
                      <td colspan="8" class="align-middle text-center">{{ __('messages.search.not_exist') }}</td>
                  </tr>
              @endif
              {{-- <div class="card-body">
                @foreach ($oNewOrder->orderRequestLog->request_param as $sKey => $sItem)
                <div class="form-group row">
                  <label class="col-md-3 col-form-label text-md-right">{{ $sKey }}</label>
      
                  <div class="col-md-9 col-form-label">
                    {{ $sItem }}
                  </div>
                </div>
                @endforeach
              </div> --}}
          </tbody>
      </table>
			</div>
			@endif
		</div>
	</div>
</div>
@endsection
