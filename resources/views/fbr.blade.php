@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="row table_setting">
            <div class="container-fluid">
                <div class="row table_setting">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">FBR Lookups</h5>
                            </div>

                            <div class="card-body p-0">
                                {{-- Filter Section --}}
                                <div class="p-3 border-bottom">
                                    <form id="lookupForm" class="row g-3 align-items-end">
                                        {{-- Lookup Type --}}
                                        <div class="col-md-3">
                                            <label for="lookupType" class="form-label">Select Lookup Type</label>
                                            <select id="lookupType" class="form-select">
                                                <option value="">-- Select --</option>
                                                <option value="provinces">Provinces</option>
                                                <option value="items">Item Description Codes</option>
                                                <option value="uom">Units of Measure</option>
                                                <option value="doctype">Document Types</option>
                                                <option value="sroitem">SRO Item Codes</option>
                                                <option value="transtype">Transaction Types</option>
                                                <option value="sroschedule">SRO Schedule</option>
                                                <option value="saletypetorate">Sale Type to Rate</option>
                                                <option value="hsuom">HS Code with UOM</option>
                                                <option value="sroitemdetail">SRO Item (with Date & ID)</option>
                                                <option value="statl">STATL – Registration Status</option>
                                                <option value="regtype">STATL – Registration Type</option>
                                            </select>
                                        </div>

                                        {{-- Dynamic Params --}}
                                        <div id="paramFields" class="row g-3 col-md-9"></div>

                                        {{-- Fetch Button --}}
                                        <div class="col-md-12 text-end">
                                            <button id="fetchBtn" type="button" class="btn btn-primary">
                                                <i class="fa-solid fa-database me-2"></i> Fetch
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                {{-- Results Section --}}
                                <div id="myTable">
                                    <div class="list-table-header d-flex justify-content-between align-items-center p-3">
                                        <h6 class="mb-0 text-muted">Fetched Results</h6>
                                        <form class="app-form app-icon-form">
                                            <div class="position-relative">
                                                <input type="search" class="form-control search"
                                                    placeholder="Search Results...">
                                            </div>
                                        </form>
                                    </div>

                                    <div class="app-scroll overflow-auto">
                                        <div id="results" class="p-3">
                                            <p class="text-muted">Please select a lookup type and fetch data.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('scripts')
    <script nonce="{{ $nonce ?? '' }}">
        document.addEventListener('DOMContentLoaded', function() {
            const lookupType = document.getElementById('lookupType');
            const paramFields = document.getElementById('paramFields');
            const fetchBtn = document.getElementById('fetchBtn');
            const resultsDiv = document.getElementById('results');

            lookupType.addEventListener('change', function() {
                const type = this.value;
                paramFields.innerHTML = '';

                switch (type) {
                    case 'sroschedule':
                        paramFields.innerHTML = `
                    <div class="col-md-4">
                        <label class="form-label">Rate ID</label>
                        <input type="text" id="rate_id" class="form-control" placeholder="Enter Rate ID" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date</label>
                        <input type="date" id="date" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Origination Supplier CSV</label>
                        <input type="text" id="origination_supplier_csv" class="form-control" placeholder="Supplier CSV" required>
                    </div>`;
                        break;

                    case 'saletypetorate':
                        paramFields.innerHTML = `
                    <div class="col-md-4">
                        <label class="form-label">Date</label>
                        <input type="date" id="date" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Transaction Type ID</label>
                        <input type="text" id="transTypeId" class="form-control" placeholder="Enter Transaction Type ID" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Origination Supplier</label>
                        <input type="text" id="originationSupplier" class="form-control" placeholder="Supplier Code" required>
                    </div>`;
                        break;

                    case 'hsuom':
                        paramFields.innerHTML = `
                    <div class="col-md-6">
                        <label class="form-label">HS Code</label>
                        <input type="text" id="hs_code" class="form-control" placeholder="Enter HS Code" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Annexure ID</label>
                        <input type="text" id="annexure_id" class="form-control" placeholder="Enter Annexure ID" required>
                    </div>`;
                        break;

                    case 'sroitemdetail':
                        paramFields.innerHTML = `
                    <div class="col-md-6">
                        <label class="form-label">Date</label>
                        <input type="date" id="date" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">SRO ID</label>
                        <input type="text" id="sro_id" class="form-control" placeholder="Enter SRO ID" required>
                    </div>`;
                        break;

                    case 'statl':
                        paramFields.innerHTML = `
                    <div class="col-md-6">
                        <label class="form-label">Registration No</label>
                        <input type="text" id="regno" class="form-control" placeholder="Enter Registration No" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date</label>
                        <input type="date" id="date" class="form-control" required>
                    </div>`;
                        break;

                    case 'regtype':
                        paramFields.innerHTML = `
                    <div class="col-md-6">
                        <label class="form-label">Registration No</label>
                        <input type="text" id="Registration_No" class="form-control" placeholder="Enter Registration No" required>
                    </div>`;
                        break;
                }
            });

            fetchBtn.addEventListener('click', function() {
                const type = lookupType.value;
                if (!type) {
                    alert("Please select a lookup type first");
                    return;
                }

                const inputs = paramFields.querySelectorAll('input');
                let params = {};
                inputs.forEach(input => {
                    if (input.value) params[input.id] = input.type === "date" ? input.value : input
                        .value.trim();
                });

                resultsDiv.innerHTML = "<p>Loading...</p>";

                fetch("{{ route('fbr.fetch') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            type,
                            params
                        })
                    })
                    .then(response => response.json())
                    .then(res => {
                        if (res.success) {
                            let html = `
                    <table class="table table-striped table-bordered m-0">
                        <thead><tr><th>#</th><th>Data</th></tr></thead>
                        <tbody>`;
                            if (Array.isArray(res.data)) {
                                res.data.forEach((item, i) => {
                                    html +=
                                        `<tr><td>${i+1}</td><td><pre class="mb-0 text-wrap">${JSON.stringify(item, null, 2)}</pre></td></tr>`;
                                });
                            } else {
                                html +=
                                    `<tr><td>1</td><td><pre class="mb-0 text-wrap">${JSON.stringify(res.data, null, 2)}</pre></td></tr>`;
                            }
                            html += `</tbody></table>`;
                            resultsDiv.innerHTML = html;
                        } else {
                            resultsDiv.innerHTML = `<p class="text-danger">Error: ${res.error}</p>`;
                        }
                    })
                    .catch(() => {
                        resultsDiv.innerHTML = "<p class='text-danger'>Request failed</p>";
                    });
            });
        });
    </script>
@endpush
