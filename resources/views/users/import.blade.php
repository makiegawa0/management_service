@extends('layouts.admin')

@section('content')

<div class="callback_request_log">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('messages.callback_request_log.lock_file_upload') }}
                    <div class="float-right">
                        <a href="{{ route('users.index') }}" class="btn btn-primary">{{ __('messages.new_user.list') }}</a>
                    </div>
                </div>
                <div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            @csrf
                            {{-- <input type="hidden" name="bank_csv_upload_lock_id" value="{{ $oBankCsvUploadLock->id }}"> --}}

                            {{-- <div class="form-group row">
                                <div class="col-md-3">
                                    {{ __('messages.callback_request_log.lock_bank_type') }}
                                </div>
                                <div class="col-md-9">
                                    {{ $oBankCsvUploadLock->bank_type }}
                                </div>
                            </div> 
                            <div class="form-group row">
                                <div class="col-md-3">
                                    {{ __('messages.callback_request_log.lock_bank_mc') }}
                                </div>
                                <div class="col-md-9">
                                    {{ $oBankCsvUploadLock->mc }} ({{ config('bank.API_BANK_NAME')[$oBankCsvUploadLock->bank_type][$oBankCsvUploadLock->mc]['name'] }})
                                </div>
                            </div> --}}

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-md-left">{{ __('messages.callback_request_log.csv_file') }}<span class="text-danger">*</span></label>

                                <div class="col-md-9">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('csv_file') is-invalid @enderror" name="csv_file">
                                        <label class="custom-file-label" data-browse="{{ __('messages.form.file_browse') }}">{{ __('messages.form.file_select') }}</label>
                                        @error('csv_file')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="text-danger">
                                CSV Format:<br>
                                Please make sure to include a header and the columns are in order of UID, First Name, Last Name, Phone Number, Email, Payment Code, Keyword, KYC (Y/N), User Kind (V/N/M).<br>
                                DO NOT include 'B-' to the payment code.<br>
                                Please DO NOT reuse the Payment Code.<br><br>
                                CSV files must be in SJIS encoding.
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
@endsection
