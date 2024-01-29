@extends('layouts.admin')

@section('content')

<div id="loader">
    <div id="loader-body">
        <div class="alert alert-primary shadow" role="alert">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <span class='ml-2'>Data Communications.</span>
        </div>
    </div>
</div>

<div class="order">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ __('messages.order.search') }}
                        <button class="btn btn-primary ml-2" type="button" data-toggle="collapse" data-target="#collapse_search" aria-expanded="false" aria-controls="collapse_search">{{ __('messages.search.search_show') }}</button>
                    </div>
                    <div class="collapse {{ $bSearchShow ? 'show' : '' }}" id="collapse_search">
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
                                    <label for="product_id" class="col-md-2 col-form-label">{{ __('messages.order.product_id') }}</label>
                                    <div class="col-md-10">
                                        <input type="text" id="product_id" class="form-control" name="product_id" value="{{ request()->product_id }}">
                                        <small class="text-muted">{{ sprintf(__('messages.form.half_width'), __('messages.order.product_id')) }}</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="c_id" class="col-md-2 col-form-label">uid</label>
                                    <div class="col-md-10">
                                        <input type="text" id="c_id" class="form-control" name="c_id" value="{{ request()->c_id }}">
                                        <small class="text-muted">{{ sprintf(__('messages.form.half_width'), 'cid') }}</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="exp_id" class="col-md-2 col-form-label">expid</label>
                                    <div class="col-md-10">
                                        <input type="text" id="exp_id" class="form-control" name="exp_id" value="{{ request()->exp_id }}">
                                        <small class="text-muted">{{ sprintf(__('messages.form.half_width'), 'expid') }}</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="order_process_code" class="col-md-2 col-form-label">process_code</label>
                                    <div class="col-md-10">
                                        <input type="text" id="order_process_code" class="form-control" name="order_process_code" value="{{ request()->order_process_code }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tel" class="col-md-2 col-form-label">{{ __('messages.order.phone_number') }}</label>
                                    <div class="col-md-10">
                                        <input type="text" id="tel" class="form-control" name="tel" value="{{ request()->tel }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-md-2 col-form-label">{{ __('messages.order.email') }}</label>
                                    <div class="col-md-10">
                                        <input type="text" id="email" class="form-control" name="email" value="{{ request()->email }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="keyword" class="col-md-2 col-form-label">{{ __('messages.order.keyword') }}</label>
                                    <div class="col-md-10">
                                        <input type="text" id="keyword" class="form-control" name="keyword" value="{{ request()->keyword }}">
                                    </div>
                                </div>
                                @if ($oAdminUserLogin->isAdmin() || $oAdminUserLogin->isOperator() || $oAdminUserLogin->isCallbackManager())
                                <div class="form-group row">
                                    <label for="incoming_log_id" class="col-md-2 col-form-label">API Log ID</label>
                                    <div class="col-md-10">
                                        <input type="text" id="incoming_log_id" class="form-control" name="incoming_log_id" value="{{ request()->incoming_log_id }}">
                                        <label class="form-check-label">
                                            API Log ID Set data search
                                            @if (request()->set_incoming_log_id)
                                                <input name="set_incoming_log_id" value="1" type="checkbox" checked>
                                            @else
                                                <input name="set_incoming_log_id" value="1" type="checkbox">
                                            @endif
                                        </label>
                                    </div>
                                </div>
                                @endif
                                <div class="form-group row">
                                    <label for="status" class="col-md-2 col-form-label">{{ __('messages.order.status') }}</label>
                                    <div class="col-md-10">
                                        <select id="status" class="form-control" name="status[]" multiple size="7">
                                            @foreach (config('master.orderStatusList') as $iKey => $sValue)
                                                <option value="{{ $iKey }}" {{ request()->status && in_array($iKey, request()->status) ? "selected" : "" }}>{{ __($sValue) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="start_time" class="col-md-2 col-form-label">{{ __('messages.order.created_at') }}</label>
                                    <div class="col-md-4">
                                        <input type="text" id="start_time" class="form-control datepicker" name="start_time" autocomplete="off" value="{{ request()->start_time }}">
                                    </div>
                                    <label class="col-md-1 col-form-label">～</label>
                                    <div class="col-md-4">
                                        <input type="text" id="end_time" class="form-control datepicker" name="end_time" autocomplete="off" value="{{ request()->end_time }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="cb_start_time" class="col-md-2 col-form-label">{{ __('messages.order.cb_created_at') }}</label>
                                    <div class="col-md-4">
                                        <input type="text" id="cb_start_time" class="form-control datepicker" name="cb_start_time" autocomplete="off" value="{{ request()->cb_start_time }}">
                                    </div>
                                    <label class="col-md-1 col-form-label">～</label>
                                    <div class="col-md-4">
                                        <input type="text" id="cb_end_time" class="form-control datepicker" name="cb_end_time" autocomplete="off" value="{{ request()->cb_end_time }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="start_callback_amount" class="col-md-2 col-form-label">{{ __('messages.order.callback_amount') }}</label>
                                    <div class="col-md-4">
                                        <input type="text" id="start_callback_amount" class="form-control" name="start_callback_amount" autocomplete="off" value="{{ request()->start_callback_amount }}">
                                        <small class="text-muted text-nowrap">{{ sprintf(__('messages.form.half_width'), __('messages.order.callback_amount')) }}</small>
                                    </div>
                                    <label class="col-md-1 col-form-label">～</label>
                                    <div class="col-md-4">
                                        <input type="text" id="end_callback_amount" class="form-control" name="end_callback_amount" autocomplete="off" value="{{ request()->end_callback_amount }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="start_amount" class="col-md-2 col-form-label">{{ __('messages.order.amount') }}</label>
                                    <div class="col-md-4">
                                        <input type="text" id="start_amount" class="form-control" name="start_amount" autocomplete="off" value="{{ request()->start_amount }}">
                                        <small class="text-muted text-nowrap">{{ sprintf(__('messages.form.half_width'), __('messages.order.amount')) }}</small>
                                    </div>
                                    <label class="col-md-1 col-form-label">～</label>
                                    <div class="col-md-4">
                                        <input type="text" id="end_amount" class="form-control" name="end_amount" autocomplete="off" value="{{ request()->end_amount }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="disp_number" class="col-md-2 col-form-label">{{ __('messages.order.display') }}</label>
                                    <div class="col-md-10">
                                        <select id="disp_number" class="form-control" name="disp_number">
                                            @foreach (config('const.ADMIN_PAGINATE_NUMBER_LIST') as $iKey => $sValue)
                                                @if ((request()->disp_number && $iKey == request()->disp_number) || (!request()->disp_number && $iKey == config('const.ADMIN_ORDER_DISPLAY_MAX')))
                                                    <option value="{{ $iKey }}" selected>{{ $sValue }} {{ __('messages.admin_paginate_number_unit') }}</option>
                                                @else
                                                    <option value="{{ $iKey }}">{{ $sValue }} {{ __('messages.admin_paginate_number_unit') }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

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
                    <div class="card-header">Download</div>
                    <div class="card-body">
                        <div>
                            @if ($oAdminUserLogin->isAdmin() || $oAdminUserLogin->isAccountant() || $oAdminUserLogin->isCallbackManager())
                            <div class="float-right">
                                @if (request()->start_time || request()->end_time)
                                    <a href="{{ route('admin.new-order.csv-download', request()->query()) }}" class="btn btn-success mr-2"> {{ __('messages.order.csv_download') }}</a>
                                @else
                                    <small class="text-muted">{{ __('messages.order.csv_download_required') }}</small>
                                    <a href="#" class="btn btn-success disabled" tabindex="-1" role="button" aria-disabled="true">{{ __('messages.order.csv_download') }}</a>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
