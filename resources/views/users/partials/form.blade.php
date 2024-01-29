<x-template.text-field name="id" :label="__('ID')" type="text" :value="request()->id ?? old('id')" :required=false/>

{{-- <div class="form-group row">
    <label for="start_id" class="col-md-4 col-form-label">{{ __('ID Range') }}</label>
    <div class="col-md-3">
            <input type="text" id="start_id" class="form-control" name="start_id" autocomplete="off" value="{{ request()->start_id }}"> --}}
            {{-- <small class="text-muted text-nowrap">{{ sprintf(__('messages.form.half_width'), __('messages.order.amount')) }}</small> --}}
    {{-- </div>
    <label class="col-form-label">～</label>
    <div class="col-md-3">
            <input type="text" id="end_id" class="form-control" name="end_id" autocomplete="off" value="{{ request()->end_id }}">
    </div>
</div> --}}

<x-template.text-field name="name" :label="__('User Name')" type="text" :value="request()->id ?? old('name')" :required=false/>

<x-template.text-field name="user_code" :label="__('User Code')" type="text" :value="request()->user_code ?? old('user_code')" :required=false/>

<x-template.text-field name="hurigana" :label="__('Hurigana')" type="text" :value="request()->hurigana ?? old('hurigana')" :required=false/>

<x-template.select-field name="alertLevels[]" :label="__('Alert Levels')" :options="$alertLevels" :value="$selectedAlertLevels" multiple />

<x-template.text-field name="payment_code" :label="__('Payment Code')" type="text" :value="request()->payment_code ?? old('payment_code')" :required=false/>

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
        <a href="{{ route('users.index') }}"
        class="btn btn-md btn-secondary btn-preview">{{ __('Clear') }}</a>
        <button type="submit" class="btn btn-primary btn-md">{{ __('Search') }}</button>
    </div>
</div>