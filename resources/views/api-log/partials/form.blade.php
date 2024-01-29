<x-template.text-field name="id" :label="__('ID')" type="text" :value="request()->id ?? old('id')" :required=false/>

<x-template.text-field name="payment_request_unique_code" :label="__('Payment Request Unique Code')" type="text" :value="request()->payment_request_unique_code ?? old('payment_request_unique_code')" :required=false/>
    
<x-template.select-field name="statuses[]" :label="__('Status')" :options="$statuses" :value="$selectedStatuses" multiple />

<x-template.text-field name="response" :label="__('Response')" type="text" :value="request()->response ?? old('response')" :required=false/>

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
      <a href="{{ route('api-logs.index') }}"
      class="btn btn-md btn-secondary btn-preview">{{ __('Clear') }}</a>
      <button type="submit" class="btn btn-primary btn-md">{{ __('Search') }}</button>
  </div>
</div>