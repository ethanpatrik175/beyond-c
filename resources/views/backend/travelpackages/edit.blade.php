@extends('layouts.backend.master')
@section('title') {{ __($page_title ?? '-') }} @endsection
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
            @if(Session::has('message'))
            <div class="col-sm-12">
                <div class="alert alert-{{Session::get('type')}} alert-dismissible fade show" role="alert">
                    @if(Session::get('type') == 'danger') <i class="mdi mdi-block-helper me-2"></i> @else <i
                        class="mdi mdi-check-all me-2"></i> @endif
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
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endforeach
            </div>
            @endif
            <div class="col-sm-12 message"></div>
            <div class="col-sm-12 mb-2">
                {!! $shortcut_buttons !!}
            </div>
            <form class="needs-validation" method="POST" action="{{route('travel-packages.update', $travel_package->id)}}"
                enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-sm-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="Name" class="form-label">Name</label>
                                            <input type="text" class="form-control" name="name" id="name"
                                                placeholder="Name here" value="{{$travel_package->name ?? ''}}" required
                                                onkeyup="myFunction();">
                                            <div class="invalid-feedback">
                                                Please enter valid Name.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="slug" class="form-label">Slug</label>
                                            <input type="text" class="form-control" name="slug" id="slug"
                                                placeholder="Slug" value="{{$travel_package->slug ?? ''}}">
                                            <div class="invalid-feedback">
                                                Please enter valid slug.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Package Type</label>
                                            <select  name="package_type_id"
                                                class="form-control select2">
                                                <option value="">Package Type</option>
                                                @foreach($package_type as $repo)
                                                <option value="{{$repo->id}}" {{ ($travel_package->package_type_id == $repo->id) ? 'selected' : '' }} >{{$repo->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tags</label>
                                            <select class="select2 form-control select2-multiple" multiple="multiple"
                                                data-placeholder="Choose ..." name="tag_id[]">
                                                @foreach ($Tags as $repo)
                                                <option value="{{ $repo->id }}" {{ in_array($repo->id, $tag) ? 'selected' : '' }}>{{ $repo->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="Location" class="form-label">Location</label>
                                            <input type="text" class="form-control" name="location" id="location"
                                                placeholder="location" value="{{$travel_package->location ?? ''}}" required>
                                            <div class="invalid-feedback">
                                                Please enter valid Location.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Price</label>
                                            <input type="number"  step="any" class="form-control" name="price" id="price"
                                                placeholder="Price" value="{{$travel_package->price ?? ''}}" required>
                                            <div class="invalid-feedback">
                                                Please enter valid Price.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="sale_price" class="form-label">Sale Price</label>
                                            <input type="number" step="any" class="form-control" name="sale_price"
                                                id="sale_price" placeholder="Regular Price "
                                                value="{{$travel_package->sale_price ?? ''}}" required>
                                            <div class="invalid-feedback">
                                                Please enter valid Sale_Price.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="no_of_days" class="form-label">No of Days</label>
                                            <input type="number" step="any" class="form-control" name="no_of_days"
                                                id="no_of_days" placeholder="No of Days" value="{{$travel_package->no_of_days ?? ''}}"
                                                required>
                                            <div class="invalid-feedback">
                                                Please enter valid No of Days.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="no_of_members" class="form-label">No of Members</label>
                                            <input type="number" step="any" class="form-control" name="no_of_members"
                                                id="no_of_members" placeholder="No of Members " value="{{$travel_package->no_of_members ?? ''}}">
                                            <div class="invalid-feedback">
                                                Please enter valid No of Members.
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="requirment" class="form-label">Requirment</label>
                                            <input type="text" class="form-control" name="requirment" id="requirment"
                                                placeholder="Requirment " value="{{$travel_package->requirements ?? ''}}" required>
                                            <div class="invalid-feedback">
                                                Please enter valid Requirment.
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-12">
                                        <div class="mb-3">
                                            <label for="requirment" class="form-label">Requirment</label>
                                            <textarea rows="4" class="form-control" name="requirment" id="requirment"
                                                placeholder="Requirment here" required>{{$travel_package->requirements ?? ''}}</textarea>
                                            <div class="invalid-feedback">
                                                Please enter valid Requirment.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="mb-3">
                                            <label for="content" class="form-label">Description</label>
                                            <textarea rows="4" class="form-control" name="description" id="content"
                                                placeholder="Description here" required>{{$travel_package->description ?? ''}}</textarea>
                                            <div class="invalid-feedback">
                                                Please enter valid Description.
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
                                            <label for="image" class="control-label">Icon image</label>
                                            <input type="file" class="form-control" name="image" id="image" />
                                            <div class="invalid-feedback">
                                                Please upload icon image.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label class="control-label">Current Icon Image</label>
                                            <img src="{{asset('assets/frontend/images/travelpackages/'.$travel_package->image)}}"
                                                class="form-control img-thumbnail" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="card mt-1">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label for="meta_title" class="control-label">Meta Data</label>
                                            <input type="text" class="form-control" name="meta_title" id="meta_title"
                                                placeholder="Meta Title" value="{{ json_decode($Product->metaTitle)->meta_title }}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <div class="col-sm-12 mb-5">
                        <button type="submit" class="btn btn-primary">UPDATE PRODUCT</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- end row -->
    </div> <!-- container-fluid -->
</div>
@endsection

@push('scripts')
<script src="{{asset('assets/backend/libs/parsleyjs/parsley.min.js')}}"></script>
<script src="{{asset('assets/backend/js/pages/form-validation.init.js')}}"></script>
<script src="{{ asset('assets/backend/ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace('content');
    CKEDITOR.replace('requirment');
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
