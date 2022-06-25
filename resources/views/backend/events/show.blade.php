@extends('layouts.backend.master')
@section('title')
    {{ __($page_title ?? '-') }}
@endsection
@section('page-content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ __($page_title ?? '-') }}</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ __($section ?? '-') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __($page_title ?? '-') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                @if (Session::has('message'))
                    <div class="col-sm-12">
                        <div class="alert alert-{{ Session::get('type') }} alert-dismissible fade show" role="alert">
                            @if (Session::get('type') == 'danger')
                                <i class="mdi mdi-block-helper me-2"></i>
                            @else
                                <i class="mdi mdi-check-all me-2"></i>
                            @endif
                            {{ __(Session::get('message')) }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="col-sm-12">
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-block-helper me-2"></i>
                                {{ __($error) }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="col-sm-12 message"></div>

                <div class="row">
                    <div class="col-sm-10 mb-2">
                        {!! $buttons !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-9">
                        <div class="card">
                            <div class="card-body">

                                <table class="table">
                                    <tr>
                                        <th>Title / Sub Title</th>
                                        <td>{{ __($event->title ?? '') }} / {{ __($event->sub_title ?? '') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Venue</th>
                                        <td>{{ $event->venue->title ?? '' }}/{{ $event->venue->sub_title ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Speaker</th>
                                        <td>{{ $event->speaker->name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Summary</th>
                                        <td>{{ $event->summary ? json_decode($event->summary) : '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Description</th>
                                        <td>{!! $event->description ? json_decode($event->description) : '' !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Created at</th>
                                        <td>{!! date('d-M-Y h:i:s A', strtotime($event->created_at)). ' By: <label class="text-primary">'.@$event->addedBy->first_name.' '.@$event->addedBy->last_name.'</label>';  !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated at</th>
                                        <td>{!! date('d-M-Y h:i:s A', strtotime($event->updated_at)). ' By: <label class="text-primary">'.@$event->updatedBy->first_name.' '.@$event->updatedBy->last_name.'</label>';  !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Deleted at</th>
                                        <td>{!! ($event->deleted_at) ? date('d-M-Y h:i:s A', strtotime($event->deleted_at)).' By: <label class="text-primary">'.@$event->updatedBy->first_name.' '.@$event->updatedBy->last_name.'</label>' : '';  !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>{{ $event->is_active ? 'Active' : 'Inactive' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- end card -->
                    </div> <!-- end col -->
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table">
                                            <tr>
                                                <th>Start at</th>
                                                <td>{{ ($event->start_at) ? date('d-M-Y H:i:s A', strtotime($event->start_at)) : '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Day Number</th>
                                                <td>{{ $event->day_number ?? '#' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="mb-3">
                                            <label class="form-label">Current Image</label>
                                            <img src="{{ asset('assets/frontend/images/events/' . Str::of($event->image)->replace(' ', '%20')) }}"
                                                class="form-control img-thumbnail mh-400" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div> <!-- container-fluid -->
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/backend/libs/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/pages/form-validation.init.js') }}"></script>
    <script src="{{ asset('assets/backend/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/backend/js/create-slug.js') }}"></script>
    <script>
        CKEDITOR.replace('description');
        $(document).on('click', '#submit-form', function() {
            $(document).find('form.create-form').submit();
        });
    </script>
@endpush
