<div class="form-group row mb-0">
    <div class="col-md-9 offset-md-4">
        <button type="submit" {{ $attributes->merge(['class' => 'btn btn-primary']) }}>
            {{ $label }}
        </button>
    </div>
</div>

{{-- Example component from Sendportal
<div class="form-group row">
    <div class="offset-sm-3 col-sm-9">
        <button type="submit" {{ $attributes->merge(['class' => 'btn btn-primary']) }}>{{ $label }}</button>
    </div>
</div> --}}