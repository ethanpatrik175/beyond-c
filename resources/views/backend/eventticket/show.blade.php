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
                    <div class="col-xl-12">
                        <div class="mt-4">
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button fw-medium" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                            aria-expanded="true" aria-controls="collapseOne">
                                            Ticket Detail
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                             <table class="table">
                                                <tr>
                                                    <th>
                                                      Price
                                                    </th>
                                                    <td>
                                                        {{ $EventTicket->price }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Sub Total
                                                    </th>
                                                    <td>
                                                        {{ $EventTicket->total_price }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Amount
                                                    </th>
                                                    <td>
                                                        ${{$payment_info->amount}}
                                                    </td>
                                                </tr>
                                               <tr>
                                                    <th>
                                                       Currency
                                                    </th>
                                                    <td>
                                                        {{ $payment_info->currency }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                       Description
                                                    </th>
                                                    <td>
                                                        {{ $payment_info->description }}
                                                    </td>
                                                </tr>
                                            </table> 
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingfour">
                                        <button class="accordion-button fw-medium collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapsefour"
                                            aria-expanded="false" aria-controls="collapsefour">
                                            Event Detail
                                        </button>
                                    </h2>
                                    <div id="collapsefour" class="accordion-collapse collapse"
                                        aria-labelledby="headingfour" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <table class="table">
                                                <tr>
                                                    <th>
                                                        Title
                                                    </th>
                                                    <td>
                                                        {{ $EventTicket->events->title }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Sub Title
                                                    </th>
                                                    <td>
                                                        {{ $EventTicket->events->sub_title }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Start at
                                                    </th>
                                                    <td>
                                                       {{ $EventTicket->events->start_at }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Number Of Days
                                                    </th>
                                                    <td>
                                                        ${{ $EventTicket->events->day_number }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Orig Price
                                                    </th>
                                                    <td>
                                                        ${{ $event->orig_price }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Price
                                                    </th>
                                                    <td>
                                                        ${{ $event->price }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Description
                                                    </th>
                                                    <td>
                                                        {!! json_decode($EventTicket->events->description) !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Summary
                                                    </th>
                                                    <td>
                                                        {{$EventTicket->events->summary }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Image
                                                    </th>
                                                    <td>
                                                        <img
                                                            src="{{ asset('assets/frontend/images/events/' . $EventTicket->events->image) }}">
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                               
                            </div>
                            <!-- end accordion -->
                        </div>
                    </div>
                    <!-- end col -->


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
