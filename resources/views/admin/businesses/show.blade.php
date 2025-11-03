@extends('admin.layouts.adminlayout')

@section('content')
    <div class="container-fluid">
        <div class="row table_setting">
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-3 text-primary">{{ $business->bus_name }}</h3>

                        {{-- Business Basic Info --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p><strong>NTN/CNIC:</strong> {{ $business->bus_ntn_cnic }}</p>
                                <p><strong>Address:</strong> {{ $business->bus_address }}</p>
                                <p><strong>Contact:</strong> {{ $business->bus_contact_person }}
                                    ({{ $business->bus_contact_num }})</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Account:</strong> {{ $business->bus_account_title }} -
                                    {{ $business->bus_account_number }}</p>
                                <p><strong>IBAN:</strong> {{ $business->bus_IBAN }}</p>
                                <p><strong>SWIFT:</strong> {{ $business->bus_swift_code }}</p>
                            </div>
                        </div>

                        <hr>

                        {{-- Users Table --}}
                        <h5 class="mt-4">👤 Users</h5>
                        @if ($business->users->count() > 0)
                            <div class="table-responsive mt-3">
                                <table class="table table-striped table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($business->users as $index => $user)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->created_at?->format('d M Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">No users linked to this business.</p>
                        @endif

                        <hr>

                        {{-- Scenarios Table --}}
                        <h5 class="mt-4">🧩 Scenarios</h5>
                        @if ($business->scenarios->count() > 0)
                            <div class="table-responsive mt-3">
                                <table class="table table-striped table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th>Description</th>
                                            <th>Sale Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($business->scenarios as $index => $scenario)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $scenario->scenario_description ?? '-' }}</td>
                                                <td>{{ $scenario->sale_type ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">No scenarios linked.</p>
                        @endif

                        {{-- Back Button --}}
                        <div class="mt-4">
                            <a href="{{ route('admin.businesses.index') }}" class="btn btn-secondary">← Back to List</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
