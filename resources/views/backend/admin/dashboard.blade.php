@extends('layouts.backend.master')
@section('title') {{ __('Dashboard') }} @endsection

@section('page-content')
	<div class="page-content">
	    <div class="container-fluid">
	        <!-- start page title -->
	        <div class="row">
	            <div class="col-12">
	                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
	                    <h4 class="mb-sm-0 font-size-18">Dashboard | {{ @Auth::user()->first_name . ' ' . @Auth::user()->last_name }}</h4>

	                    <div class="page-title-right">
	                        <ol class="breadcrumb m-0">
	                            <li class="breadcrumb-item"><a href="javascript: void(0);">{{ config('app.name') }}</a></li>
	                            <li class="breadcrumb-item active">Dashboard</li>
	                        </ol>
	                    </div>

	                </div>
	            </div>
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
	        </div>
            <?php
                echo "<pre>";
                // print_r($currentUser);
                echo "</pre>";
            ?>
	        <!-- end page title -->
	        {{-- <div class="row">
	            <div class="col-xl-12">
	                <div class="row">
	                    <div class="col-md-3">
	                        <div class="card mini-stats-wid">
	                            <div class="card-body">
	                                <div class="d-flex">
	                                    <div class="flex-grow-1">
	                                        <p class="text-muted fw-medium">TODAY TASKS</p>
	                                        <h4 class="mb-0">{{ $todayTasks ?? 0 }}</h4>
	                                    </div>

	                                    <div class="flex-shrink-0 align-self-center">
	                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
	                                            <span class="avatar-title">
	                                                <i class="bx bx-notepad font-size-24"></i>
	                                            </span>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-md-3">
	                        <div class="card mini-stats-wid">
	                            <div class="card-body">
	                                <div class="d-flex">
	                                    <div class="flex-grow-1">
	                                        <p class="text-muted fw-medium">ALL TASKS</p>
	                                        <h4 class="mb-0">{{ $allTasks ?? 0 }}</h4>
	                                    </div>

	                                    <div class="flex-shrink-0 align-self-center ">
	                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
	                                            <span class="avatar-title rounded-circle bg-primary">
	                                                <i class="bx bx-task font-size-24"></i>
	                                            </span>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-md-3">
	                        <div class="card mini-stats-wid">
	                            <div class="card-body">
	                                <div class="d-flex">
	                                    <div class="flex-grow-1">
	                                        <p class="text-muted fw-medium">PICKUPS</p>
	                                        <h4 class="mb-0">{{ $pickups ?? 0 }}</h4>
	                                    </div>

	                                    <div class="flex-shrink-0 align-self-center">
	                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
	                                            <span class="avatar-title rounded-circle bg-primary">
	                                                <i class="bx bx-car font-size-24"></i>
	                                            </span>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-md-3">
	                        <div class="card mini-stats-wid">
	                            <div class="card-body">
	                                <div class="d-flex">
	                                    <div class="flex-grow-1">
	                                        <p class="text-muted fw-medium">DELIVERIES</p>
	                                        <h4 class="mb-0">{{ $delivered ?? 0 }}</h4>
	                                    </div>

	                                    <div class="flex-shrink-0 align-self-center">
	                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
	                                            <span class="avatar-title rounded-circle bg-primary">
	                                                <i class="bx bxs-truck font-size-24"></i>
	                                            </span>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
                        <div class="col-md-3">
	                        <div class="card mini-stats-wid">
	                            <div class="card-body">
	                                <div class="d-flex">
	                                    <div class="flex-grow-1">
	                                        <p class="text-muted fw-medium">PENDING TASKS</p>
	                                        <h4 class="mb-0">{{ $pendingTasks ?? 0 }}</h4>
	                                    </div>

	                                    <div class="flex-shrink-0 align-self-center">
	                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
	                                            <span class="avatar-title rounded-circle bg-warning">
	                                                <i class="fas fa-exclamation-triangle text-white font-size-24"></i>
	                                            </span>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <!-- end row -->
	            </div>
	        </div> --}}
	        <!-- end row -->
	    </div>
	    <!-- container-fluid -->
	</div>
@endsection
@section('scripts')
	<script type="text/javascript">

	</script>
@endsection
