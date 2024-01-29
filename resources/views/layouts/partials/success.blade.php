<div class="row">
    <div class="col-lg-8 offset-lg-2">
        @if (session('success'))
            <div class="alert alert-success">
                <p class="font-weight-bold mb-1">{{ __('Success!') }}</p>
                <p class="mb-0">{{ session('success') }}</p>
            </div>
        @elseif(session()->has('success'))
            <div class="alert alert-success">
                <p class="font-weight-bold mb-1">{{ __('Success!') }}</p>
                <p class="mb-0">{{ session()->get('success') }}</p>
            </div>
        @endif
    </div>
</div>


{{-- @if (session('flashSuccess') || session('flashFailure'))
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('flashFailure'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ __('flash.failure') }}</strong> {{ session()->pull('flashFailure', '') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (session('flashSuccess'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ __('flash.success') }}</strong> {{ session()->pull('flashSuccess', '') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif --}}
