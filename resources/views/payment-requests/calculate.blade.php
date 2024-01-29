@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('messages.calc.search') }}
                    <button class="btn btn-primary ml-2" type="button" data-toggle="collapse" data-target="#collapse_search" aria-expanded="false" aria-controls="collapse_search">{{ __('messages.search.search_show') }}</button>
                </div>
                <div class="collapse {{ request()->all( )? 'show' : '' }}" id="collapse_search">
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <label for="status" class="col-md-2 col-form-label">{{ __('messages.calc.status') }}</label>
                                <div class="col-md-10">
                                    <select id="status" class="form-control" name="status[]" multiple size="7">
                                        @foreach (config('master.orderStatusList') as $iKey => $sValue)
                                            <option value="{{ $iKey }}" {{ request()->status && in_array($iKey, request()->status) ? "selected" : "" }}>{{ __($sValue) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="start_time" class="col-md-2 col-form-label">{{ __('messages.calc.created_at') }}</label>
                                <div class="col-md-4">
                                    <input type="text" id="start_time" class="form-control datepicker" name="start_time" autocomplete="off" value="{{ request()->start_time }}">
                                </div>
                                <label class="col-md-1 col-form-label">～</label>
                                <div class="col-md-4">
                                    <input type="text" id="end_time" class="form-control datepicker" name="end_time" autocomplete="off" value="{{ request()->end_time }}">
                                </div>
                                {{-- <input type="hidden" id="default_time" value="{{ $sDefaultTime }}"> --}}
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
        {{-- <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('messages.calc.result') }}&nbsp;({{ number_format($nCount) }})
                </div>
                <div class="card-body">
                    {{ number_format($nSumAmount) }}
                </div>
            </div>
        </div> --}}
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Total') }}&nbsp;({{ number_format($nCount) }})
                </div>
                <div class="card-body">
                    {{ number_format($nTotalAmountOfOrders) }}
                </div>
            </div>
        </div>
        {{-- <div class="col-md-12 mt-2">
            <div class="card">
                <div class="card-body">
                    total {{ number_format($nSumAmount + $nTotalAmountOfOrders) }}
                </div>
            </div>
        </div> --}}
    </div>

</div>
@endsection
