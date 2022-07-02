@extends('layouts.backend.master')
@section('title')
    {{ __('All Products') }}
@endsection
@section('styles')
    <!-- Filepond stylesheet -->
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
@endsection
@section('page-content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ __(' Event Ticket') }}</h4>
                        {{-- <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ __('Products') }}</a></li>
                                <li class="breadcrumb-item active">{{ __('Add Product') }}</li>
                            </ol>
                        </div> --}}
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
                {{-- <div class="col-sm-12 mb-2">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-primary">ALL PRODUCTS</a>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-success">ADD NEW PRODUCT</a>
                    <a href="{{ route('admin.trash.products') }}" class="btn btn-sm btn-danger">TRASH</a>
                </div> --}}
                <form class="needs-validation" method="POST" action="{{ route('event-tickets.update', $EventTicket->id) }}" enctype="multipart/form-data" novalidate>
                    <div class="row">
                        @csrf
                        @method('PUT')
                        <div class="card">
                            <div class="row">
                                <div class="col-sm-12 mb-3">
                                    <label class="form-label" for="order_status">
                                        Status</label>
                                    <select id="order_status" name="status"
                                        class="form-control" required>
                                        <option value="new" @if($EventTicket->status == 'new') {{ 'selected' }} @endif>New</option>
                                        <option value="Expired" @if($EventTicket->status == 'Pending') {{ 'selected' }} @endif >Expired</option>
                                        
                                    </select>
                                    <div class="invalid-feedback">Select Status
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 mb-3">
                                    <label class="form-label"
                                        for="description">Message</label>
                                    <div>
                                        <textarea class="form-control" id="message" name="message" rows="3"
                                            placeholder="Enter Message for user" required="required">{{ $EventTicket->message }}</textarea>
                                        <div class="invalid-feedback">Enter Message
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 mb-5">
                            <button type="submit" class="btn btn-primary">Update Status</button>
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
    <!-- form mask -->
    <script src="{{ asset('assets/backend/libs/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <!-- form mask init -->
    <script src="{{ asset('assets/backend/js/pages/form-mask.init.js') }}"></script>
    <script src="{{ asset('assets/backend/ckeditor/ckeditor.js') }}"></script>
    <script>
        $(document).on('keyup', '#name', function() {
            $.ajax({
                url: route('create.slug'),
                type: 'POST',
                data: {
                    'name': $(this).val()
                },
                success: function(response) {
                    $('#slug').val(response);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    </script>
@endsection
