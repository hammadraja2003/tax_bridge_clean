@extends('admin.layouts.adminlayout')

@section('content')
    <div class="container-fluid">
        <div class="row project_dashboard">

            <div class="col-md-6 col-lg-6">
                <div class="card project-cards">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <h6>Total Businesses</h6>
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <h4 class="text-warning f-w-600 counting" data-count="{{ $totalBusinesses }}">
                                    {{ $totalBusinesses }}
                                </h4>
                            </div>
                        </div>
                        <div class="project-card-icon project-secondary bg-light-warning h-55 w-55 d-flex-center b-r-100">
                            <i class="ti ti-building f-s-30 mb-1"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-6">
                <div class="card project-cards">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <h6>Total Users</h6>
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <h4 class="text-success f-w-600 counting" data-count="{{ $totalUsers }}">
                                    {{ $totalUsers }}
                                </h4>
                            </div>
                        </div>
                        <div class="project-card-icon project-success bg-light-success h-55 w-55 d-flex-center b-r-100">
                            <i class="ti ti-users f-s-30 mb-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
