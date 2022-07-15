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
                <form class="needs-validation" method="POST" action="{{ route('sectioncontents.store') }}"
                    enctype="multipart/form-data" novalidate>
                    <div class="row">
                        @csrf
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Name</label>
                                                <input type="text" class="form-control" name="name" id="name"
                                                    placeholder="Name here" value="{{ old('name') }}" required>
                                                <div class="invalid-feedback">
                                                    Please enter valid name.
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label class="form-label">Section</label>
                                                <select data-placeholder="Select Parent" name="section_id"
                                                    class="form-control select2">
                                                    <option value="">Select Section</option>
                                                    @foreach ($parents as $repo)
                                                        <option value="{{ $repo->id }}">{{ $repo->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label class="form-label">Content</label>
                                                <select data-placeholder="Select Parent" class="form-control" name="content_type">
                                                    <option value="">Select Section</option>
                                                    <option value="Text">Text</option>
                                                    <option value="File">File/Image</option>
                                                    <option value="textarea">Text Area</option>
                                                    <option value="link">link</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="Text msg">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Text</label>
                                                    <input type="text" class="form-control" name="text" id="content"
                                                        placeholder="Name here" value="{{ old('content') }}">
                                                    <div class="invalid-feedback">
                                                        Please enter valid name.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="File msg">
                                            <div class="col-lg-12 mb-3">
                                                <div class="form-group">
                                                    <label for="image" class="control-label">File/Image</label>
                                                    <input type="file" class="form-control" name="image"
                                                        id="content" />
                                                    <div class="invalid-feedback">
                                                        Please upload File.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="textarea msg">
                                            <div class="col-lg-12 mb-3">
                                                <div class="form-group">
                                                    <label for="meta_description" class="control-label">Meta text</label>
                                                    <textarea class="form-control" rows="4" name="meta_description" id="meta_description"
                                                        placeholder="Meta Description"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="link msg">
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label for="link" class="form-label">Link</label>
                                                    <input type="text" class="form-control" name="link"
                                                        id="link" placeholder="Link here" />
                                                    <div class="invalid-feedback">
                                                        Please enter valid link.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 mb-5">
                                            <button type="submit" class="btn btn-primary">ADD SECTION CONTENT</button>
                                        </div>



                                    </div>
                                </div>
                            </div>
                            <!-- end card -->
                        </div> <!-- end col -->
                        <!-- <div class="col-sm-12 mb-5">
                            <button type="submit" class="btn btn-primary">ADD Anncounment</button>
                        </div> -->

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
    <!-- <script src="{{ asset('assets/backend/ckeditor/ckeditor.js') }}"></script>
        <script>
            CKEDITOR.replace('description');
        </script> -->
    <script type="text/javascript">
        // function ShowHideDiv() {
        //     console.log()
        //         var ddlPassport = document.getElementById("ddlPassport");
        //         var dvPassport = document.getElementById("dvPassport");
        //         dvPassport.style.display = ddlPassport.value == "T" ? "block" : "none";
        //     }
        $(document).ready(function() {
            $("select").change(function() {
                $(this).find("option:selected").each(function() {
                    var val = $(this).attr("value");
                    if (val) {
                        $(".msg").not("." + val).hide();
                        $("." + val).show();
                    } else {
                        $(".msg").hide();
                    }
                });
            }).change();
        });
    </script>
@endpush
