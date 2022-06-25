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
                <form class="needs-validation create-form" method="POST" action="{{ route('speakers.update', $speaker->id) }}"
                    enctype="multipart/form-data" novalidate>
                    <div class="row">
                        <div class="col-sm-10 mb-2">
                            {!! $buttons !!}
                        </div>
                    </div>
                    <div class="row">
                        @csrf
                        @method('PUT')
                        <div class="col-sm-9">
                            <div class="card">
                                <div class="card-body">

                                    <table class="table">
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ __($speaker->name ?? '') }}</td>
                                        </tr>

                                        <tr>
                                            <th>Summary</th>
                                            <td>{{ __($speaker->summary ?? '') }}</td>
                                        </tr>

                                        <tr>
                                            <th>Description</th>
                                            <td>{!! ($speaker->description) ? $speaker->description : '' !!}</td>
                                        </tr>

                                        <tr>
                                            <th>Status</th>
                                            <td>{{ ($speaker->is_active) ? 'Active' : 'Inactive' }}</td>
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
                                            <div class="mb-3">
                                                <label class="form-label">Current Image</label>
                                                <img src="{{ asset('assets/frontend/images/speakers/' . Str::of($speaker->image)->replace(' ', '%20')) }}"
                                                    class="form-control img-thumbnail" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-1">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <a href="{{ json_decode($speaker->metadata)->facebook ?? '#' }}">Facebook</a>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <a href="{{ json_decode($speaker->metadata)->twitter ?? '#' }}">Twitter</a>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <a href="{{ json_decode($speaker->metadata)->linkedin ?? '#' }}">Linked In</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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
