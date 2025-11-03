@extends('admin.layouts.adminlayout')
@section('content')
    <div class="container-fluid">
        <div class="row table_setting">
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Business Details</h5>
                    </div>
                    <div class="card-body p-0">
                        {{--  Table --}}
                        <div id="myTable">
                            <div class="list-table-header d-flex justify-content-between align-items-center p-3">
                                <a href="{{ route('admin.register') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-plus fa-fw"></i>Add New Business
                                </a>
                                <form class="app-form app-icon-form" action="#">
                                    <div class="position-relative">
                                        <input type="search" class="form-control search" placeholder="Search..."
                                            aria-label="Search">
                                    </div>
                                </form>
                            </div>
                            <div class="app-scroll overflow-auto">
                                <table id="businessTable" class="table table-striped table-bordered m-0 align-middle">
                                    <thead>
                                        <tr class="app-sort">
                                            <th class="w-50">Name</th>
                                            <th class="w-50">NTN/CNIC</th>
                                            <th class="w-50">Users</th>
                                            <th class="w-50">Scenarios</th>
                                            <th class="w-50">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="businessData">
                                        @forelse ($businesses as $b)
                                            <tr class="border-t">
                                                <td>{{ $b->bus_name }}</td>
                                                <td>{{ $b->bus_ntn_cnic }}</td>
                                                <td>{{ $b->users_count }}</td>
                                                <td>{{ $b->scenarios_count }}</td>
                                                <td>
                                                    <a href="{{ route('admin.businesses.show', \Illuminate\Support\Facades\Crypt::encryptString($b->bus_config_id)) }}"
                                                        class="btn btn-xs btn-outline-warning">
                                                        <i class="ti ti-eye"></i>
                                                    </a>

                                                    @if ($b->db_username == 'dummy' || $b->db_password == 'dummy')
                                                        <a href="{{ route('admin.db.clone.form') }}"
                                                            class="btn btn-xs btn-outline-primary">
                                                            <i class="ti ti-database"></i>

                                                        </a>
                                                    @endif

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No Business found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="paginationtble-bottom">
                                    {{ $businesses->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
