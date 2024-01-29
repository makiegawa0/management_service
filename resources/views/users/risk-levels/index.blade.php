@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('messages.alert_user_info.search') }}
                    <button class="btn btn-primary ml-2" type="button" data-toggle="collapse" data-target="#collapse_search" aria-expanded="false" aria-controls="collapse_search">{{ __('messages.search.search_show') }}</button>
                </div>
                <div class="collapse {{ request()->all() ? 'show' : '' }}" id="collapse_search">
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <label for="id" class="col-md-2 col-form-label">ID</label>
                                <div class="col-md-10">
                                    <input type="text" id="id" class="form-control" name="id" value="{{ request()->id }}">
                                    <small class="text-muted">{{ sprintf(__('messages.form.half_width'), 'ID') }}</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="text" class="col-md-2 col-form-label">{{ __('messages.alert_user_info.uid') }}</label>
                                <div class="col-md-10">
                                    <input type="text" id="text" class="form-control" name="uid" value="{{ request()->uid }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="level" class="col-md-2 col-form-label">{{ __('messages.alert_user_info.level') }}</label>
                                <div class="col-md-10">
                                    <select id="level" class="form-control" multiple name="alert_level[]" size="3">
                                        @foreach (config('master.alertUserInfoLevelList') as $iKey => $sValue)
                                            <option value="{{ $iKey }}" {{ request()->alert_level && in_array($iKey, request()->alert_level) ? "selected" : "" }}>{{ __($sValue) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- <div class="form-group row">
                                <label for="status" class="col-md-2 col-form-label">{{ __('messages.alert_user_info.status') }}</label>
                                <div class="col-md-10">
                                    <select id="status" class="form-control" name="status">
                                        <option value="">{{ __('messages.form.please_select') }}</option>
                                        @foreach (config('master.statusList') as $iKey => $sValue)
                                            <option value="{{ $iKey }}" {{ request()->status == $iKey && request()->status != '' ? "selected" : "" }}>{{ __($sValue) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}

                            <div class="text-right">
                                <button type="button" class="btn btn-warning clear-button">{{ __('messages.search.clear_button') }}</button>
                                <button type="submit" class="btn btn-primary">{{ __('messages.search.button') }}</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('messages.alert_user_info.list') }}
                    {{-- @if ($oLoggedInUser->isAdmin() || $oLoggedInUser->isCallbackManager())
                    <div class="float-right">
                        <a href="{{ route('admin.alert-user-info.create') }}" class="btn btn-info">{{ __('messages.alert_user_info.new') }}</a>
                    </div>
                    @endif --}}
                </div>
                <div class="card-body">
                    {{ __('messages.search.result') }} {{ sprintf(__('messages.search.item'), $oUsers->total()) }}
                </div>
                <div class="mx-auto">
                    {{ $oUsers->appends(request()->query())->links() }}
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ __('messages.alert_user_info.uid') }}</th>
                            {{-- <th>{{ __('messages.alert_user_info.status') }}</th> --}}
                            <th>{{ __('messages.alert_user_info.level') }}</th>
                            <th>{{ __('messages.alert_user_info.name') }}</th>
                            @can('addNote', \App\Models\NewUser::class)
                                <th>{{ __('messages.alert_user_info.memo') }}</th>
                                <th>{{ __('messages.alert_user_info.detail') }}</th>
                                <th>{{ __('messages.alert_user_info.edit') }}</th>
                                {{-- <th>{{ __('messages.alert_user_info.delete_button') }}</th> --}}
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @if ($oUsers->isNotEmpty())
                            @foreach ($oUsers as $oUser)
                                <tr>
                                    <td class="align-middle">{{ $oUser->id }}</td>
                                    <td class="align-middle">{{ $oUser->c_id }}</td>
                                    {{-- <td class="align-middle">{{ __(config('master.statusList.'.$oAlertUserInfo->status)) }}</td> --}}
                                    <td class="align-middle">{{ __(config('master.alertUserInfoLevelList.'.$oUser->alert_level)) }}</td>
                                    <td class="align-middle">{{ $oUser->fname }} {{ $oUser->lname }}</td>
                                    @can('addNote', \App\Models\NewUser::class)
                                        @if($oUser->alertUserInfo)
                                            <td style="white-space:pre;" class="align-middle">{{-- <button type="button" class="btn btn-info set-alert-user-info-memo-button" id="alert-user-info-memo-{{ $oAlertUserInfo->id }}" data-id="{{ $oAlertUserInfo->id }}" data-memo="{{ $oAlertUserInfo->memo }}">{{ __('messages.alert_user_info.memo') }}</button> --}}{{ $oUser->alertUserInfo->memo }}</td>
                                            <td class="align-middle">
                                                <a href="{{ route('users.risk-levels.show', $oUser->alertUserInfo->id) }}" class="btn btn-info">{{ __('messages.alert_user_info.detail') }}</a>
                                            </td>
                                        @else
                                            <td class="align-middle"></td>
                                            <td class="align-middle"></td>
                                        @endif
                                        <td class="align-middle">
                                            <a href="{{ route('users.risk-levels.edit', $oUser->id) }}" class="btn btn-info">{{ __('users.risk-levels.edit') }}</a>
                                        </td>
                                    
                                        {{-- <td class="align-middle">
                                            <button class="btn btn-warning delete-alert-user-info" data-id='{{ $oUser->alertUserInfo->id }}'>{{ __('messages.alert_user_info.delete_button') }}</button>
                                        </td> --}}
                                    @endcan
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="align-middle text-center">{{ __('messages.search.not_exist') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="mx-auto">
                    {{ $oUsers->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <form method="POST" action="{{ route('admin.alert-user-info.destroy-post') }}" id="delete-form">
    {{ csrf_field() }}
    <input type="hidden" name="id" value="" id="delete-alert-user-info-id">
    <div class="modal fade" id="deleteAlertUserInfoModal" tabindex="-1" role="dialog" aria-labelledby="deleteAlertUserInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAlertUserInfoModalLabel">{{ __('messages.modal.delete_title') }}</h5>
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
</form> --}}

<input type="hidden" name="id" value="" id="set-alert-user-info-id">
<input type="hidden" id="set-alert-user-info-memo-url" value="{{ route('users.risk-levels.notes') }}" />
<div class="modal fade" id="setAlertUserInfoMemoModal" tabindex="-1" role="dialog" aria-labelledby="setAlertUserInfoMemoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setAlertUserInfoMemoModalLabel">{{ __('messages.order.memo_modal_title') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <textarea id="memo" name="memo" class="form-control" rows="5"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('messages.modal.close_button') }}</button>
                <button type="button" class="btn btn-primary set-alert-user-info-memo-update-button">{{ __('messages.modal.edit_button') }}</button>
            </div>
        </div>
    </div>
</div>


@endsection
