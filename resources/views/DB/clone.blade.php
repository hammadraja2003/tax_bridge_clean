@extends('layouts.login')
@section('content')
    <style>
        .required::after {
            content: " *";
            color: red;
        }

        @media (min-width: 1200px) {
            .card-body {
                min-height: 520px;
                /* adjust karein apne content ke hisaab se */
            }
        }

        @media (min-width: 1200px) {
            .card {
                min-height: 620px;
            }
        }

        .step-nav .nav-link {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 10px 15px;
            font-weight: 500;
            color: #495057;
            transition: all 0.3s ease;
            text-align: left;
        }

        .step-nav .nav-link .icon-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #f1f3f5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #ba2331;
        }

        .step-nav .nav-link.active {
            background: #ba2331;
            color: #fff;
            border-color: #ba2331;
        }

        .step-nav .nav-link.active .icon-circle {
            background: #fff;
            color: #ba2331;
        }

        .step-nav .nav-link:hover:not(.active) {
            background: #e9f2ff;
            border-color: #ba2331;
            color: #ba2331;
        }
    </style>
    @if (session('toast_error'))
        <script>
            toastr.error("{{ session('toast_error') }}", "Error", {
                closeButton: true,
                progressBar: true
            });
        </script>
    @endif
    <div class="col-12 py-5 bg-white">
        <div class="mb-5 text-center text-lg-start">
            <div class="d-flex justify-content-center align-items-center my-2">
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Clone Database</h5>
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
                    <form class="app-form needs-validation" action="{{ route('db.clone') }}" method="POST">
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
                                <input type="text" name="new_db" class="form-control" placeholder="Enter new DB name"
                                    required>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let alertBox = document.getElementById("alertMessage");
            if (alertBox) {
                setTimeout(() => {
                    alertBox.classList.remove("show"); // triggers Bootstrap fade out
                    alertBox.classList.add("fade");
                    setTimeout(() => alertBox.remove(), 500); // remove from DOM after fade
                }, 3000);
            }
        });
    </script>
@endsection
