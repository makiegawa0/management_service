{{-- <div class="form-group row form-group-name">
    <label for="id-field-name" class="control-label col-sm-2">{{ __('Template Name') }}</label>
    <div class="col-sm-6">
        <input id="id-field-name" class="form-control" name="name" type="text" value="{{ old('name', $template->name ?? '') }}">
    </div>
</div>

@include('sendportal::templates.partials.editor')

 --}}

<x-template.text-field name="id" :label="__('ID')" type="text" :value="request()->id ?? old('id')" :required=false/>

<x-template.text-field name="name" :label="__('User Name')" type="text" :value="request()->name ?? old('name')" :required=false/>

<x-template.text-field name="email" :label="__('Email')" type="text" :value="request()->email ?? old('email')" :required=false/>

<x-template.select-field name="levels[]" :label="__('Level')" :options="$levels" :value="$selectedLevels" multiple />



<div class="text-right">
    {{-- <div class="offset-2 col-10">
        <a href="#" class="btn btn-md btn-secondary btn-preview">{{ __('Show Preview') }}</a>
        <button class="btn btn-primary btn-md" type="submit">{{ __('Save Template') }}</button>
    </div> --}}

    <div class="offset-2 col-10">
        <a href="{{ route('admin-users.index') }}"
        class="btn btn-md btn-secondary btn-preview">{{ __('Clear') }}</a>
        <button type="submit" class="btn btn-primary btn-md">{{ __('Search') }}</button>
    </div>
    {{-- <button type="button" class="btn btn-warning clear-button">{{ __('messages.search.clear_button') }}</button>
    <button type="submit" class="btn btn-primary">{{ __('messages.search.button') }}</button> --}}
</div>