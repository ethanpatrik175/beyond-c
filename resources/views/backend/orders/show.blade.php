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
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endforeach
            </div>
            @endif
            <div class="col-sm-12 message"></div>
            <form class="needs-validation create-form" method="POST" action="{{ route('orders.update', $order->id) }}"
                enctype="multipart/form-data" novalidate>
                <div class="row">
                    <div class="col-sm-10 mb-2">
                        {!! $buttons !!}
                    </div>
                </div>

                <div class="row">
                    @csrf
                    @method('PUT')
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-tabs" role="tablist">
                                   
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#menu3" role="tab">
                                            <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                            <span class="d-none d-sm-block">Items</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#menu4" role="tab">
                                            <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                            <span class="d-none d-sm-block">Recipt</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#menu5" role="tab">
                                            <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                            <span class="d-none d-sm-block">Order Status</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#menu6" role="tab">
                                            <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                            <span class="d-none d-sm-block">Order History</span>
                                        </a>
                                    </li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content p-3 text-muted">
                                   
                                    <div class="tab-pane" id="menu2" role="tabpanel">
                                       
                                    </div>
                                    <div class="tab-pane" id="menu3" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table align-middle mb-0 table-nowrap">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col">Product</th>
                                                        <th scope="col">Product Desc</th>
                                                        <th scope="col">Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @forelse ($order->items as $item)
                                                    <?php
                                                // dd($item->product);
                                                ?>
                                                    <tr>
                                                        <th scope="row"><img
                                                                src="{{asset('assets/frontend/images/products/'. $item->product[0]->icon)}}"
                                                                alt="product-img" title="product-img" class="avatar-md">
                                                        </th>
                                                        <td>
                                                            <h5 class="font-size-14 text-truncate"><a
                                                                    href="javascript:void(0);"
                                                                    class="text-dark">{{ __($item->product[0]->title) }}</a>
                                                            </h5>
                                                            <p class="text-muted mb-0">
                                                                ${{number_format($item->price, 2)}} *
                                                                {{$item->quantity}} </p>
                                                        </td>
                                                        <td>${{ $item->price }}</td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="3">No Item Found!</td>
                                                    </tr>
                                                    @endforelse
                                                    <tr>
                                                        <td colspan="2">
                                                            <h6 class="m-0 text-end">Sub Total:</h6>
                                                        </td>
                                                        <td>${{ number_format($order->total, 2) }}</td>
                                                    </tr>
                                                    {{--<tr>
                                                    <td colspan="2"><h6 class="m-0 text-end">Discount:</h6></td>
                                                    <td>&minus; ${{ number_format($order->discount_amount, 2) }}</td>
                                                    </tr> --}}
                                                    <tr>
                                                        <td colspan="2">
                                                            <h6 class="m-0 text-end">Delivery Charges:</h6>
                                                        </td>
                                                        <td>${{ number_format(10, 2) }}</td>
                                                    </tr>
                                                    @if(isset($order->additional_amount))
                                                    <tr>
                                                        <td colspan="2">
                                                            <h6 class="m-0 text-end">Additional Amount:
                                                        </td>
                                                        <td>${{ number_format($order->additional_amount, 2) }}</td>
                                                    </tr>
                                                    @endif
                                                    <tr>
                                                        <td colspan="2">
                                                            <h6 class="m-0 text-end">Grand Total:</h6>
                                                        </td>
                                                        <td>${{ number_format(($order->total+10), 2) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="menu4" role="tabpanel">
                                        <div id="invoice">
                                            <div class="toolbar hidden-print" style="text-align: right;">
                                                <div style="padding-right: 1%;">
                                                    <button id="printInvoice" onclick="jsPrintAll()"
                                                        class="btn btn-danger"><i class="fa fa-print"></i>
                                                        Print</button>
                                                </div>
                                            </div>
                                            <div id="invoice" class="invoice overflow-auto"
                                                style="position: relative; background-color: #FFF; min-height: 680px; padding: 15px;">
                                                <div style="min-width: 600px">
                                                    <header
                                                        style="padding: 10px 0; margin-bottom: 20px; border-bottom: 1px solid #3989c6;">
                                                        <div class="row row-format"
                                                            style="margin-right: auto; margin-left: auto;">
                                                            <div class="col border p-1"
                                                                style="border: black solid 2px;">
                                                                <a href="javascript:void(0);">
                                                                    <img style="background-color: black !important;"
                                                                        src="{{asset('assets/frontend/images/logos/logo.png')}}"
                                                                        data-holder-rendered="true">
                                                                </a>
                                                                <div class="company-details">
                                                                    <h6 class="name company-name"
                                                                        style="padding-top: 8px; margin-top: 0; margin-bottom: 0;">
                                                                        {{ config('app.name') }}</h6>
                                                                    <div class="fonts-12" style="font-size: 12px;">
                                                                        Street 4, golden road new york</div>
                                                                    <div class="fonts-12" style="font-size: 12px;">
                                                                        <strong>Tel:</strong> <a
                                                                            href="tel:123-456-7890">123-456-7890</a>
                                                                        <br> <strong>Email:</strong> <a
                                                                            href="mailto:info@pressn-go.com">info@pressn-go.com</a>
                                                                        <br> <strong>website:</strong><a
                                                                            href="http://www.pressn-go.com"
                                                                            target="_blank" rel="noopener noreferrer">
                                                                            www.pressn-go.com</a></div>
                                                                </div>
                                                            </div>
                                                            <div class="col border company-details p-1"
                                                                style="border: black solid 2px;">
                                                                <h6 class="name"
                                                                    style="margin-top: 0; margin-bottom: 0;">Pickup &
                                                                    Delivery Address:</h6>

                                                                <div>

                                                                    <strong>{{ $order->address}}:</strong> <br />

                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="row row-format mt-1"
                                                            style="margin-right: auto; margin-left: auto;">
                                                            <div class="col border"
                                                                style="border: black solid 2px; padding:5px;">
                                                                <h6 class="name mb-0">Order No#</h6>
                                                                <div>{{ $order->order_number }}</div>
                                                            </div>
                                                            <div class="col border"
                                                                style="border: black solid 2px; padding:5px;">
                                                                <h6 class="name mb-0">Order Date</h6>
                                                                <div>
                                                                    {{ date('Y-m-d h:i:s A', strtotime($order->created_at)) }}
                                                                </div>
                                                            </div>
                                                            <div class="col border"
                                                                style="border: black solid 2px; padding:5px;">
                                                                <h6 class="name mb-0">Customer</h6>
                                                                <?php
                                                          
                                                            ?>
                                                                <div>{{ $order->firstName.' '.$order->lastName }}</div>
                                                            </div>
                                                        </div>
                                                    </header>
                                                    <main style="">
                                                        <table border="0" cellspacing="0" cellpadding="0"
                                                            style="width: 100%; border-collapse: collapse; border-spacing: 0; margin-bottom: 20px; border-color:#000;"
                                                            width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th
                                                                        style="padding: 3px; border: 1px solid #000; background: #eee; white-space: nowrap; font-weight: 400; font-size: 16px;">
                                                                        <strong>#</strong></th>
                                                                    <th class="text-left"
                                                                        style="padding: 3px; border: 1px solid #000; background: #eee; white-space: nowrap; font-weight: 400; font-size: 16px;">
                                                                        <strong>ITEM</strong></th>
                                                                    <th class="text-right"
                                                                        style="padding: 3px; border: 1px solid #000; background: #eee; white-space: nowrap; font-weight: 400; font-size: 16px;">
                                                                        <strong>PRICE</strong></th>
                                                                    <th class="text-right"
                                                                        style="padding: 3px; border: 1px solid #000; background: #eee; white-space: nowrap; font-weight: 400; font-size: 16px;">
                                                                        <strong>QTY:</strong></th>
                                                                    <th class="text-left"
                                                                        style="padding: 3px; border: 1px solid #000; background: #eee; white-space: nowrap; font-weight: 400; font-size: 16px;">
                                                                        <strong>UNIT</strong></th>
                                                                    <th class="text-right"
                                                                        style="padding: 3px; border: 1px solid #000; background: #eee; white-space: nowrap; font-weight: 400; font-size: 16px;">
                                                                        <strong>TOTAL</strong></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $counter = 1; 
                                                            // dd($order->items->product);
                                                            ?>
                                                                @forelse($order->items as $item)
                                                                <?php
                                                            // dd($item->product[0]->title);
                                                            ?>
                                                                <tr>
                                                                    <td class="no"
                                                                        style="padding: 3px; border: 1px solid #000; color: #000; font-size: 1.6em; background: #3989c6;">
                                                                        {{ $counter++ }}</td>
                                                                    <td class="text-left item-text-size"
                                                                        style="font-size: 1.2em; padding: 3px; background: #eee; border-bottom: 1px solid #000;">
                                                                        {{ $item->product[0]->title }}</td>
                                                                    <td class="unit item-text-size"
                                                                        style="padding: 3px; border: 1px solid #000; text-align: right; font-size: 1.2em; background: #ddd;"
                                                                        align="right">
                                                                        ${{ number_format($item->price, 2) }}</td>
                                                                    <td class="qty item-text-size"
                                                                        style="padding: 3px; background: #eee; border: 1px solid #000; text-align: right; font-size: 1.2em;"
                                                                        align="right">x{{ $item->quantity }}</td>
                                                                    <td class="text-left item-text-size"
                                                                        style="font-size: 1.2em; padding: 3px; background: #eee; border: 1px solid #000;">
                                                                        {{ $item->quantity }}</td>
                                                                    <td class="total item-text-size"
                                                                        style="padding: 3px; border: 1px solid #000; text-align: right; font-size: 1.2em; background: #3989c6; color: #000;"
                                                                        align="right">
                                                                        ${{ number_format($item->price, 2) }}</td>
                                                                </tr>
                                                                @empty

                                                                @endforelse
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="3"
                                                                        style="background: 0 0; border-bottom: none; white-space: nowrap; text-align: right; padding: 10px 20px; font-size: 1.2em; border-top: none; border: none;"
                                                                        align="right"></td>
                                                                    <td colspan="2"
                                                                        style="background: 0 0; border-bottom: none; white-space: nowrap; text-align: right; padding: 10px 20px; font-size: 1.2em; border-top: none;"
                                                                        align="right">{{ __('SUBTOTAL') }}</td>
                                                                    <td style="background: 0 0; border-bottom: none; white-space: nowrap; text-align: right; padding: 10px 20px; font-size: 1.2em; border-top: none;"
                                                                        align="right">
                                                                        ${{ number_format($order->total, 2) }}</td>
                                                                </tr>
                                                                {{--<tr>
                                                                <td colspan="3" style="background: 0 0; border-bottom: none; white-space: nowrap; text-align: right; padding: 10px 20px; font-size: 1.2em; border-top: 1px solid #aaa; border: none;" align="right"></td>
                                                                <td colspan="2" style="background: 0 0; border-bottom: none; white-space: nowrap; text-align: right; padding: 10px 20px; font-size: 1.2em; border-top: 1px solid #aaa;" align="right">{{ __('DISCOUNT') }}
                                                                </td>
                                                                <td style="background: 0 0; border-bottom: none; white-space: nowrap; text-align: right; padding: 10px 20px; font-size: 1.2em; border-top: 1px solid #aaa;"
                                                                    align="right">&minus;
                                                                    ${{ number_format($order->discount_amount, 2) }}
                                                                </td>
                                                                </tr>
                                                                --}}
                                                                <tr>
                                                                    <td colspan="3"
                                                                        style="background: 0 0; border-bottom: none; white-space: nowrap; text-align: right; padding: 10px 20px; font-size: 1.2em; border-top: 1px solid #aaa; border: none;"
                                                                        align="right"></td>
                                                                    <td colspan="2"
                                                                        style="background: 0 0; border-bottom: none; white-space: nowrap; text-align: right; padding: 10px 20px; font-size: 1.2em; border-top: 1px solid #aaa;"
                                                                        align="right">{{ __('DELIVERY CHARGES') }}</td>
                                                                    <td style="background: 0 0; border-bottom: none; white-space: nowrap; text-align: right; padding: 10px 20px; font-size: 1.2em; border-top: 1px solid #aaa;"
                                                                        align="right">${{ number_format(10, 2) }}</td>
                                                                </tr>
                                                                @if(isset($order->additional_amount))
                                                                <tr>
                                                                    <td colspan="3"
                                                                        style="background: 0 0; border-bottom: none; white-space: nowrap; text-align: right; padding: 10px 20px; font-size: 1.2em; border-top: 1px solid #aaa; border: none;"
                                                                        align="right"></td>
                                                                    <td colspan="2"
                                                                        style="background: 0 0; border-bottom: none; white-space: nowrap; text-align: right; padding: 10px 20px; font-size: 1.2em; border-top: 1px solid #aaa;"
                                                                        align="right">{{ __('ADDITIONAL AMOUNT') }}</td>
                                                                    <td style="background: 0 0; border-bottom: none; white-space: nowrap; text-align: right; padding: 10px 20px; font-size: 1.2em; border-top: 1px solid #aaa;"
                                                                        align="right">
                                                                        ${{ number_format($order->additional_amount, 2) }}
                                                                    </td>
                                                                </tr>
                                                                @endif

                                                                <tr>
                                                                    <td colspan="3"
                                                                        style="background: 0 0; border-bottom: none; white-space: nowrap; text-align: right; padding: 10px 20px; color: #3989c6; font-size: 1.4em; border-top: 1px solid #3989c6; border: none;"
                                                                        align="right"></td>
                                                                    <td colspan="2"
                                                                        style="background: 0 0; border-bottom: none; white-space: nowrap; text-align: right; padding: 10px 20px; color: #3989c6; font-size: 1.4em; border-top: 1px solid #3989c6;"
                                                                        align="right">{{ __('GRAND TOTAL') }}</td>
                                                                    <td style="background: 0 0; border-bottom: none; white-space: nowrap; text-align: right; padding: 10px 20px; color: #3989c6; font-size: 1.4em; border-top: 1px solid #3989c6;"
                                                                        align="right">
                                                                        @if(isset($order->additional_amount))
                                                                        ${{ number_format(($order->grand_total+$order->additional_amount+$order->delivery_charges), 2) }}
                                                                        @else
                                                                        ${{ number_format(($order->total+10), 2) }}
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                        <div class="thanks"
                                                            style="margin-top: -100px; font-size: 2em; margin-bottom: 50px;">
                                                            {{ __('Thank you') }}!</div>
                                                        @if(isset($order->instructions))
                                                        <div class="notices"
                                                            style="padding-left: 6px; border-left: 6px solid #3989c6;">
                                                            <div>{{ __('ORDER INSTRUCTIONS') }}:</div>
                                                            <div class="notice" style="font-size: 1.2em;">
                                                                {{ __($order->instructions) }}</div>
                                                        </div>
                                                        @endif
                                                    </main>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="menu5" role="tabpanel">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h4 class="card-title">{{ __('UPDATE ORDER STATUS') }}</h4>
                                                        <hr class="mt-1" />
                                                        <form id="updatePayment" class="needs-validation" method="POST"
                                                            action="{{route('order.updated')}}" novalidate>
                                                            @csrf
                                                            <?php
                                                        // dd($order);
                                                        ?>
                                                            <input type="hidden" name="order_id"
                                                                value="{{$order->id}}" />
                                                            <div class="row">
                                                                <div class="col-sm-12 mb-3">
                                                                    <label class="form-label" for="order_status">Order
                                                                        Status</label>
                                                                    <select id="order_status" name="order_status"
                                                                        class="form-control" required>
                                                                        <option value="{{$order->order_status}}"
                                                                            selected>{{$order->order_status}}</option>
                                                                        <option value="new">new</option>
                                                                        <option value="Pending">Pending</option>
                                                                        <option value="Approved">Approved</option>
                                                                        <option value="Delivered">Delivered</option>
                                                                    </select>
                                                                    <div class="invalid-feedback">Select Order Status
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12 mb-3">
                                                                    <label class="form-label"
                                                                        for="description">Description</label>
                                                                    <div>
                                                                        <textarea class="form-control" id="description"
                                                                            name="description" rows="3"
                                                                            placeholder="Enter description for user"
                                                                            required="required">{{ $order->description }}</textarea>
                                                                        <div class="invalid-feedback">Enter Description
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <button id="updatePaymentBtn" class="btn btn-primary"
                                                                    type="submit">UPDATE STATUS</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6"></div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="menu6" role="tabpanel">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="card-title">{{ __('ORDER HISTORY') }}</h4>
                                                    <hr class="mt-1"/>
                                                    <div class="table-responsive">
                                                        <table style="width:100% !important;" class="table table-bordered data-table wrap">
                                                            <thead>
                                                                <tr>
                                                                    <th>STATUS</th>
                                                                    <th>DESCRIPTION</th>
                                                                    <th>Created At</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script>
    CKEDITOR.replace('description');
    $(document).on('click', '#submit-form', function () {
        $(document).find('form.create-form').submit();
    });
    $(function() {
        
            let id = "{{ $order->id }}";
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: route('order.history', id),
                columns: [{
                    data: 'status',
                    name: 'status'
                }, {
                    data: 'description',
                    name: 'description'
                }, {
                    data: 'created',
                    name: 'created'
                }],
                responsive: true,
                'createdRow': function(row, data, dataIndex) {
                    $(row).attr('id', data.id);
                },
                "bDestroy": true,
            });
        });
    // $('#printInvoice').click(function(){
    //         Popup();
    //         function Popup(data)
    //         {
    //             window.print();
    //             return true;
    //         }
    //     });
    var jsPrintAll = function () {
        console.log("test");
        setTimeout(function () {
            window.print();
        }, 500);
    }

</script>
<script src="{{ asset('assets/backend/libs/parsleyjs/parsley.min.js') }}"></script>
<script src="{{ asset('assets/backend/js/pages/form-validation.init.js') }}"></script>
<!-- form mask -->
<script src="{{asset('assets/backend/libs/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
<!-- form mask init -->
<script src="{{asset('assets/backend/js/pages/form-mask.init.js')}}"></script>
@endpush
