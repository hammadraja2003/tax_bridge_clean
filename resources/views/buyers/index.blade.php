@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="row table_setting">
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Client Details</h5>
                    </div>
                    <div class="card-body p-0">
                        {{-- Filter Section --}}
                        <div class="p-3 border-bottom">
                            <form method="GET" action="{{ route('buyers.index') }}" class="row g-3 align-items-end">
                                {{-- Buyer Type --}}
                                <div class="col-md-3">
                                    <label for="byr_type" class="form-label">Client Type</label>
                                    <select name="byr_type" id="byr_type" class="form-select">
                                        <option value="">All Types</option>
                                        <option value="1" {{ request('byr_type') === '1' ? 'selected' : '' }}>
                                            Registered
                                        </option>
                                        <option value="0" {{ request('byr_type') === '0' ? 'selected' : '' }}>
                                            Unregistered
                                        </option>
                                    </select>
                                </div>
                                {{-- Search --}}
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" name="search" id="search" class="form-control"
                                        placeholder="Name, CNIC, Address..." value="{{ request('search') }}">
                                </div>
                                {{-- Buttons --}}
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    @if (request()->filled('byr_type') || request()->filled('search'))
                                        <a href="{{ route('buyers.index') }}" class="btn btn-outline-secondary">Clear</a>
                                    @endif
                                </div>
                            </form>
                        </div>
                        {{-- Buyer Table --}}
                        <div id="myTable">
                            <div class="list-table-header d-flex justify-content-between align-items-center p-3">
                                <a href="{{ route('buyer.create') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-plus fa-fw"></i>Add New Client
                                </a>
                                <form class="app-form app-icon-form" action="#">
                                    <div class="position-relative">
                                        <input type="search" class="form-control search" placeholder="Search..."
                                            aria-label="Search">
                                    </div>
                                </form>
                            </div>
                            <div class="app-scroll overflow-auto">
                                <table id="buyersTable" class="table table-striped table-bordered m-0 align-middle">
                                    <thead>
                                        <tr class="app-sort">
                                            <th class="w-50">Logo</th>
                                            <th>Name</th>
                                            <th class="w-50">Type</th>
                                            <th class="extra-column">Client Details</th>
                                            <th class="extra-column">Bank Details</th>
                                            <th class="extra-column">Tempered</th>
                                            <th class="w-50">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="buyersData">
                                        @forelse($buyers as $buyer)
                                            <tr @if ($buyer->tampered) class="table-warning" @endif>
                                                <td>
                                                    @if ($buyer->byr_logo)
                                                        @php
                                                            $url = dynamicTemporaryUrl(
                                                                config('filesystems.default'),
                                                                $buyer->byr_logo,
                                                            );
                                                        @endphp
                                                        <img src="{{ $url }}" alt="Logo" width="50"
                                                            class="rounded">
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>

                                                <td>{{ $buyer->byr_name }}</td>

                                                <td>
                                                    @if ($buyer->byr_type == 1)
                                                        <span class="badge bg-success">Registered</span>
                                                    @else
                                                        <span class="badge bg-secondary">Unregistered</span>
                                                    @endif
                                                </td>

                                                <td class="extra-column">
                                                    <div><strong>NTN/CNIC:</strong> {{ $buyer->byr_ntn_cnic ?? '-' }}</div>
                                                    <div><strong>Contact Person:</strong>
                                                        {{ $buyer->byr_contact_person ?? '-' }}</div>
                                                    <div><strong>Contact #:</strong> {{ $buyer->byr_contact_num ?? '-' }}
                                                    </div>
                                                    <div><strong>Address:</strong> {{ $buyer->byr_address ?? '-' }}</div>
                                                </td>

                                                <td class="extra-column">
                                                    <div><strong>IBAN:</strong> {{ $buyer->byr_IBAN ?? '-' }}</div>
                                                    <div><strong>Account Title:</strong>
                                                        {{ $buyer->byr_account_title ?? '-' }}</div>
                                                    <div><strong>Account Number:</strong>
                                                        {{ $buyer->byr_account_number ?? '-' }}</div>
                                                </td>

                                                <td class="extra-column">
                                                    @if ($buyer->tampered)
                                                        <span class="text-danger fw-bold">⚠ Data tampered!</span>
                                                    @else
                                                        <span class="text-success">OK</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <button type="button"
                                                        class="btn btn-xs btn-outline-success toggle-details"
                                                        data-bs-toggle="tooltip" title="Show Details">
                                                        <i class="fa fa-angle-right"></i>
                                                    </button>

                                                    <a href="{{ route('buyers.edit', Crypt::encryptString($buyer->byr_id)) }}"
                                                        class="btn btn-xs btn-outline-warning" data-bs-toggle="tooltip"
                                                        title="Edit">
                                                        <i class="ti ti-edit"></i>
                                                    </a>

                                                    <form action="{{ route('buyers.delete') }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="byr_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encryptString($buyer->byr_id) }}" />
                                                        <button type="button"
                                                            class="btn btn-xs btn-outline-danger delete-button"
                                                            title="Delete">
                                                            <i class="ti ti-trash f-s-20"></i>
                                                        </button>
                                                    </form>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No buyers found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <div class="paginationtble-bottom">
                                    {{ $buyers->links() }}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script nonce="{{ $nonce ?? '' }}">
        $(document).ready(function() {
            $('#buyersTable').on('click', '.toggle-details', function() {
                const btn = $(this);
                const tr = btn.closest('tr');
                const icon = btn.find('i');

                if (tr.next().hasClass('details-row')) {
                    tr.next().remove();
                    icon.removeClass('fa-angle-down').addClass('fa-angle-right');
                    return;
                }

                $('.details-row').remove();
                $('.toggle-details i').removeClass('fa-angle-down').addClass('fa-angle-right');
                icon.removeClass('fa-angle-right').addClass('fa-angle-down');

                const extraCols = tr.find('td.extra-column');
                const labels = ['Client Details', 'Bank Details', 'Tempered'];
                let detailsHtml = '<div class="card card-body m-0 px-3 py-2"><div class="row g-3">';

                extraCols.each((i, col) => {
                    detailsHtml +=
                        `<div class="col-sm-12 col-md-4"><strong>${labels[i]}:</strong><br>${$(col).html()}</div>`;
                });

                detailsHtml += '</div></div>';

                tr.after(
                    `<tr class="details-row"><td colspan="${tr.children('td').length}" class="p-0">${detailsHtml}</td></tr>`
                );
            });
        });
    </script>
@endsection
