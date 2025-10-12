@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <h2>Audit Log #{{ $log->audit_id }}</h2>
        <div class="mb-3">
            <strong>Table:</strong> {{ $log->table_name }} <br>
            <strong>Row ID:</strong> {{ $log->row_id }} <br>
            <strong>Action:</strong> {{ $log->action_type }} <br>
            <strong>User:</strong> {{ $log->db_user }} <br>
            <strong>IP:</strong> {{ $log->ip_address }} <br>
            <strong>Device:</strong> {{ $log->device_info }} <br>
            <strong>Changed At:</strong> {{ $log->changed_at }} <br>
            <strong>Status:</strong>
            @if ($log->tampered)
                <span class="badge bg-danger">Tampered</span>
            @else
                <span class="badge bg-success">Safe</span>
            @endif
        </div>
        <h4>Changes</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Old Value</th>
                    <th>New Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($log->changes as $field => $change)
                    <tr>
                        <td>{{ $field }}</td>
                        <td>{{ is_array($change['old']) ? json_encode($change['old']) : $change['old'] }}</td>
                        <td>{{ is_array($change['new']) ? json_encode($change['new']) : $change['new'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
