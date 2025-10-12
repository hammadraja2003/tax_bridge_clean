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
                     <img src="{{ asset('assets/images/logo/' . config('app.logo')) }}" alt="Logo" class="dark-logo">
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Basic Configuration Form</h4>
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

                <div class="card-body">

                    <form class="app-form needs-validation" novalidate action="{{ route('register') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-wizard">
                            <div class="row">
                                <!-- Left Side Navigation -->
                                <div class="col-xl-3 mb-3">
                                    <div class="nav navstpes flex-column step-nav p-3 rounded shadow-sm bg-light"
                                        id="Basic" role="tablist">
                                        <button class="nav-link active d-flex align-items-center mb-2" id="v-features-tab"
                                            data-bs-toggle="tab" data-bs-target="#v-features-tab-pane" type="button"
                                            role="tab">
                                            <div class="icon-circle me-2">
                                                <i class="ti ti-info-circle"></i>
                                            </div>
                                            <span>Company Details</span>
                                        </button>
                                        <button class="nav-link d-flex align-items-center mb-2" id="v-history-tab"
                                            data-bs-toggle="tab" data-bs-target="#v-history-tab-pane" type="button"
                                            role="tab">
                                            <div class="icon-circle me-2">
                                                <i class="ti ti-building-bank"></i>
                                            </div>
                                            <span>Bank Details</span>
                                        </button>
                                        <button class="nav-link d-flex align-items-center mb-2" id="v-reviews-tab"
                                            data-bs-toggle="tab" data-bs-target="#v-reviews-tab-pane" type="button"
                                            role="tab">
                                            <div class="icon-circle me-2">
                                                <i class="ti ti-settings"></i>
                                            </div>
                                            <span>Configuration Details</span>
                                        </button>
                                        <button class="nav-link d-flex align-items-center" id="v-reviews-tab1"
                                            data-bs-toggle="tab" data-bs-target="#v-reviews-tab-pane1" type="button"
                                            role="tab">
                                            <div class="icon-circle me-2">
                                                <i class="ti ti-user-circle"></i>
                                            </div>
                                            <span>User Details</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Right Side Content -->
                                <div class="col-xl-9">
                                    <div class="tab-content" id="BasicContent">

                                        <!-- Company Details -->
                                        <div class="tab-pane fade show active" id="v-features-tab-pane" role="tabpanel">
                                            <div class="row g-3">

                                                <!-- Business Name -->
                                                <div class="col-md-4">
                                                    <label class="form-label required">Business Name</label>
                                                    <input type="text" name="bus_name"
                                                        class="form-control @error('bus_name') is-invalid @enderror"
                                                        value="{{ old('bus_name') }}" required
                                                        placeholder="Enter Business Name">
                                                    <div class="invalid-feedback">
                                                        @error('bus_name')
                                                            {{ $message }}
                                                        @else
                                                            Please Enter Business Name.
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Select NTN / CNIC -->
                                                <div class="col-md-4">
                                                    <label class="form-label required">Select NTN / CNIC</label>
                                                    <select name="id_type" id="id_type"
                                                        class="form-select @error('id_type') is-invalid @enderror" required>
                                                        <option value="NTN"
                                                            {{ old('id_type', 'NTN') === 'NTN' ? 'selected' : '' }}>NTN
                                                        </option>
                                                        <option value="CNIC"
                                                            {{ old('id_type') === 'CNIC' ? 'selected' : '' }}>CNIC</option>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        @error('id_type')
                                                            {{ $message }}
                                                        @else
                                                            Please select ID type.
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- NTN / CNIC Value -->
                                                <div class="col-md-4">
                                                    <label class="form-label required">NTN / CNIC</label>
                                                    <input type="text" name="bus_ntn_cnic" id="bus_ntn_cnic"
                                                        class="form-control @error('bus_ntn_cnic') is-invalid @enderror"
                                                        value="{{ old('bus_ntn_cnic') }}" required
                                                        placeholder="Enter your NTN / CNIC Number">
                                                    <div class="invalid-feedback">
                                                        @error('bus_ntn_cnic')
                                                            {{ $message }}
                                                        @else
                                                            Please Enter Business NTN/CNIC.
                                                        @enderror
                                                    </div>
                                                </div>


                                                <!-- Registration -->
                                                <div class="col-md-4">
                                                    <label class="form-label required">Registration #</label>
                                                    <input type="text" name="bus_reg_num"
                                                        class="form-control @error('bus_reg_num') is-invalid @enderror"
                                                        value="{{ old('bus_reg_num') }}" required
                                                        placeholder="Enter your Registration Number">
                                                    <div class="invalid-feedback">
                                                        @error('bus_reg_num')
                                                            {{ $message }}
                                                        @else
                                                            Please Enter Business Registration.
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Contact Number -->
                                                <div class="col-md-4">
                                                    <label class="form-label required">Contact Number</label>
                                                    <input type="text" name="bus_contact_num"
                                                        class="form-control @error('bus_contact_num') is-invalid @enderror"
                                                        value="{{ old('bus_contact_num') }}" required
                                                        placeholder="Enter Contact Number">
                                                    <div class="invalid-feedback">
                                                        @error('bus_contact_num')
                                                            {{ $message }}
                                                        @else
                                                            Please Enter Contact Number.
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Contact Person -->
                                                <div class="col-md-4">
                                                    <label class="form-label required">Contact Person</label>
                                                    <input type="text" name="bus_contact_person"
                                                        class="form-control @error('bus_contact_person') is-invalid @enderror"
                                                        value="{{ old('bus_contact_person') }}" required
                                                        placeholder="Enter Contact Person">
                                                    <div class="invalid-feedback">
                                                        @error('bus_contact_person')
                                                            {{ $message }}
                                                        @else
                                                            Please Enter Contact Person.
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Province -->
                                                <div class="col-md-4">
                                                    <label for="bus_province" class="form-label required">Province</label>
                                                    <select name="bus_province"
                                                        class="form-select @error('bus_province') is-invalid @enderror"
                                                        required>
                                                        {!! provinceOptions(old('bus_province')) !!}
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        @error('bus_province')
                                                            {{ $message }}
                                                        @else
                                                            Please select Province.
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Logo -->
                                                <div class="col-md-4">
                                                    <label class="form-label required">Company
                                                        Logo</label>
                                                    <input type="file" name="bus_logo"
                                                        class="form-control @error('bus_logo') is-invalid @enderror"
                                                        required>
                                                    <div class="invalid-feedback">
                                                        @error('bus_logo')
                                                            {{ $message }}
                                                        @else
                                                            Please Upload Company Logo.
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Address -->
                                                <div class="col-md-12">
                                                    <label class="form-label required">Business Address</label>
                                                    <textarea name="bus_address" class="form-control @error('bus_address') is-invalid @enderror"
                                                        placeholder="Enter Business Address" rows="3" required>{{ old('bus_address') }}</textarea>
                                                    <div class="invalid-feedback">
                                                        @error('bus_address')
                                                            {{ $message }}
                                                        @else
                                                            Please Enter your Business Address.
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Bank Details -->
                                        <div class="tab-pane fade" id="v-history-tab-pane" role="tabpanel">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label required">Branch Name</label>
                                                    <input type="text" name="bus_acc_branch_name"
                                                        class="form-control @error('bus_acc_branch_name') is-invalid @enderror"
                                                        value="{{ old('bus_acc_branch_name') }}"
                                                        placeholder="Enter your Branch Name" required>
                                                    <div class="invalid-feedback">
                                                        @error('bus_acc_branch_name')
                                                            {{ $message }}
                                                        @else
                                                            Please Enter Branch Name.
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label required">Branch Code</label>
                                                    <input type="text" name="bus_acc_branch_code"
                                                        class="form-control @error('bus_acc_branch_code') is-invalid @enderror"
                                                        value="{{ old('bus_acc_branch_code') }}"
                                                        placeholder="Enter your Branch Code" required>
                                                    <div class="invalid-feedback">
                                                        @error('bus_acc_branch_code')
                                                            {{ $message }}
                                                        @else
                                                            Please Enter Branch Code.
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label required">Account Title</label>
                                                    <input type="text" name="bus_account_title"
                                                        class="form-control @error('bus_account_title') is-invalid @enderror"
                                                        value="{{ old('bus_account_title') }}"
                                                        placeholder="Enter your Account Title" required>
                                                    <div class="invalid-feedback">
                                                        @error('bus_account_title')
                                                            {{ $message }}
                                                        @else
                                                            Please Enter Account Title.
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label required">Account Number</label>
                                                    <input type="text" name="bus_account_number"
                                                        class="form-control @error('bus_account_number') is-invalid @enderror"
                                                        value="{{ old('bus_account_number') }}"
                                                        placeholder="Enter your Account Number" required>

                                                    <div class="invalid-feedback">
                                                        @error('bus_account_number')
                                                            {{ $message }}
                                                        @else
                                                            Please Enter Account Number.
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label required">IBAN</label>
                                                    <input type="text" name="bus_IBAN"
                                                        class="form-control @error('bus_IBAN') is-invalid @enderror"
                                                        value="{{ old('bus_IBAN') }}" placeholder="Enter your IBAN"
                                                        required>
                                                    <div class="invalid-feedback">
                                                        @error('bus_IBAN')
                                                            {{ $message }}
                                                        @else
                                                            Please Enter IBAN.
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label">Swift Code</label>
                                                    <input type="text" name="bus_swift_code"
                                                        class="form-control @error('bus_swift_code') is-invalid @enderror"
                                                        value="{{ old('bus_swift_code') }}"
                                                        placeholder="Enter your Swift Code">
                                                    @error('bus_swift_code')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Config Settings -->
                                        <div class="tab-pane fade" id="v-reviews-tab-pane" role="tabpanel">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label required">FBR Business Scenarios</label>
                                                    <select name="scenario_ids[]" id="scenario_ids"
                                                        class="form-select @error('scenario_ids') is-invalid @enderror"
                                                        multiple required>
                                                        @foreach ($scenarios as $scenario)
                                                            <option value="{{ $scenario->scenario_id }}"
                                                                @if (!empty($selectedScenarios) && in_array($scenario->scenario_id, $selectedScenarios)) selected @endif>
                                                                {{ $scenario->scenario_code }} -
                                                                {{ $scenario->scenario_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        @error('scenario_ids')
                                                            {{ $message }}
                                                        @else
                                                            Please select at least one scenario.
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label required">FBR Environment</label>
                                                    <select name="fbr_env" id="fbr_env"
                                                        class="form-select @error('fbr_env') is-invalid @enderror"
                                                        required>
                                                        <option value="">-- Select Environment --</option>
                                                        <option value="sandbox"
                                                            {{ old('fbr_env') == 'sandbox' ? 'selected' : '' }}>Sandbox
                                                        </option>
                                                        <option value="production"
                                                            {{ old('fbr_env') == 'production' ? 'selected' : '' }}>
                                                            Production</option>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        @error('fbr_env')
                                                            {{ $message }}
                                                        @else
                                                            Please select FBR Environment.
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label id="label_sandbox" class="form-label">FBR API Token
                                                        (Sandbox)</label>
                                                    <textarea id="fbr_api_token_sandbox" name="fbr_api_token_sandbox"
                                                        class="form-control @error('fbr_api_token_sandbox') is-invalid @enderror"
                                                        placeholder="Enter Your FBR API Token (Sandbox)">{{ old('fbr_api_token_sandbox') }}</textarea>
                                                    <div class="invalid-feedback">
                                                        @error('fbr_api_token_sandbox')
                                                            {{ $message }}
                                                        @else
                                                            Please Enter Sandbox API Token.
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label id="label_prod" class="form-label">FBR API Token
                                                        (Production)</label>
                                                    <textarea id="fbr_api_token_prod" name="fbr_api_token_prod"
                                                        class="form-control @error('fbr_api_token_prod') is-invalid @enderror"
                                                        placeholder="Enter Your FBR API Token (Production)">{{ old('fbr_api_token_prod') }}</textarea>
                                                    <div class="invalid-feedback">
                                                        @error('fbr_api_token_prod')
                                                            {{ $message }}
                                                        @else
                                                            Please Enter Production API Token.
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- User Settings  -->
                                        <div class="tab-pane fade" id="v-reviews-tab-pane1" role="tabpanel">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label required">Name</label>
                                                    <input type="text" name="name"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        value="{{ old('name', $user->name ?? '') }}" required
                                                        placeholder="Enter Your Username">
                                                    <div class="invalid-feedback">
                                                        @error('name')
                                                            {{ $message }}
                                                        @else
                                                            Please Enter Name.
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label required">Email</label>
                                                    <input type="email" name="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        value="{{ old('email', $user->email ?? '') }}" required
                                                        placeholder="Enter Your Email">
                                                    <div class="invalid-feedback">
                                                        @error('email')
                                                            {{ $message }}
                                                        @else
                                                            Please Enter Email.
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label required">Password</label>
                                                    <div class="input-group">
                                                        <input type="password" id="password" name="password"
                                                            class="form-control @error('password') is-invalid @enderror"
                                                            placeholder="Enter password" required>
                                                        <span class="input-group-text toggle-password"
                                                            data-target="password" style="cursor:pointer;">
                                                            <i class="bi bi-eye"></i>
                                                        </span>
                                                        <div class="invalid-feedback">
                                                            @error('password')
                                                                {{ $message }}
                                                            @else
                                                                Please Enter Password.
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label required">Confirm Password</label>
                                                    <div class="input-group">
                                                        <input type="password" id="password_confirmation"
                                                            name="password_confirmation" class="form-control"
                                                            placeholder="Confirm password" required>
                                                        <span class="input-group-text toggle-password"
                                                            data-target="password_confirmation" style="cursor:pointer;">
                                                            <i class="bi bi-eye"></i>
                                                        </span>
                                                        <div class="invalid-feedback">
                                                            Passwords do not match.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="mt-4 text-end">
                                                <button type="submit" class="btn btn-primary">Save Configuration</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script nonce="{{ $nonce ?? '' }}">
        document.addEventListener('DOMContentLoaded', function() {
            const envSelect = document.getElementById('fbr_env');
            const sandboxInput = document.getElementById('fbr_api_token_sandbox');
            const prodInput = document.getElementById('fbr_api_token_prod');
            const sandboxLabel = document.getElementById('label_sandbox');
            const prodLabel = document.getElementById('label_prod');

            function toggleRequired() {
                const env = envSelect.value;

                if (env === 'sandbox') {
                    sandboxInput.setAttribute('required', 'required');
                    sandboxLabel.classList.add('required');

                    prodInput.removeAttribute('required');
                    prodLabel.classList.remove('required');
                } else if (env === 'production') {
                    prodInput.setAttribute('required', 'required');
                    prodLabel.classList.add('required');

                    sandboxInput.removeAttribute('required');
                    sandboxLabel.classList.remove('required');
                } else {
                    // none selected
                    sandboxInput.removeAttribute('required');
                    prodInput.removeAttribute('required');
                    sandboxLabel.classList.remove('required');
                    prodLabel.classList.remove('required');
                }
            }

            // Run once on page load (to handle old() values)
            toggleRequired();

            // Re-run on change
            envSelect.addEventListener('change', toggleRequired);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('id_type');
            const input = document.getElementById('bus_ntn_cnic');
            if (!typeSelect || !input) return;

            function applyValidation() {
                const isNTN = typeSelect.value === 'NTN';

                // keep only digits as the user types
                input.value = input.value.replace(/\D/g, '');

                // switch constraints + UI hints
                input.maxLength = isNTN ? 7 : 13;
                input.setAttribute('pattern', isNTN ? '\\d{7}' : '\\d{13}');
                input.title = isNTN ?
                    'NTN must be exactly 7 digits' :
                    'CNIC must be exactly 13 digits (without dashes)';
                input.placeholder = isNTN ? 'Enter 7-digit NTN' : 'Enter 13-digit CNIC';
            }

            // Default to NTN on a fresh load if nothing selected
            if (!typeSelect.value) typeSelect.value = 'NTN';

            typeSelect.addEventListener('change', function() {
                input.value = ""; // clear value on type change
                applyValidation();
            });

            input.addEventListener('input', applyValidation);
            applyValidation();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#scenario_ids').select2({
                placeholder: "Select Scenarios",
                allowClear: true
            });
        });
    </script>

    <script nonce="{{ $nonce ?? '' }}">
        document.addEventListener('DOMContentLoaded', function() {
            const password = document.getElementById('password');
            const confirm = document.getElementById('password_confirmation');

            // ✅ Password match validation
            function validatePasswords() {
                if (confirm.value && password.value !== confirm.value) {
                    confirm.setCustomValidity("Passwords do not match");
                } else {
                    confirm.setCustomValidity("");
                }
            }
            password.addEventListener('input', validatePasswords);
            confirm.addEventListener('input', validatePasswords);

            // ✅ Toggle password visibility
            document.querySelectorAll('.toggle-password').forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                });
            });
        });
    </script>

@endsection
