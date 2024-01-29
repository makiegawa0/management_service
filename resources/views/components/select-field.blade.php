<x-template.field-wrapper :name="$name" :label="$label" :required="$required">
    <select name="{{ $name }}" {{ $attributes->merge(['id' => 'id-field-' .  str_replace('[]', '', $name), 'class' => 'form-control ' . ($multiple ? 'selectpicker' : '') . ($errors->has($name) ? ' is-invalid' : ''), 'multiple' => $multiple]) }}>
        @foreach($options as $key => $text)
        {{ $key }}
            {{ $isSelected($key) }}
            <option value="{{ $key }}" {{ $isSelected($key) ? 'selected' : '' }}>{{ $text }}</option>
        @endforeach
    </select>

    @if (isset($errors) and $errors->has($name))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
</x-template.field-wrapper>
