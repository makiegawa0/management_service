@extends('layouts.app')

@section('title', __('Admin Users'))

@section('heading')
    {{ __('Admin Users') }}
@endsection

@section('content')
    @include('layouts.partials.loading')
    @component('layouts.partials.actions')

        @slot('left')
            <a class="btn btn-primary btn-md btn-flat" data-toggle="collapse" data-target="#collapse_search" aria-expanded="false" aria-controls="collapse_search">
                <i class="fas fa-search mr-1"></i> {{ __('Search') }}
            </a>
            <div class="collapse {{ request()->all() ? 'show' : '' }}" id="collapse_search">
                <div class="card">
                    <div class="card-header">
                        {{ __('Search') }}
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal">
                            @include('admin-users.partials.form')
                        </form>
                    </div>
                </div>
            </div>

            {{-- <form action="{{ route('sendportal.paymentRequests.index') }}" method="GET" class="form-inline mb-3 mb-md-0">
                <input class="form-control form-control-sm" name="name" type="text" value="{{ request('name') }}"
                        placeholder="{{ __('Search...') }}">

                <div class="mr-2">
                    <select name="status" class="form-control form-control-sm">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>{{ __('All') }}</option>
                        <option
                            value="subscribed" {{ request('status') == 'subscribed' ? 'selected' : '' }}>{{ __('Subscribed') }}</option>
                        <option
                            value="unsubscribed" {{ request('status') == 'unsubscribed' ? 'selected' : '' }}>{{ __('Unsubscribed') }}</option>
                    </select>
                </div>

                @if(count($tags))
                    <div id="tagFilterSelector" class="mr-2">
                        <select multiple="" class="selectpicker form-control form-control-sm" name="tags[]" data-width="auto">
                            @foreach($tags as $tagId => $tagName)
                                <option value="{{ $tagId }}" @if(in_array($tagId, request()->get('tags') ?? [])) selected @endif>{{ $tagName }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <button type="submit" class="btn btn-light btn-md">{{ __('Search') }}</button>

                @if(request()->anyFilled(['name', 'status']))
                    <a href="{{ route('sendportal.paymentRequests.index') }}"
                        class="btn btn-md btn-light">{{ __('Clear') }}</a>
                @endif
            </form> --}}
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-table table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('User Name') }}</th>
                        <th>{{ __('Level') }}</th>
                        {{-- <th>{{ __('messages.admin_user.status') }}</th> --}}
                        {{-- <th>{{ __('messages.admin_user.edit') }}</th>
                        <th>{{ __('messages.admin_user.delete_button') }}</th> --}}
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($oAdminUsers as $oAdminUser)
                        <tr>
                            <td>{{ $oAdminUser->id }}</td>
                            <td>{{ $oAdminUser->name }}</td>
                            <td>
                                @include('admin-users.partials.level')
                            </td>
                            {{-- <td class="align-middle">{{ __($statuses[$oAdminUser->status]) }}</td> --}}
                            <td>
                                @if (Gate::allows('update', \App\Models\AdminUser::class) || $oAdminUser->id == Auth::user()->id)
                                    <a href="{{ route('admin-users.edit', $oAdminUser->id) }}" class="btn btn-sm btn-light">
                                        {{ __('Edit') }}
                                    </a>
                                @endif
                                {{-- <a class="btn btn-sm btn-light"
                               href="{{ route('sendportal.email_services.edit', $service->id) }}">{{ __('Edit') }}</a> --}}
                                <form action="{{ route('admin-users.destroy', $oAdminUser) }}" style="display: inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-light">{{ __('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%">
                                <p class="empty-table-text">{{ __('No Admin User Found') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('layouts.partials.pagination', ['records' => $oAdminUsers])

@endsection

{{-- <form method="POST" action="{{ route('admin-users.destroy') }}" id="delete-form">
    {{ csrf_field() }}
    <input type="hidden" name="id" value="" id="delete-admin-user-id">
    <div class="modal fade" id="deleteAdminUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteAdminUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAdminUserModalLabel">{{ __('messages.modal.delete_title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ __('messages.modal.delete_content') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('messages.modal.close_button') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('messages.modal.delete_button') }}</button>
                </div>
            </div>
        </div>
    </div>
</form> --}}