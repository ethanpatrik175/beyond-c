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
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                <form class="needs-validation" method="POST" action="{{ route('page.update', $page->id) }}"
                    enctype="multipart/form-data" novalidate>
                    <div class="row">
                        @csrf
                        @method('PUT')
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Name</label>
                                                <input type="text" class="form-control" name="name" id="name"
                                                    placeholder="Name here" value="{{ $page->name ?? '' }}" required
                                                    onkeyup="myFunction();">
                                                <div class="invalid-feedback">
                                                    Please enter valid name.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="slug" class="form-label">Slug</label>
                                                <input type="text" class="form-control" name="slug" id="slug"
                                                    placeholder="Slug here" value="{{ $page->slug ?? '' }}" required>
                                                <div class="invalid-feedback">
                                                    Please enter valid Slug.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="order" class="form-label">Page Order</label>
                                                <input type="number" class="form-control" name="order" id="order"
                                                    placeholder="Slug here" value="{{ $page->order ?? '' }}" required>
                                                <div class="invalid-feedback">
                                                    Please enter valid order.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Parent</label>
                                                <select data-placeholder="Select Parent" name="parent_id"
                                                    class="form-control select2"> 
                                                    <option value="">Select Parent</option>
                                                    @foreach ($parents as $page)
                                                        <option value="{{ $page->id}}" {{ in_array($page->id, $parent) ? 'selected' : '' }}>{{ $page->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- <div class="col-sm-12">
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea rows="4" class="form-control" name="description" id="description" placeholder="Description here"
                                                    required>{{-- old('description') --}}</textarea>
                                                <div class="invalid-feedback">
                                                    Please enter valid description.
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="col-sm-12">
                                            <div class="mb-3">
                                                <label for="link" class="form-label">Pages Link</label>
                                                <input type="text" class="form-control" name="link" id="link"
                                                    placeholder="Link here" value="{{ $page->link ?? '' }}" />
                                                <div class="invalid-feedback">
                                                    Please enter valid link.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end card -->
                        </div> <!-- end col -->

                        <div class="col-sm-12 mb-5">
                            <button type="submit" class="btn btn-primary">UPDATE PAGE</button>
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
    <script>
        CKEDITOR.replace('description');
    </script>
     <script type="text/javascript">
        function myFunction() {

            var a = document.getElementById("name").value;

            var b = a.toLowerCase().replace(/ /g, '-')
                .replace(/[^\w-]+/g, '');
            console.log(b);
            document.getElementById("slug").value = b;

            // document.getElementById("slug-target-span").innerHTML = b;
        }
    </script>
@endpush
