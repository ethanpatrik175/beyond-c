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
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ __($section ?? '-') }}</a></li>
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
	                        @if(Session::get('type') == 'danger') <i class="mdi mdi-block-helper me-2"></i> @else <i class="mdi mdi-check-all me-2"></i> @endif
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
                <form class="needs-validation" method="POST" action="{{route('banners.update', $banner->id)}}" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-sm-9">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="heading_oneBrown" class="form-label">Heading One</label>
                                                <input type="text" class="form-control" name="heading[one]" id="heading_oneBrown"
                                                    placeholder="Heading One (Brown)" value="{{ json_decode($banner->headings)->one ?? '' }}" />
                                                <div class="invalid-feedback">
                                                    Please enter valid heading one.
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="heading_oneWhite" class="form-label">Heading One(White)</label>
                                                <input type="text" class="form-control" name="heading[oneWhite]" id="heading_oneWhite"
                                                    placeholder="Heading One (White)" value="{{ json_decode($banner->headings)->oneWhite ?? '' }}" />
                                                <div class="invalid-feedback">
                                                    Please enter valid heading one.
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="heading_two" class="form-label">Heading Two</label>
                                                <input type="text" class="form-control" name="heading[two]" id="heading_two"
                                                    placeholder="Heading Two here" value="{{ json_decode($banner->headings)->two ?? '' }}" >
                                                <div class="invalid-feedback">
                                                    Please enter valid heading two.
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="heading_two" class="form-label">Heading Three</label>
                                                <input type="text" class="form-control" name="heading[three]" id="heading_two"
                                                    placeholder="Heading Three here" value="{{ json_decode($banner->headings)->three ?? '' }}" >
                                                <div class="invalid-feedback">
                                                    Please enter valid heading two.
                                                </div>
                                            </div>
                                        </div> --}}

                                        <div class="col-sm-12">
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea rows="4" class="form-control" name="description" id="description"
                                                    placeholder="Description here" >{{ $banner->description ?? '' }}</textarea>
                                                <div class="invalid-feedback">
                                                    Please enter valid description.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="btn_title" class="form-label">Button Title</label>
                                                <input type="text" class="form-control" name="buttons[title1]" id="btn_title"
                                                    placeholder="Button Title here" value="{{ json_decode($banner->buttons)->title1 }}" >
                                                <div class="invalid-feedback">
                                                    Please enter valid button title.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="btn_title" class="form-label">Button Title</label>
                                                <input type="text" class="form-control" name="buttons[title2]" id="btn_title"
                                                    placeholder="Button Title here" value="{{ json_decode($banner->buttons)->title2 }}" >
                                                <div class="invalid-feedback">
                                                    Please enter valid button title.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="mb-3">
                                                <label for="btn_link" class="form-label">Button Link</label>
                                                <input type="text" class="form-control" name="buttons[link1]" id="btn_link"
                                                    placeholder="Button Link here" value="{{ json_decode($banner->buttons)->link1 }}" >
                                                <div class="invalid-feedback">
                                                    Please enter valid button link.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="mb-3">
                                                <label for="btn_link" class="form-label">Button Link</label>
                                                <input type="text" class="form-control" name="buttons[link1]" id="btn_link"
                                                    placeholder="Button Link here" value="{{ json_decode($banner->buttons)->link2 }}" >
                                                <div class="invalid-feedback">
                                                    Please enter valid button link.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="mb-3">
                                                <label for="page" class="form-label">Page</label>
                                                <select class="form-select select2" id="page" name="page" required>
                                                    <option selected disabled value="">Select Page</option>
                                                    @forelse ($pages as $page)
                                                        <option value="{{$page->slug}}" @if($banner->page == $page->slug) {{ 'selected' }} @endif>{{ $page->name ?? '' }}</option>
                                                    @empty
                                                        <option value="">No Page Found!</option>
                                                    @endforelse
                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select page
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
                                                <label for="image" class="form-label">Image</label>
                                                <input type="file" class="form-control" id="image" name="image"/>
                                                <div class="invalid-feedback">
                                                    Please upload valid image
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="mb-3">
                                                <label class="form-label">Current Image</label>
                                                <img src="{{asset('assets/frontend/images/banners/'.$banner->image)}}" class="form-control img-thumbnail" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <button class="btn btn-primary" type="submit">UPDATE BANNER</button>
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
@endpush
