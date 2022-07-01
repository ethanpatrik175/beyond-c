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
                <div class="col-sm-12 mb-2">
                    {!! $shortcut_buttons !!}
                </div>
                <form class="needs-validation" method="POST" action="{{ route('packages.update.permissions') }}"
                    enctype="multipart/form-data" novalidate>
                    <div class="row">
                        @csrf
                        <div class="col-sm-9">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="package_id" class="form-label">Packages</label>
                                                <select class="form-control select2" name="package_id" id="package_id">
                                                    <option value="" selected disabled>Select Package</option>
                                                    @forelse ($packages as $package)
                                                        <option value="{{ $package->id }}"
                                                            @if ($package_id == $package->id) {{ 'selected' }} @endif>
                                                            {{ $package->name }}</option>
                                                    @empty
                                                        <option value="">No Package</option>
                                                    @endforelse
                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select package.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group mt-3">
                                                <label for="services" class="form-label">Services</label>
                                                <select class="form-control select2" multiple
                                                    data-placeholder="Choose Services..." name="services[]" id="services">
                                                    @forelse ($services as $service)
                                                        <option value="{{ $service->id }}"
                                                            @if (in_array($service->id, $pServices)) {{ 'selected' }} @endif>
                                                            {{ $service->name }}</option>
                                                    @empty
                                                        <option value="">No Service</option>
                                                    @endforelse
                                                </select>
                                                <div class="invalid-feedback">
                                                    Please choose services.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group mt-3">
                                                <label for="stories" class="form-label">Stories</label>
                                                <select class="form-control select2" multiple
                                                    data-placeholder="Choose Stories..." name="stories[]" id="stories">
                                                    @forelse ($stories as $story)
                                                        <option value="{{ $story->id }}"
                                                            @if (in_array($story->id, $pStories)) {{ 'selected' }} @endif>
                                                            {{ $story->name }}</option>
                                                    @empty
                                                        <option value="">No Story</option>
                                                    @endforelse
                                                </select>
                                                <div class="invalid-feedback">
                                                    Please choose stories.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group mt-3">
                                                <label for="podcasts" class="form-label">Podcast</label>
                                                <select class="form-control select2" multiple
                                                    data-placeholder="Choose Podcasts..." name="podcasts[]" id="podcasts">
                                                    @forelse ($podcasts as $podcast)
                                                        <option value="{{ $podcast->id }}"
                                                            @if (in_array($podcast->id, $pPodcasts)) {{ 'selected' }} @endif>
                                                            {{ $podcast->title . ' (' . $podcast->categoryName . ')' ?? '' }}</option>
                                                    @empty
                                                        <option value="">No Podcast</option>
                                                    @endforelse
                                                </select>
                                                <div class="invalid-feedback">
                                                    Please choose podcasts.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                                <label class="form-label">Image</label>
                                                @if (isset($package->icon))
                                                    <img src="{{ asset('assets/frontend/images/packages/' . $package->icon) }}"
                                                        class="form-control img-thumbnail" alt="">
                                                @else
                                                    <img src="{{ asset('assets/backend/images/no-image.jpg') }}"
                                                        class="form-control img-thumbnail" alt="">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 mb-5">
                            <button type="submit" class="btn btn-primary">UPDATE PERMISSIONS</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- end row -->
        </div> <!-- container-fluid -->
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/backend/libs/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/pages/form-validation.init.js') }}"></script>
@endsection
