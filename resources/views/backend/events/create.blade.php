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
                <form class="needs-validation create-form" method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data" novalidate>
                    <div class="row">
                        <div class="col-sm-10 mb-2">
                            {!! $buttons !!}
                        </div>
                        <div class="col-sm-2 text-end"><button type="submit" class="btn btn-sm btn-primary">ADD {{ strtoupper(Str::singular($section)) }}</button></div>
                    </div>
                    <div class="row">
                        @csrf
                        <div class="col-sm-9">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Title</label>
                                                <input type="text" class="form-control" name="title" id="name"
                                                    placeholder="Title" value="{{ old('title') }}" required>
                                                <div class="invalid-feedback">
                                                    Please enter valid title.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="sub_title" class="form-label">Sub Title</label>
                                                <input type="text" class="form-control" name="sub_title" id="sub_title"
                                                    placeholder="Sub Title" value="{{ old('sub_title') }}" />
                                                <div class="invalid-feedback">
                                                    Please enter valid sub title.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Slug</label>
                                                <input type="text" class="form-control" name="slug" id="slug"
                                                    placeholder="Slug" value="{{ old('slug') }}" required>
                                                <div class="invalid-feedback">
                                                    Please enter valid slug.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="mb-3">
                                                <label for="summary" class="form-label">Summary</label>
                                                <textarea rows="2" class="form-control" name="summary" id="summary"
                                                    placeholder="Event Summary" required>{{ old('summary') }}</textarea>
                                                <div class="invalid-feedback">
                                                    Please enter valid summary.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea rows="4" class="form-control" name="description" id="description" placeholder="Description here"
                                                    required>{{ old('description') }}</textarea>
                                                <div class="invalid-feedback">
                                                    Please enter valid description.
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
                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <label for="day_number" class="control-label">Day Number</label>
                                                <input type="number" min="0" class="form-control" name="day_number" id="day_number" placeholder="Day Number" required/>
                                                <div class="invalid-feedback">
                                                    Please enter valid day number.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <label for="venue_id" class="control-label">Event Venue</label>
                                                <select class="form-control select2" name="venue_id" id="venue_id" aria-placeholder="Select Venue" required>
                                                    <option value="" selected disabled>Select Venue</option>
                                                    @foreach($venues as $venue)
                                                        <option value="{{ $venue->id }}">{{ $venue->title ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select valid Venue
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <label for="speaker_id" class="control-label">Event Speaker</label>
                                                <select class="form-control select2" name="speaker_id" id="speaker_id" aria-placeholder="Select Speaker" required>
                                                    <option value="" selected disabled>Select Speaker</option>
                                                    @foreach($speakers as $speaker)
                                                        <option value="{{ $speaker->id }}">{{ $speaker->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select valid speaker
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <label for="start_at" class="control-label">Start At</label>
                                                <input type="datetime-local" class="form-control" name="start_at" id="start_at" required/>
                                                <div class="invalid-feedback">
                                                    Please enter valid start time
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <label for="orig_price" class="control-label">Ticket Original Price ($)</label>
                                                <input type="text" class="form-control input-mask text-start" name="orig_price" id="orig_price" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" required/>
                                                <div class="invalid-feedback">
                                                    Please enter valid original price
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <label for="price" class="control-label">Ticket Discounted Price ($)</label>
                                                <input type="text" class="form-control input-mask text-start" name="price" id="price" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" required/>
                                                <div class="invalid-feedback">
                                                    Please enter valid price
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <label for="image" class="control-label">Icon image</label>
                                                <input type="file" class="form-control" name="image" id="image"
                                                    required />
                                                <div class="invalid-feedback">
                                                    Please upload icon image.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 mb-5">
                            <button type="submit" class="btn btn-primary">ADD {{ strtoupper(Str::singular($section)) }}</button>
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
    <!-- form mask -->
    <script src="{{asset('assets/backend/libs/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <!-- form mask init -->
    <script src="{{asset('assets/backend/js/pages/form-mask.init.js')}}"></script>
    <script src="{{ asset('assets/backend/ckeditor/ckeditor.js') }}"></script>
    <script src="{{asset('assets/backend/js/create-slug.js')}}"></script>
    <script>
        CKEDITOR.replace('description');
    </script>
@endpush
