<x-template.field-wrapper :name="$name" :label="$label" :required="$required">
    @php
        $nameForError = str_replace(['[', ']'], ['.', ''], $name);
    @endphp
    <input type="{{ $type }}" name="{{ $name }}" value="{{ $value }}" {{ $attributes->merge(['id' => 'id-field-' .  str_replace('[]', '', $name), 'class' => 'form-control'. ($errors->has($nameForError) ? ' is-invalid' : '')]) }}>

    @if (isset($errors) and $errors->has($nameForError))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first($nameForError) }}</strong>
        </span>
    @endif
</x-template.field-wrapper>
