@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="row table_setting">
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Activity Logs</h5>
                    </div>
                    <div class="card-body p-0">
                        <div id="myTable">
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
                                        <tr class="app-sort">
                                            <th>Date</th>
                                            <th>User</th>
                                            <th>IP</th>
                                            <th>Device</th>
                                            <th>Action</th>
                                            <th>Table</th>
                                            <th>Description</th>
                                            <th>Changed Data</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list" id="t-data">
                                        @forelse($logs as $log)
                                            <tr @if ($log->hash_changed) class="table-warning" @endif>
                                                <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d-M-Y h:i:s A') }} 
                                                <td class="email">{{ $log->user_name ?? 'System' }}</td>
                                                <td class="email">{{ $log->ip_address }}</td>
                                                <td class="contact">{{ $log->device_id ?? '-' }}</td>
                                                <td>{{ ucfirst($log->action) }}</td>
                                                <td class="status">{{ $log->table_name }}</td>
                                                <td class="email">{{ $log->description }}</td>
                                                <td class="status">
                                                    @if ($log->action === 'update' && !empty($log->diff))
                                                        <ul class="mb-0">
                                                            @foreach ($log->diff as $field => $values)
                                                                <li>
                                                                    <strong>{{ $field }}:</strong>
                                                                    <span class="text-danger">"{{ $values['old'] }}"</span>
                                                                    →
                                                                    <span class="text-success">"{{ $values['new'] }}"</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        {{-- <pre class="mb-0">{{ json_encode($log->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre> --}}
                                                        <div class="json-container">
                                                            <button class="btn btn-sm btn-light toggle-json mb-1">+</button>
                                                            <pre class="json-content mb-0 d-none">{{ json_encode($log->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </div>
                                                    @endif
                                                    @if ($log->hash_changed)
                                                        <div class="text-danger fw-bold mt-1">
                                                            ⚠ Data hash mismatch — possible tampering detected!
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No activity found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="paginationtble-bottom">
                                    {{ $logs->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script nonce="{{ $nonce }}">
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.toggle-json').forEach(button => {
                    button.addEventListener('click', function() {
                        const pre = this.nextElementSibling;
                        if (pre.classList.contains('d-none')) {
                            pre.classList.remove('d-none');
                            this.textContent = '-';
                        } else {
                            pre.classList.add('d-none');
                            this.textContent = '+';
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
