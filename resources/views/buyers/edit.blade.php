@extends('layouts.admin')
@section('content')
    @include('layouts.partials.errors')
    <div class="container-fluid">
        <h2 class="mb-4 text-center">Edit Client</h2>
        <form class="app-form needs-validation" novalidate method="POST" action="{{ route('buyers.update') }}"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="byr_id"
                value="{{ \Illuminate\Support\Facades\Crypt::encryptString($buyer->byr_id) }}" />
            <div class="card mb-4">
                <div class="card-body row g-3">
                    {{-- Client Name --}}
                    <div class="col-md-6">
                        <label class="form-label required">Client Name</label>
                        <input type="text" name="byr_name" placeholder="Enter Client Name"
                            value="{{ old('byr_name', $buyer->byr_name) }}" class="form-control" required />
                        <div class="invalid-feedback">Please Enter Client Name.</div>
                        @error('byr_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Client Type --}}
                    <div class="col-md-6">
                        <label class="form-label required">Client Type</label>
                        <select name="byr_type" class="form-select @error('byr_type') is-invalid @enderror" required>
                            <option value="">-- Select Client Type --</option>
                            <option value="1" {{ old('byr_type', $buyer->byr_type) == '1' ? 'selected' : '' }}>
                                Registered</option>
                            <option value="0" {{ old('byr_type', $buyer->byr_type) == '0' ? 'selected' : '' }}>
                                Unregistered</option>
                        </select>
                        <div class="invalid-feedback">Please select a Client Type.</div>
                        @error('byr_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Buyer NTN / CNIC --}}
                    <div class="col-md-4">
                        <label class="form-label">Select ID Type</label>
                        <select name="byr_id_type" id="byr_id_type"
                            class="form-select @error('byr_id_type') is-invalid @enderror">
                            <option value="">-- Select ID Type --</option>
                            <option value="NTN"
                                {{ old('byr_id_type', strlen($buyer->byr_ntn_cnic) == 7 ? 'NTN' : '') == 'NTN' ? 'selected' : '' }}>
                                NTN
                            </option>
                            <option value="CNIC"
                                {{ old('byr_id_type', strlen($buyer->byr_ntn_cnic) == 13 ? 'CNIC' : '') == 'CNIC' ? 'selected' : '' }}>
                                CNIC
                            </option>
                        </select>
                        @error('byr_id_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Client NTN / CNIC</label>
                        <input type="text" name="byr_ntn_cnic" id="byr_ntn_cnic" placeholder="Enter NTN/CNIC"
                            value="{{ old('byr_ntn_cnic', $buyer->byr_ntn_cnic) }}"
                            class="form-control @error('byr_ntn_cnic') is-invalid @enderror" />
                        <div class="invalid-feedback">Please Enter VALID NTN/CNIC.</div>
                        @error('byr_ntn_cnic')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="byr_province" class="form-label required">Province</label>
                        <select name="byr_province" id="byr_province"
                            class="form-select @error('byr_province') is-invalid @enderror" required>
                            {!! provinceOptions(old('byr_province', $buyer->byr_province ?? '')) !!}
                        </select>
                        @error('byr_province')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Address --}}
                    <div class="col-md-12">
                        <label class="form-label required">Address</label>
                        <textarea name="byr_address" class="form-control" rows="3" placeholder="Enter Address" required>{{ old('byr_address', $buyer->byr_address) }}</textarea>
                        <div class="invalid-feedback">Please Enter Address.</div>
                        @error('byr_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Account Title </label>
                        <input type="text" name="byr_account_number" placeholder="Enter Account Title"
                            value="{{ old('byr_account_number', $buyer->byr_account_number) }}" class="form-control" />
                        <div class="invalid-feedback">Please Enter Account Title.</div>
                        @error('byr_account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Account Number</label>
                        <input type="text" name="byr_account_title" placeholder="Enter Account Number"
                            value="{{ old('byr_account_title', $buyer->byr_account_title) }}" class="form-control" />
                        <div class="invalid-feedback">Please Enter Account Number.</div>
                        @error('byr_account_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Registration Number</label>
                        <input type="text" name="byr_reg_num" placeholder="Enter Registration Number"
                            class="form-control" value="{{ old('byr_reg_num', $buyer->byr_reg_num) }}" />
                        <div class="invalid-feedback">Please Enter Registration Number.</div>
                        @error('byr_reg_num')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Contact --}}
                    <div class="col-md-4">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="byr_contact_num" placeholder="Enter Contact Number" class="form-control"
                            value="{{ old('byr_contact_num', $buyer->byr_contact_num) }}" />
                        <div class="invalid-feedback">Please Enter Contact Number.</div>
                        @error('byr_contact_num')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Contact Person</label>
                        <input type="text" name="byr_contact_person" placeholder="Enter Contact Person"
                            value="{{ old('byr_contact_person', $buyer->byr_contact_person) }}" class="form-control" />
                        <div class="invalid-feedback">Please Enter Contact Person.</div>
                        @error('byr_contact_person')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">IBAN</label>
                        <input type="text" name="byr_IBAN" placeholder="Enter IBAN" class="form-control"
                            value="{{ old('byr_IBAN', $buyer->byr_IBAN) }}" />
                        <div class="invalid-feedback">Please Enter IBAN.</div>
                        @error('byr_IBAN')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Branch Name</label>
                        <input type="text" name="byr_acc_branch_name" placeholder="Enter Branch Name"
                            value="{{ old('byr_acc_branch_name', $buyer->byr_acc_branch_name) }}" class="form-control" />
                        <div class="invalid-feedback">Please Enter Branch Name.</div>
                        @error('byr_acc_branch_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Branch Code</label>
                        <input type="text" name="byr_acc_branch_code" placeholder="Enter Branch Code"
                            value="{{ old('byr_acc_branch_code', $buyer->byr_acc_branch_code) }}" class="form-control" />
                        <div class="invalid-feedback">Please Enter Branch Code.</div>
                        @error('byr_acc_branch_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Swift Code</label>
                        <input type="text" name="byr_swift_code" placeholder="Enter SWIFT Code"
                            value="{{ old('byr_swift_code', $buyer->byr_swift_code) }}" class="form-control" />
                    </div>
                    {{-- Logo --}}
                    {{-- <div class="col-md-4">
                        <label class="form-label">Client Logo</label>
                        <input type="file" name="byr_logo" class="form-control" />
                        @if ($buyer->byr_logo)
                            <div class="mt-3">
                                @php
                                    $disk = env('FILESYSTEM_DISK', config('filesystems.default', 'uploads'));
                                    $logoUrl = Storage::disk($disk)->url($buyer->byr_logo);
                                @endphp

                                <img src="{{ $logoUrl }}" alt="Company Logo"
                                    style="max-width: 200px; height: auto; border: 1px solid #ddd;
                   padding: 5px; border-radius: 8px;
                   box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                            </div>
                        @endif

                        @error('byr_logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div> --}}
                    <div class="col-md-4">
                        <label class="form-label">Client Logo</label>
                        <input type="file" name="byr_logo" class="form-control" />

                        @if (!empty($buyer->byr_logo))
                            <div class="mt-3">
                                @php
                                    $disk = env('FILESYSTEM_DISK', config('filesystems.default', 'uploads'));
                                    $logoUrl = null;

                                    try {
                                        if ($disk === 's3') {
                                            // Generate signed URL (valid 1 hour) for private buckets
                                            $logoUrl = Storage::disk($disk)->temporaryUrl(
                                                $buyer->byr_logo,
                                                now()->addHour(),
                                            );
                                        } else {
                                            // Public/local disks
                                            $logoUrl = Storage::disk($disk)->url($buyer->byr_logo);
                                        }
                                    } catch (\Throwable $e) {
                                        $logoUrl = null;
                                    }
                                @endphp

                                @if ($logoUrl)
                                    <img src="{{ $logoUrl }}" alt="Client Logo"
                                        style="max-width: 200px; height: auto; border: 1px solid #ddd;
                           padding: 5px; border-radius: 8px;
                           box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                                @else
                                    <p class="text-danger">⚠️ Unable to load logo from storage</p>
                                @endif
                            </div>
                        @else
                            <p class="text-muted mt-2">No client logo uploaded</p>
                        @endif

                        @error('byr_logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="col-md-12">
                        <div class="mb-3 col-2 mt-3 ms-auto text-end">
                            <button type="submit" role="button" class="btn btn-primary w-100">
                                Update Client
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @push('scripts')
        <script nonce="{{ $nonce }}">
            document.addEventListener('DOMContentLoaded', function() {
                const buyerTypeSelect = document.getElementById('byr_id_type');
                const buyerInput = document.getElementById('byr_ntn_cnic');
                if (!buyerTypeSelect || !buyerInput) return;

                function applyBuyerValidation() {
                    const val = buyerInput.value.trim();
                    const isNTN = buyerTypeSelect.value === 'NTN';
                    const patternNTN = /^[0-9]{7}$/;
                    const patternCNIC = /^[0-9]{13}$/;
                    // Clear if value does not match expected pattern
                    if ((isNTN && val && !patternNTN.test(val)) ||
                        (!isNTN && val && !patternCNIC.test(val))) {
                        buyerInput.value = "";
                    }
                    if (isNTN) {
                        buyerInput.setAttribute('pattern', '^[0-9]{7}$');
                        buyerInput.setAttribute('minlength', '7');
                        buyerInput.setAttribute('maxlength', '7');
                        buyerInput.setAttribute('title', 'NTN must be exactly 7 digits');
                        buyerInput.placeholder = 'Enter 7-digit NTN';
                    } else {
                        buyerInput.setAttribute('pattern', '^[0-9]{13}$');
                        buyerInput.setAttribute('minlength', '13');
                        buyerInput.setAttribute('maxlength', '13');
                        buyerInput.setAttribute('title', 'CNIC must be exactly 13 digits (without dashes)');
                        buyerInput.placeholder = 'Enter 13-digit CNIC';
                    }
                }
                // 🔹 Force numeric input only
                buyerInput.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '');
                });
                buyerTypeSelect.addEventListener('change', applyBuyerValidation);
                applyBuyerValidation(); // run on page load
            });
        </script>
        <script nonce="{{ $nonce }}">
            document.addEventListener('DOMContentLoaded', function() {
                const clientTypeSelect = document.querySelector('select[name="byr_type"]');
                const ntcInput = document.getElementById('byr_ntn_cnic');
                const label = ntcInput.previousElementSibling;
                const byr_id_typeInput = document.getElementById('byr_id_type');
                const byr_id_typelabel = byr_id_typeInput.previousElementSibling;

                function toggleRequired() {
                    const selectedValue = clientTypeSelect.value;
                    if (selectedValue === '1') { // Registered
                        ntcInput.setAttribute('required', 'required');
                        label.classList.add('required');
                        byr_id_typeInput.setAttribute('required', 'required');
                        byr_id_typelabel.classList.add('required');
                    } else {
                        ntcInput.removeAttribute('required');
                        label.classList.remove('required');
                        byr_id_typeInput.removeAttribute('required');
                        byr_id_typelabel.classList.remove('required');
                    }
                }
                toggleRequired(); // run on page load in case old value present
                clientTypeSelect.addEventListener('change', toggleRequired);
            });
        </script>
    @endpush
@endsection
