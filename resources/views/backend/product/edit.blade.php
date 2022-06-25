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
            <form class="needs-validation" method="POST" action="{{route('products.update', $Product->id)}}"
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
                                            <label for="Title" class="form-label">Title</label>
                                            <input type="text" class="form-control" name="Title" id="title"
                                                placeholder="Title here" value="{{$Product->title ?? ''}}" required
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
                                                placeholder="Slug" value="{{$Product->slug ?? ''}}">
                                            <div class="invalid-feedback">
                                                Please enter valid slug.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Product Category</label>
                                            <select  name="category_id"
                                                class="form-control select2">
                                                <option value="">Product Category</option>
                                                @foreach($parents as $repo)
                                                <option value="{{$repo->id}}" {{ ($Product->category_id == $repo->id) ? 'selected' : '' }} >{{$repo->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Brand</label>
                                            <select name="Brand_id" class="form-control select2">
                                                <option value="">Select Brand</option>
                                                @foreach($Brand as $repo)
                                                <option value="{{$repo->id}}" {{ ($Product->brand_id == $repo->id) ? 'selected' : '' }} >{{$repo->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Related Product</label>
                                            <select class="select2 form-control select2-multiple" multiple="multiple"
                                                data-placeholder="Choose ..." name="related_product_id[]">
                                                @foreach ($Products as $repo)
                                                <option value="{{ $repo->id }}" {{ in_array($repo->id, $related_product) ? 'selected' : '' }}>{{ $repo->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="type" class="form-label">Product Type</label>
                                            <input type="text" class="form-control" name="type" id="type"
                                                placeholder="Product Type" value="{{$Product->type ?? ''}}">
                                            <div class="invalid-feedback">
                                                Please enter valid Product Type.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="sku" class="form-label">Stock kepping Unit</label>
                                            <input type="text" class="form-control" name="sku" id="sku"
                                                placeholder="Stock kepping Unit" value="{{$Product->sku ?? ''}}">
                                            <div class="invalid-feedback">
                                                Please enter valid Stock kepping Unit.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="regular_price" class="form-label">Regular Price $</label>
                                            <input type="number" step="any" class="form-control" name="regular_price"
                                                id="regular_price" placeholder="Regular Price "
                                                value="{{$Product->regular_price}}" required>
                                            <div class="invalid-feedback">
                                                Please enter valid Regular Price.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="sale_price" class="form-label">Sale Price $</label>
                                            <input type="number" step="any" class="form-control" name="sale_price"
                                                id="sale_price" placeholder="Sale Price " value="{{$Product->sale_price}}"
                                                required>
                                            <div class="invalid-feedback">
                                                Please enter valid Sale Price.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="discount" class="form-label">Discount in %</label>
                                            <input type="number" step="any" class="form-control" name="discount" id="discount"
                                                placeholder="Discount" value="{{$Product->discount ?? ''}}">
                                            <div class="invalid-feedback">
                                                Please enter valid Discount.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label">Quantity</label>
                                            <input type="number" class="form-control" name="quantity" id="quantity"
                                                placeholder="Quantity" value="{{$Product->quantity ?? ''}}">
                                            <div class="invalid-feedback">
                                                Please enter valid Quantity.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Stock Status </label>
                                            <select name="stock_status" class="form-control select2" required >
                                                <option value="">Select Stock Status</option>
                                                <option value="Stock Available" {{($Product->stock_status == "Stock Available") ? 'selected' : '' }}>Stock Available</option>
                                                <option value="Stock Shortage" {{($Product->stock_status == "Stock Shortage") ? 'selected' : '' }}>Stock Shortage</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="stock_alt_quantity" class="form-label">Stock Alt
                                                Quantity</label>
                                            <input type="number" class="form-control" name="stock_alt_quantity"
                                                id="stock_alt_quantity" placeholder="Stock Alter Quantity "
                                                value="{{$Product->stock_alert_quantity}}" required>
                                            <div class="invalid-feedback">
                                                Please enter valid Stock Alter Quantity.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="stock_alt_quantity" class="form-label">Select Color</label>
                                            <input type="color" class="form-control" name="color"
                                                id="stock_alt_quantity" 
                                                value="{{old('color')}}" required>
                                            <div class="invalid-feedback">
                                                Please enter valid Select Color.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Size</label>
                                            <select class="select2 form-control select2-multiple" multiple="multiple"
                                                data-placeholder="Choose ..." name="size[]">
                                                <option value=""></option>
                                                <option value="Small" {{ in_array("Small" , $size) ? 'selected' : '' }}> Small</option>
                                                <option value="Medium"{{ in_array("Medium" , $size) ? 'selected' : '' }}>Medium</option>
                                                <option value="large" {{ in_array("large" , $size) ? 'selected' : '' }} >large</option>
                                                <option value="Extra large" {{ in_array("Extra large" , $size) ? 'selected' : '' }} >Extra large</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="mb-3">
                                            <input type="checkbox" id="is_new" name="is_new" value="1" {{ ($Product->is_new =="1") ? 'checked':'' }} >
                                            <label for="is_new">Product Is New</label><br>
                                            <div class="invalid-feedback">
                                                Please enter valid Product Is Featured
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="mb-3">
                                            <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ ($Product->is_featured =="1") ? 'checked':'' }}>
                                            <label for="is_featured">Product Is Featured</label><br>
                                            <div class="invalid-feedback">
                                                Please enter valid Product Is Featured
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="mb-3">
                                            <label for="content" class="form-label">Content</label>
                                            <textarea rows="4" class="form-control" name="description" id="content"
                                                placeholder="Content here" required>{{ $Product->description }}</textarea>
                                            <div class="invalid-feedback">
                                                Please enter valid Content.
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
                                            <img src="{{asset('assets/frontend/images/products/'.$Product->icon)}}"
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
                                            <label for="meta_title" class="control-label">Meta Data</label>
                                            <input type="text" class="form-control" name="meta_title" id="meta_title"
                                                placeholder="Meta Title" value="{{ json_decode($Product->metaTitle)->meta_title }}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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

</script>
<script type="text/javascript">
    function myFunction() {

        var a = document.getElementById("title").value;

        var b = a.toLowerCase().replace(/ /g, '-')
            .replace(/[^\w-]+/g, '');
        console.log(b);
        document.getElementById("slug").value = b;

        // document.getElementById("slug-target-span").innerHTML = b;
    }

</script>
@endpush
