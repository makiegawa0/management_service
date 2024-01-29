<div {{ $attributes->merge(['class' => 'form-group row form-group-' . $name . ' ' . $wrapperClass  . ' '. $errorClass($name)]) }}>
    <x-template.label :name="$name" :required="$required">{{ $label }}</x-template.label>
    <div class="col-md-6">
        {{ $slot }}
    </div>
</div>