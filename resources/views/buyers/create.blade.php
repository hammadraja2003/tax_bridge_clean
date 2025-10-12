@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <h2 class="mb-4 text-center">Add New Client</h2>
        <form class="app-form needs-validation" novalidate action="{{ route('buyers.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="card mb-4">
                <div class="card-body row g-3">
                    {{-- Client Name --}}
                    <div class="col-md-6">
                        <label for="byr_name" class="form-label required">Client Name</label>
                        <input type="text" name="byr_name" id="byr_name" placeholder="Enter Client Name" value="{{ old('byr_name') }}"
                            class="form-control @error('byr_name') is-invalid @enderror" required>
                        @error('byr_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Client Type --}}
                    <div class="col-md-6">
                        <label for="byr_type" class="form-label required">Client Type</label>
                        <select name="byr_type" id="byr_type" class="form-select @error('byr_type') is-invalid @enderror"
                            required>
                            <option value="">-- Select Client Type --</option>
                            <option value="1" {{ old('byr_type') == '1' ? 'selected' : '' }}>Registered</option>
                            <option value="0" {{ old('byr_type') == '0' ? 'selected' : '' }}>Unregistered</option>
                        </select>
                        @error('byr_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Client ID Type --}}
                    <div class="col-md-4">
                        <label for="byr_id_type" class="form-label">Select ID Type</label>
                        <select name="byr_id_type" id="byr_id_type"
                            class="form-select @error('byr_id_type') is-invalid @enderror">
                            <option value="">-- Select ID Type --</option>
                            <option value="NTN" {{ old('byr_id_type') == 'NTN' ? 'selected' : '' }}>NTN</option>
                            <option value="CNIC" {{ old('byr_id_type') == 'CNIC' ? 'selected' : '' }}>CNIC</option>
                        </select>
                        @error('byr_id_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Client NTN/CNIC --}}
                    <div class="col-md-4">
                        <label for="byr_ntn_cnic" class="form-label">Client NTN / CNIC</label>
                        <input type="text" name="byr_ntn_cnic" id="byr_ntn_cnic" placeholder="Enter NTN/CNIC" value="{{ old('byr_ntn_cnic') }}"
                            class="form-control @error('byr_ntn_cnic') is-invalid @enderror">
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
                    {{-- Client Address --}}
                    <div class="col-md-12">
                        <label for="byr_address" class="form-label required">Client Address</label>
                        <textarea name="byr_address" id="byr_address" placeholder="Enter Address" class="form-control @error('byr_address') is-invalid @enderror" required
                            rows="3">{{ old('byr_address') }}</textarea>
                        @error('byr_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Account Title --}}
                    <div class="col-md-4">
                        <label for="byr_account_title" class="form-label">Account Title</label>
                        <input type="text" name="byr_account_title" placeholder="Enter Account Title" id="byr_account_title"
                            value="{{ old('byr_account_title') }}"
                            class="form-control @error('byr_account_title') is-invalid @enderror">
                        @error('byr_account_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Account Number --}}
                    <div class="col-md-4">
                        <label for="byr_account_number" class="form-label">Account Number</label>
                        <input type="text" name="byr_account_number" placeholder="Enter Account Number" id="byr_account_number"
                            value="{{ old('byr_account_number') }}"
                            class="form-control @error('byr_account_number') is-invalid @enderror">
                        @error('byr_account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Registration Number --}}
                    <div class="col-md-4">
                        <label for="byr_reg_num" class="form-label">Registration Number</label>
                        <input type="text" name="byr_reg_num" placeholder="Enter Registration Number" id="byr_reg_num" value="{{ old('byr_reg_num') }}"
                            class="form-control @error('byr_reg_num') is-invalid @enderror">
                        @error('byr_reg_num')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Contact Number --}}
                    <div class="col-md-4">
                        <label for="byr_contact_num" class="form-label">Contact Number</label>
                        <input type="text" name="byr_contact_num" placeholder="Enter Contact Number" id="byr_contact_num"
                            value="{{ old('byr_contact_num') }}"
                            class="form-control @error('byr_contact_num') is-invalid @enderror">
                        @error('byr_contact_num')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Contact Person --}}
                    <div class="col-md-4">
                        <label for="byr_contact_person" class="form-label">Contact Person</label>
                        <input type="text" name="byr_contact_person" placeholder="Enter Contact Person" id="byr_contact_person"
                            value="{{ old('byr_contact_person') }}"
                            class="form-control @error('byr_contact_person') is-invalid @enderror">
                        @error('byr_contact_person')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- IBAN --}}
                    <div class="col-md-4">
                        <label for="byr_IBAN" class="form-label">IBAN</label>
                        <input type="text" name="byr_IBAN" placeholder="Enter IBAN" id="byr_IBAN" value="{{ old('byr_IBAN') }}"
                            class="form-control @error('byr_IBAN') is-invalid @enderror">
                        @error('byr_IBAN')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Branch Name --}}
                    <div class="col-md-4">
                        <label for="byr_acc_branch_name" class="form-label">Branch Name</label>
                        <input type="text" name="byr_acc_branch_name" placeholder="Enter Branch name" id="byr_acc_branch_name"
                            value="{{ old('byr_acc_branch_name') }}"
                            class="form-control @error('byr_acc_branch_name') is-invalid @enderror">
                        @error('byr_acc_branch_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Branch Code --}}
                    <div class="col-md-4">
                        <label for="byr_acc_branch_code" class="form-label">Branch Code</label>
                        <input type="text" name="byr_acc_branch_code" placeholder="Enter Branch Code" id="byr_acc_branch_code"
                            value="{{ old('byr_acc_branch_code') }}"
                            class="form-control @error('byr_acc_branch_code') is-invalid @enderror">
                        @error('byr_acc_branch_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Swift Code --}}
                    <div class="col-md-4">
                        <label for="byr_swift_code" class="form-label">Swift Code</label>
                        <input type="text" name="byr_swift_code" placeholder="Enter SWIFT Code" id="byr_swift_code"
                            value="{{ old('byr_swift_code') }}"
                            class="form-control @error('byr_swift_code') is-invalid @enderror">
                        @error('byr_swift_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Buyer Logo --}}
                    <div class="col-md-4">
                        <label for="byr_logo" class="form-label">Buyer Logo</label>
                        <input type="file" name="byr_logo" id="byr_logo"
                            class="form-control @error('byr_logo') is-invalid @enderror"
                            accept=".jpg,.jpeg,.png,.svg">
                        @error('byr_logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Submit Button --}}
                    <div class="col-md-12 ">
                        <div class="mb-3 col-2 mt-3 ms-auto text-end">
                            <button type="submit" role="button" class="btn btn-primary w-100">
                                Save Client
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
                if (buyerTypeSelect && buyerInput) {
                    function applyBuyerValidation() {
                        const isNTN = buyerTypeSelect.value === 'NTN';
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
                        // Clear value when changing type
                        buyerInput.value = "";
                    }
                    // ✅ Prevent non-digit input
                    buyerInput.addEventListener('input', function(e) {
                        this.value = this.value.replace(/\D/g, ""); // only digits
                    });
                    buyerTypeSelect.addEventListener('change', applyBuyerValidation);
                    applyBuyerValidation();
                }
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
