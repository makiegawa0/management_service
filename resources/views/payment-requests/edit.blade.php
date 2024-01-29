@extends('layouts.admin')

@section('content')
<div class="container">
    @component('layouts.partials.card')
        @slot('cardHeader')
            {{ __('messages.new_order.edit_title') }}
            <div class="float-right">
                <a href="{{ route('payment-requests.index') }}" class="btn btn-primary">{{ __('messages.new_order.list') }}</a>
            </div>
        @endSlot
        
        @slot('cardBody')
            <div class="row">
                <small class="col text-right"><span class="text-danger">*</span> {{ __('messages.form.required') }}</small>
            </div>
            <form method="POST">
                @csrf
                @method('PUT')
                @include('payment-requests.partials.form', ['statuses' => $statuses])
                        
            </form>
        @endSlot
    @endcomponent
</div>

@endsection
