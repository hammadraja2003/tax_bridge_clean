@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <h2 class="mb-4 text-center">Configuration</h2>
        {{-- Tampering check notice (at page end) --}}
        <div class="mt-4 text-end">
            @if ($config && $config->tampered)
                <span class="text-danger fw-bold">⚠ Data tampered!</span>
            @endif
        </div>
        {{-- Show success/error messages --}}
        @if ($errors->has('db_error'))
            <div class="alert alert-danger">
                {{ $errors->first('db_error') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form class="app-form needs-validation" novalidate method="POST" action="{{ route('company.configuration.save') }}"
            enctype="multipart/form-data">
            @csrf
            <div class="card mb-4">
                <div class="card-body row g-3">
                    <div class="col-12 mt-4">
                        <h5 class="fw-bold border-bottom pb-2 mb-3">Company Details</h5>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label required">Name</label>
                            <input type="text" name="bus_name" placeholder="Enter a Buissness Name" class="form-control"
                                required value="{{ old('bus_name', $config->bus_name ?? '') }}">
                            <div class="invalid-feedback">
                                Please Enter Business Configuration.
                            </div>
                            @error('bus_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Select NTN / CNIC</label>
                            <select name="id_type" id="id_type" class="form-select" required>
                                <option value="NTN"
                                    {{ (old('id_type') ?? (isset($config) && strlen($config->bus_ntn_cnic) == 7 ? 'NTN' : 'CNIC')) == 'NTN' ? 'selected' : '' }}>
                                    NTN
                                </option>
                                <option value="CNIC"
                                    {{ (old('id_type') ?? (isset($config) && strlen($config->bus_ntn_cnic) == 13 ? 'CNIC' : 'NTN')) == 'CNIC' ? 'selected' : '' }}>
                                    CNIC
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">NTN / CNIC</label>
                            <input type="text" name="bus_ntn_cnic" id="bus_ntn_cnic" placeholder="Enter a NTN / CNIC"
                                class="form-control" required
                                value="{{ old('bus_ntn_cnic', $config->bus_ntn_cnic ?? '') }}">
                            <div class="invalid-feedback">Please Enter Business NTN/CNIC.</div>
                            @error('bus_ntn_cnic')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Registration #</label>
                            <input type="text" name="bus_reg_num" placeholder="Enter a Registration #"
                                class="form-control" required value="{{ old('bus_reg_num', $config->bus_reg_num ?? '') }}">
                            <div class="invalid-feedback">Please Enter Business Registration.</div>
                            @error('bus_reg_num')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Contact Number</label>
                            <input type="text" name="bus_contact_num" placeholder="Enter a Contact Number"
                                class="form-control" value="{{ old('bus_contact_num', $config->bus_contact_num ?? '') }}"
                                required>
                            <div class="invalid-feedback">
                                Please Enter Contact Number.
                            </div>
                            @error('bus_contact_num')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Contact Person</label>
                            <input type="text" name="bus_contact_person" class="form-control"
                                placeholder="Enter a Contact Person"
                                value="{{ old('bus_contact_person', $config->bus_contact_person ?? '') }}" required>
                            <div class="invalid-feedback">
                                Please Enter Contact Person.
                            </div>
                            @error('bus_contact_person')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="bus_province" class="form-label required">Province</label>
                            <select name="bus_province" id="bus_province"
                                class="form-select @error('bus_province') is-invalid @enderror" required>
                                {!! provinceOptions(old('bus_province', $config->bus_province ?? '')) !!}
                            </select>
                            <div class="invalid-feedback">Please select Province.</div>
                            @error('bus_province')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label {{ empty($config->bus_logo) ? 'required' : '' }}">Company
                                Logo</label>
                            <input type="file" name="bus_logo" class="form-control"
                                {{ empty($config->bus_logo) ? 'required' : '' }}>
                            <div class="invalid-feedback">
                                Please Enter Company Logo.
                            </div>
                            @error('bus_logo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        @if (!empty($config->bus_logo))
                            <div class="col-md-4">
                                <label class="form-label">Company Logo</label>
                                @if (!empty($config->bus_logo))
                                    @php
                                        $disk = env('FILESYSTEM_DISK', config('filesystems.default', 'uploads'));
                                        $url = null;

                                        try {
                                            if ($disk === 's3') {
                                                // Generate temporary signed URL (valid 1 hour)
                                                $url = Storage::disk($disk)->temporaryUrl(
                                                    $config->bus_logo,
                                                    now()->addHour(),
                                                );
                                            } else {
                                                // Local or public disks
                                                $url = Storage::disk($disk)->url($config->bus_logo);
                                            }
                                        } catch (\Throwable $e) {
                                            $url = null;
                                        }
                                    @endphp

                                    @if ($url)
                                        <img src="{{ $url }}" alt="Company Logo"
                                            style="max-width: 200px; height: auto; border: 1px solid #ddd; padding: 5px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                                    @else
                                        <p class="text-danger">⚠️ Unable to load logo from storage</p>
                                    @endif
                                @else
                                    <p class="text-muted">No logo uploaded</p>
                                @endif
                            </div>


                        @endif
                        <div class="col-md-12">
                            <label class="form-label required">Address</label>
                            <textarea name="bus_address" class="form-control" placeholder="Enter a Addres" rows="3" required>{{ old('bus_address', $config->bus_address ?? '') }}</textarea>
                            <div class="invalid-feedback">
                                Please Enter Business Address.
                            </div>
                            @error('bus_address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <h5 class="fw-bold border-bottom pb-2 mb-3">Bank Details</h5>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label required">Branch Name</label>
                            <input type="text" name="bus_acc_branch_name" placeholder="Enter a Branch Name"
                                class="form-control"
                                value="{{ old('bus_acc_branch_name', $config->bus_acc_branch_name ?? '') }}" required>
                            <div class="invalid-feedback">
                                Please Enter Branch Name.
                            </div>
                            @error('bus_acc_branch_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Branch Code</label>
                            <input type="text" name="bus_acc_branch_code" placeholder="Enter a Branch Code"
                                class="form-control"
                                value="{{ old('bus_acc_branch_code', $config->bus_acc_branch_code ?? '') }}" required>
                            <div class="invalid-feedback">
                                Please Enter Branch Code.
                            </div>
                            @error('bus_acc_branch_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Account Title</label>
                            <input type="text" name="bus_account_title" class="form-control"
                                placeholder="Enter a Account Title"
                                value="{{ old('bus_account_title', $config->bus_account_title ?? '') }}" required>
                            <div class="invalid-feedback">
                                Please Enter Account Title.
                            </div>
                            @error('bus_account_title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Account Number</label>
                            <input type="text" name="bus_account_number" class="form-control"
                                placeholder="Enter a Account Number"
                                value="{{ old('bus_account_number', $config->bus_account_number ?? '') }}" required>
                            <div class="invalid-feedback">
                                Please Enter Account Number.
                            </div>
                            @error('bus_account_number')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">IBAN</label>
                            <input type="text" name="bus_IBAN" class="form-control" placeholder="Enter a IBAN"
                                value="{{ old('bus_IBAN', $config->bus_IBAN ?? '') }}" required>
                            <div class="invalid-feedback">
                                Please Enter IBAN.
                            </div>
                            @error('bus_IBAN')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Swift Code</label>
                            <input type="text" name="bus_swift_code" placeholder="Enter a SWIFT Code"
                                class="form-control" value="{{ old('bus_swift_code', $config->bus_swift_code ?? '') }}">
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <h5 class="fw-bold border-bottom pb-2 mb-3">Configuration Settings</h5>
                    </div>
                    <div class="row g-3">
                        {{-- <div class="col-md-4">
                            <label class="form-label required">Database Host</label>
                            <input type="text" name="db_host" class="form-control" placeholder="Enter DB Host"
                                value="{{ old('db_host', $config->db_host ?? '') }}" required>
                            <div class="invalid-feedback">Please enter DB Host.</div>
                            @error('db_host')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Database Name</label>
                            <input type="text" name="db_name" class="form-control" placeholder="Enter DB Name"
                                value="{{ old('db_name', $config->db_name ?? '') }}" required>
                            <div class="invalid-feedback">Please enter DB Name.</div>
                            @error('db_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Database Username</label>
                            <input type="text" name="db_username" class="form-control"
                                placeholder="Enter DB Username"
                                value="{{ old('db_username', $config->db_username ?? '') }}" required>
                            <div class="invalid-feedback">Please enter DB Username.</div>
                            @error('db_username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Database Password</label>
                            <input type="password" name="db_password" class="form-control"
                                placeholder="Enter DB Password"
                                value="{{ old('db_password', $config->db_password ?? '') }}" required>
                            <div class="invalid-feedback">Please enter DB Password.</div>
                            @error('db_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}
                        <div class="col-md-4">
                            <label class="form-label required">FBR Environment</label>
                            <select name="fbr_env" class="form-select" required>
                                <option value="">-- Select Environment --</option>
                                <option value="sandbox"
                                    {{ old('fbr_env', $config->fbr_env ?? '') == 'sandbox' ? 'selected' : '' }}>Sandbox
                                </option>
                                <option value="production"
                                    {{ old('fbr_env', $config->fbr_env ?? '') == 'production' ? 'selected' : '' }}>
                                    Production
                                </option>
                            </select>
                            <div class="invalid-feedback">Please select FBR Environment.</div>
                            @error('fbr_env')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">FBR API Token (Sandbox)</label>
                            <textarea name="fbr_api_token_sandbox" class="form-control" placeholder="Paste Sandbox API Token" rows="2">{{ old('fbr_api_token_sandbox', $config->fbr_api_token_sandbox ?? '') }}</textarea>
                            @error('fbr_api_token_sandbox')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">FBR API Token (Production)</label>
                            <textarea name="fbr_api_token_prod" class="form-control" placeholder="Paste Production API Token" rows="2">{{ old('fbr_api_token_prod', $config->fbr_api_token_prod ?? '') }}</textarea>
                            @error('fbr_api_token_prod')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Scenarios</label>
                            <select name="scenario_ids[]" id="scenario_ids" class="form-select" multiple required>
                                @foreach ($scenarios as $scenario)
                                    <option value="{{ $scenario->scenario_id }}"
                                        @if (!empty($selectedScenarios) && in_array($scenario->scenario_id, $selectedScenarios)) selected @endif>
                                        {{ $scenario->scenario_code }} - {{ $scenario->scenario_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Save Configuration</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @push('scripts')
        <script nonce="{{ $nonce }}">
            document.addEventListener('DOMContentLoaded', function() {
                const typeSelect = document.getElementById('id_type');
                const input = document.getElementById('bus_ntn_cnic');
                if (!typeSelect || !input) return; // Prevent error if elements don't exist
                function applyValidation() {
                    const val = input.value.trim();
                    const isNTN = typeSelect.value === 'NTN';
                    // Define regex patterns
                    const patternNTN = /^[0-9]{7}$/;
                    const patternCNIC = /^[0-9]{13}$/;
                    // Clear input only if current value does NOT match the new pattern
                    if ((isNTN && !patternNTN.test(val)) || (!isNTN && !patternCNIC.test(val))) {
                        input.value = "";
                    }
                    // Apply attributes
                    if (isNTN) {
                        input.setAttribute('pattern', '[0-9]{7}');
                        input.setAttribute('maxlength', '7');
                        input.setAttribute('title', 'NTN must be exactly 7 digits');
                        input.placeholder = 'Enter 7-digit NTN';
                    } else {
                        input.setAttribute('pattern', '[0-9]{13}');
                        input.setAttribute('maxlength', '13');
                        input.setAttribute('title', 'CNIC must be exactly 13 digits (without dashes)');
                        input.placeholder = 'Enter 13-digit CNIC';
                    }
                }
                typeSelect.addEventListener('change', applyValidation);
                applyValidation();
            });
        </script>
        <script nonce="{{ $nonce }}">
            $(document).ready(function() {
                $('#scenario_ids').select2();
            });
        </script>
    @endpush
@endsection
