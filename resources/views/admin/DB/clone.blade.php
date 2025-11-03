@extends('admin.layouts.adminlayout')
@section('content')
    <div class="container-fluid">
        <div class="row table_setting">
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Clone Database</h5>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="alertMessage">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alertMessage">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="card-body">
                        <form class="app-form needs-validation" action="{{ route('admin.db.clone') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <!-- Source Database -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Select Database to Clone</label>
                                    <select name="source_db" class="form-select" required>
                                        <option value="">-- Select Database --</option>
                                        @foreach ($databases as $db)
                                            <option value="{{ $db }}">{{ $db }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- New Database Name -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">New Database Name</label>
                                    <input type="text" name="new_db" class="form-control"
                                        placeholder="Enter new DB name" required>
                                </div>
                            </div>
                            <!-- Submit -->
                            <div class="row mt-4">
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="ti ti-database-export me-2"></i> Clone Database
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
