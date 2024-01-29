{{-- <div class="form-group row">
<label for="id" class="col-md-2 col-form-label">ID</label>
<div class="col-md-10">
    <input type="text" id="id" class="form-control" name="id" value="{{ request()->id }}">
    <small class="text-muted">{{ sprintf(__('messages.form.half_width'), 'ID') }}</small>
</div>
</div> --}}
<x-template.text-field name="id" :label="__('ID')" type="text" :value="request()->id ?? old('id')" :required=false/>

{{-- <div class="form-group row">
    <label for="product_id" class="col-md-2 col-form-label">{{ __('messages.order.product_id') }}</label>
    <div class="col-md-10">
        <input type="text" id="product_id" class="form-control" name="product_id" value="{{ request()->product_id }}">
        <small class="text-muted">{{ sprintf(__('messages.form.half_width'), __('messages.order.product_id')) }}</small>
    </div>
</div> --}}
{{-- <div class="form-group row">
    <label for="c_id" class="col-md-2 col-form-label">uid</label>
    <div class="col-md-10">
        <input type="text" id="c_id" class="form-control" name="c_id" value="{{ request()->c_id }}">
        <small class="text-muted">{{ sprintf(__('messages.form.half_width'), 'uid') }}</small>
    </div>
</div> --}}
<x-template.text-field name="user_code" :label="__('User Code')" type="text" :value="request()->user_code ?? old('user_code')" :required=false/>
{{-- <div class="form-group row">
    <label for="exp_id" class="col-md-2 col-form-label">expid</label>
    <div class="col-md-10">
        <input type="text" id="exp_id" class="form-control" name="exp_id" value="{{ request()->exp_id }}">
        <small class="text-muted">{{ sprintf(__('messages.form.half_width'), 'expid') }}</small>
    </div>
</div> --}}
<x-template.text-field name="payment_request_unique_code" :label="__('Payment Request Unique Code')" type="text" :value="request()->payment_request_unique_code ?? old('payment_request_unique_code')" :required=false/>
{{-- <div class="form-group row">
    <label for="order_process_code" class="col-md-2 col-form-label">payment_code</label>
    <div class="col-md-10">
        <input type="text" id="order_process_code" class="form-control" name="order_process_code" value="{{ request()->order_process_code }}">
    </div>
</div>--}}
<x-template.text-field name="payment_code" :label="__('Payment Code')" type="text" :value="request()->payment_code ?? old('payment_code')" :required=false/>
{{-- <div class="form-group row">
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
</div> --}}
@can ('update', \App\Models\PaymentRequest::class)
    {{-- <div class="form-group row">
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
    </div> --}}
    <x-template.text-field name="payin_id" :label="__('Payin ID')" type="text" :value="request()->payin_id ?? old('payin_id')" :required=false/>
@endcan
<x-template.select-field name="statuses[]" :label="__('Status')" :options="$statuses" :value="$selectedStatuses" multiple />
{{-- <div class="form-group row">
    <label for="status" class="col-md-2 col-form-label">{{ __('messages.order.status') }}</label>
    <div class="col-md-10">
        <select id="status" class="form-control" name="status[]" multiple size="7">
            @foreach (config('master.orderStatusList') as $iKey => $sValue)
                <option value="{{ $iKey }}" {{ request()->status && in_array($iKey, request()->status) ? "selected" : "" }}>{{ __($sValue) }}</option>
            @endforeach
        </select>
    </div>
</div> --}}
{{-- <x-template.text-field class="form-control datepicker" name="start_time" :label="__('Start Time')" type="text" :value="request()->start_time ?? old('start_time')" :required=false/> --}}
{{-- <label class="col-md-1 col-form-label">～</label> --}}
{{-- <x-template.label name="connection" required=false>{{ "__('～')" }}</x-template.label> --}}
{{-- <x-template.text-field name="end_time" :label="__('End Time')" type="text" :value="request()->end_time ?? old('end_time')" :required=false/> --}}

<div class="form-group row">
    <label for="start_date" class="col-md-4 col-form-label">{{ __('Date') }}</label>
    <div class="col-md-3">
        <input type="text" id="start_date" class="form-control datepicker" name="start_date" autocomplete="off" value="{{ request()->start_date }}">
    </div>
    <label class="col-form-label">～</label>
    <div class="col-md-3">
        <input type="text" id="end_date" class="form-control datepicker" name="end_date" autocomplete="off" value="{{ request()->end_date }}">
    </div>
</div>

<div class="form-group row">
    <label for="cb_start_date" class="col-md-4 col-form-label">{{ __('Callback Date') }}</label>
    <div class="col-md-3">
        <input type="text" id="cb_start_date" class="form-control datepicker" name="cb_start_date" autocomplete="off" value="{{ request()->cb_start_time }}">
    </div>
    <label class="col-form-label">～</label>
    <div class="col-md-3">
        <input type="text" id="cb_end_date" class="form-control datepicker" name="cb_end_date" autocomplete="off" value="{{ request()->cb_end_date }}">
    </div>
</div>

{{-- <div class="form-group row">
    <label for="start_callback_amount" class="col-md-2 col-form-label">{{ __('messages.order.callback_amount') }}</label>
    <div class="col-md-3">
        <input type="text" id="start_callback_amount" class="form-control" name="start_callback_amount" autocomplete="off" value="{{ request()->start_callback_amount }}">
        <small class="text-muted text-nowrap">{{ sprintf(__('messages.form.half_width'), __('messages.order.callback_amount')) }}</small>
    </div>
    <label class="col-md-1 col-form-label">～</label>
    <div class="col-md-3">
        <input type="text" id="end_callback_amount" class="form-control" name="end_callback_amount" autocomplete="off" value="{{ request()->end_callback_amount }}">
    </div>
</div> --}}
<div class="form-group row">
    <label for="start_amount" class="col-md-4 col-form-label">{{ __('Amount') }}</label>
    <div class="col-md-3">
        <input type="text" id="start_amount" class="form-control" name="start_amount" autocomplete="off" value="{{ request()->start_amount }}">
        {{-- <small class="text-muted text-nowrap">{{ sprintf(__('messages.form.half_width'), __('messages.order.amount')) }}</small> --}}
    </div>
    <label class="col-form-label">～</label>
    <div class="col-md-3">
        <input type="text" id="end_amount" class="form-control" name="end_amount" autocomplete="off" value="{{ request()->end_amount }}">
    </div>
</div>

{{--
<div class="form-group row">
    <label for="start_original_amount" class="col-md-2 col-form-label">{{ __('messages.order.original_amount') }}</label>
    <div class="col-md-3">
        <input type="text" id="start_original_amount" class="form-control" name="start_original_amount" autocomplete="off" value="{{ request()->start_original_amount }}">
        <small class="text-muted text-nowrap">{{ sprintf(__('messages.form.half_width'), __('messages.order.original_amount')) }}</small>
    </div>
    <label class="col-md-1 col-form-label">～</label>
    <div class="col-md-3">
        <input type="text" id="end_original_amount" class="form-control" name="end_original_amount" autocomplete="off" value="{{ request()->end_original_amount }}">
    </div>
</div>
--}}

<div class="form-group row">
    <label for="disp_number" class="col-md-4 col-form-label">{{ __('Display Number') }}</label>
    <div class="col-md-6">
        <select id="disp_number" class="form-control" name="disp_number">
            @foreach([
                10 => __("10 Items"),
                25 => __("25 Items"),
                50 => __("50 Items"),
                100 => __("100 Items")
            ] as $key => $label)
                <option value="{{ $key }}" {{ request()->get('disp_number', 100) == $key ? 'selected' : null }}>{{ $label }}</option>
            @endforeach
        
            {{-- @foreach (config('const.ADMIN_PAGINATE_NUMBER_LIST') as $iKey => $sValue)
                @if ((request()->disp_number && $iKey == request()->disp_number) || (!request()->disp_number && $iKey == config('const.ADMIN_ORDER_DISPLAY_MAX')))
                    <option value="{{ $iKey }}" selected>{{ $sValue }} {{ __('messages.admin_paginate_number_unit') }}</option>
                @else
                    <option value="{{ $iKey }}">{{ $sValue }} {{ __('messages.admin_paginate_number_unit') }}</option>
                @endif
            @endforeach --}}
        </select>
    </div>
</div>


<div class="text-right">
    <div class="offset-2 col-10">
        <a href="{{ route('payment-requests.index') }}"
        class="btn btn-md btn-secondary btn-preview">{{ __('Clear') }}</a>
        <button type="submit" class="btn btn-primary btn-md">{{ __('Search') }}</button>
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
 --}}

{{-- <x-template.select-field name="status" :label="__('messages.payment_request.status')" :options="$statuses" :required=true/>

<x-template.text-field name="exp_id" :label="__('messages.payment_request.exp_id')" type="text" :value="$order->exp_id ?? old('exp_id')" :required=true/>
<div class="form-group row">
    <label for="order_process_code" class="col-md-3 col-form-label text-md-right">payment_code<span class="text-danger">*</span></label>

    <div class="col-md-6">
        <input id="order_process_code" type="text" class="form-control @error('order_process_code') is-invalid @enderror" name="order_process_code" value="{{ old('order_process_code', $order->order_process_code) }}">

        @error('order_process_code')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    <label for="callback_amount" class="col-md-3 col-form-label text-md-right">{{ __('messages.order_detail.callback_amount') }}<span class="text-danger">*</span></label>

    <div class="col-md-6">
        <input id="callback_amount" type="text" class="form-control @error('callback_amount') is-invalid @enderror" name="callback_amount" value="{{ old('callback_amount', $order->callback_amount) }}">

        @error('callback_amount')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    <label for="amount" class="col-md-3 col-form-label text-md-right">{{ __('messages.order_detail.amount') }}<span class="text-danger">*</span></label>

    <div class="col-md-6">
        <input id="amount" type="text" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount', $order->amount) }}">

        @error('amount')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    <label for="original_amount" class="col-md-3 col-form-label text-md-right">{{ __('messages.order_detail.original_amount') }}<span class="text-danger">*</span></label>

    <div class="col-md-6">
        <input id="original_amount" type="text" class="form-control @error('original_amount') is-invalid @enderror" name="original_amount" value="{{ old('original_amount', $order->original_amount) }}">

        @error('original_amount')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    <label for="product_id" class="col-md-3 col-form-label text-md-right">product_id<span class="text-danger">*</span></label>

    <div class="col-md-6">
        <input id="product_id" type="text" class="form-control @error('product_id') is-invalid @enderror" name="product_id" value="{{ old('product_id', $order->product_id) }}">

        @error('product_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<div class="form-group row mb-0">
    <div class="col-md-9 offset-md-4">
        <button type="submit" class="btn btn-primary">
            {{ __('messages.order.edit_button') }}
        </button>
    </div>
</div> --}}