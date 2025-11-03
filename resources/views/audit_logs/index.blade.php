@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="row table_setting">
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Audit Logs</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="myTable">
                            <div class="list-table-header d-flex justify-content-end align-items-center p-3">
                                <form class="app-form app-icon-form" action="#">
                                    <div class="position-relative">
                                        <input type="search" class="form-control search" placeholder="Search..."
                                            aria-label="Search">
                                    </div>
                                </form>
                            </div>
                            <div class="app-scroll overflow-auto">
                                <table id="projectTableT" class="table table-striped table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th>Sr #</th>
                                            <th>Table</th>
                                            <th>Row ID</th>
                                            <th>Action</th>
                                            <th>User</th>
                                            <th>IP</th>
                                            <th>Device</th>
                                            <th>Changed At</th>
                                            <th>Status</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $count = 1; @endphp
                                        @foreach ($logs as $log)
                                            <tr class="{{ $log->tampered ? 'table-danger' : '' }}">
                                                <td>{{ $count++ }}</td>
                                                <td>{{ $log->table_name }}</td>
                                                <td>{{ $log->row_id }}</td>
                                                <td>{{ $log->action_type }}</td>
                                                <td>{{ $log->user_name ?? $log->db_user }}</td>
                                                <td>{{ $log->ip_address }}</td>
                                                <td>{{ $log->device_info }}</td>
                                                <td>{{ \Carbon\Carbon::parse($log->changed_at)->format('d-M-Y h:i:s A') }} 
                                                <td>
                                                    @if ($log->tampered)
                                                        <span class="badge bg-danger">Tampered</span>
                                                    @else
                                                        <span class="badge bg-success">Safe</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('audit_logs.show', ['id' => encrypt($log->audit_id)]) }}"
                                                        class="btn btn-xs btn-outline-success">
                                                        <i class="ti ti-eye f-s-20"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                    <div class="paginationtble-bottom">{!! $logs->links() !!}</div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
