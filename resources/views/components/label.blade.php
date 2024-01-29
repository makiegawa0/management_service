{{-- <label for="id-field-{{ str_replace('[]', '', $name) }}" class="col-md-4 col-form-label text-md-right"> --}}
  <label for="id-field-{{ str_replace('[]', '', $name) }}" class="col-md-4 col-form-label">
  {{ $slot }}
  @if($required) <span class="text-danger">*</span> @endif
</label>

{{-- <label for="id-field-{{ str_replace('[]', '', $name) }}" class="control-label col-md-2">{{ $slot }}</label> --}}