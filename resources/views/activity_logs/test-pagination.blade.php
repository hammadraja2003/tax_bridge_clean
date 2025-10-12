@extends('layouts.admin')
@section('content')
   <div class="col-lg-6 col-xxl-4">
        <div class="card">
            <div class="card-header">
            <h5>Table with Pagination</h5>
            </div>
            <div class="card-body pb-0">
            <div id="user">
                <div class="mb-3">
                <input type="search" class="form-control search" placeholder="Search..." aria-label="Search">
                </div>
                <div class="app-scroll table-responsive">
                <table class="table table-bordered table-list-box align-middle mb-3">
                    <thead>
                        <tr>
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
                    <tbody class="list">
                        @forelse($logs as $log)
                            <tr @if ($log->hash_changed) class="table-warning" @endif>
                                <td class="date">{{ $log->created_at->format('d-M-Y H:i:s') }}</td>
                                <td class="name">{{ $log->user_name ?? 'System' }}</td>
                                <td class="email">{{ $log->ip_address }}</td>
                                <td class="contact">{{ $log->device_id ?? '-' }}</td>
                                <td class="status">{{ ucfirst($log->action) }}</td>
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
                </div>
                <div class="list-pagination">
                <ul class="pagination"></ul>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection