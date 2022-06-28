@extends('layouts.backend.master')
@section('title') {{ __('All Contact Messages') }} @endsection
@section('styles')
    <link rel="stylesheet" href="{{asset('/assets/backend/datatable/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('/assets/backend/datatable/dataTables.bootstrap4.min.css')}}">
@endsection
@section('page-content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ __('All Contact Messages') }}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ __('Contact Messages') }}</a></li>
                                <li class="breadcrumb-item active">{{ __('All Contact Messages') }}</li>
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
                            @if (Session::get('type') == 'danger') <i class="mdi mdi-block-helper me-2"></i> @else <i class="mdi mdi-check-all me-2"></i> @endif
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
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered data-table wrap">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th width="30%;">Message</th>
                                        <th>Recieved At</th>
                                        <th>Read At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div> <!-- container-fluid -->
    </div>

    <div id="read-message" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!--<div class="col-sm-12 text-center business-info">-->
                        <!--    <img src="{{asset('assets/backend/images/logo-dark.png')}}" class="mb-3"/>-->
                        <!--</div>-->
                    </div>
                    <div class="message-detail"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection

@push('scripts')
    <script src="{{asset('/assets/backend/datatable/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('/assets/backend/datatable/dataTables.bootstrap4.min.js')}}"></script>
    <script type="text/javascript">

        $(function () {
            $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax:{
                    url: route('contact-us.index')
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'message', name: 'message'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'read_at', name: 'read_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                "order": [[ 0, "desc" ]],
            });
        });

        $(document).on('click', '.read-message', function(){
            let id = $(this).data('id');
            $.ajax({
                url: route('contact.read.message'),
                data:{id:id},
                type: "GET",
                beforeSend: function(){
                    $('.message-detail').html('<tr><td class="text-center" colspan="5">Loading...</td></tr>');
                },
                success:function(response){
                    let result = JSON.parse(response);
                    if(result.type == "success"){
                        const regex = /\\n|\\r\\n|\\n\\r|\\r/g;
                        let str = result.message;
                        let msg = str.replace(regex, '<br>');
                        $('.message-detail').html(msg);
                    }
                    else if(result.type == "error")
                    {
                        const regex = /\\n|\\r\\n|\\n\\r|\\r/g;
                        let str = result.message;
                        let msg = str.replace(regex, '<br>');
                        $('.message-detail').html(msg);
                    }
                },
                error:function(response){
                    console.log(response);
                }
            });
        });

        $(document).on("click", ".remove", function(event) {
            var flag = confirm('Are You Sure want to Remove Message?');
            if (flag) {
                var id = $(this).data('id');
                $.ajax({
                    url: route('remove.message'),
                    type: 'POST',
                    data: {id:id},
                    success: function(data) {
                        let result = JSON.parse(data);
                        console.log(result);
                        console.log(data);
                        $('.message').html('<div class="alert alert-' + result.type +
                            ' alert-dismissible fade show" role="alert"><i class="mdi ' + result.icon +
                            ' me-2"></i>' + result.message +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> </div>'
                        );

                        let table = $('.data-table').DataTable();
                        table.row('#' + id).remove().draw(false);
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            } else {
                event.preventDefault();
            }
        });
    </script>
@endpush
