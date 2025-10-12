@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="row table_setting">
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Invoice Details</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="p-3 border-bottom">
                            <form method="GET" action="{{ route('invoices.index') }}" class="row g-2 align-items-end">
                                {{-- Invoice Type --}}
                                <div class="col-md-2">
                                    <label for="invoice_type" class="form-label">Invoice Type</label>
                                    <select name="invoice_type" id="invoice_type" class="form-select">
                                        <option value="">All Types</option>
                                        <option value="Sale Invoice"
                                            {{ request('invoice_type') == 'Sale Invoice' ? 'selected' : '' }}>Sale Invoice
                                        </option>
                                        <option value="Debit Note"
                                            {{ request('invoice_type') == 'Debit Note' ? 'selected' : '' }}>Debit Note
                                        </option>
                                    </select>
                                </div>
                                {{-- Posted to FBR --}}
                                <div class="col-md-2">
                                    <label for="is_posted_to_fbr" class="form-label">Posted to FBR?</label>
                                    <select name="is_posted_to_fbr" id="is_posted_to_fbr" class="form-select">
                                        <option value="">All</option>
                                        <option value="1" {{ request('is_posted_to_fbr') === '1' ? 'selected' : '' }}>
                                            Yes</option>
                                        <option value="0" {{ request('is_posted_to_fbr') === '0' ? 'selected' : '' }}>
                                            No</option>
                                    </select>
                                </div>
                                {{-- Date From --}}
                                <div class="col-md-2">
                                    <label for="date_from" class="form-label">Date From</label>
                                    <input type="date" name="date_from" id="date_from" class="form-control"
                                        value="{{ request('date_from') }}">
                                </div>
                                {{-- Date To --}}
                                <div class="col-md-2">
                                    <label for="date_to" class="form-label">Date To</label>
                                    <input type="date" name="date_to" id="date_to" class="form-control"
                                        value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    @if (request()->filled('invoice_type') ||
                                            request()->filled('date_from') ||
                                            request()->filled('date_to') ||
                                            request()->filled('is_posted_to_fbr'))
                                        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Clear</a>
                                    @endif
                                </div>
                            </form>
                        </div>
                        <div id="myTable">
                            <div class="list-table-header d-flex justify-content-between align-items-center p-3">
                                <a href="{{ route('invoices.create') }}" class="btn btn-primary"><i
                                        class="fa-solid fa-plus fa-fw"></i>New Invoice</a>
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                </div>
                                <form class="app-form app-icon-form" action="#">
                                    <div class="position-relative ">
                                        <input type="search" class="form-control search" placeholder="Search..."
                                            aria-label="Search">
                                    </div>
                                </form>
                            </div>
                            <div class="app-scroll table-responsive overflow-auto">
                                <table id="projectTableT" class="table table-sm table-striped table-bordered text-nowrap">
                                    <thead>
                                        <tr class="app-sort">
                                            <th>Invoice Date</th>
                                            <th>Invoice #</th>
                                            <th>Posted to FBR</th>
                                            <th>Buyer</th>
                                            <th>FBR Invoice #</th>
                                            <th>Invoice Status</th>
                                            <th>Excl. Tax</th>
                                            <th>Incl. Tax</th>
                                            <th class="extra-column">Type</th>
                                            <th class="extra-column">FBR Env</th>
                                            <th class="extra-column">Seller</th>
                                            <th class="extra-column">Total Sales Tax</th>
                                            <th class="extra-column">Total Further Tax</th>
                                            <th class="extra-column">Total Extra Tax</th>
                                            <th class="extra-column">Tempered</th>
                                            <th class="w-190">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list" id="t-data">
                                        @forelse($invoices as $inv)
                                            <tr @if ($inv->tampered || $inv->tampered_lines) class="table-warning" @endif>
                                                <td>{{ \Carbon\Carbon::parse($inv->invoice_date)->format('d M Y') }}</td>
                                                <td class="employee">{{ $inv->invoice_no }}</td>
                                                <td class="contact">
                                                    @if ($inv->is_posted_to_fbr === 1)
                                                        <span class="badge text-bg-success">Yes</span>
                                                    @elseif ($inv->is_posted_to_fbr === 0)
                                                        <span class="badge text-bg-danger">No</span>
                                                    @else
                                                        <span class="badge text-bg-secondary">N/A</span>
                                                    @endif
                                                </td>
                                                <td>{{ $inv->buyer->byr_name }}</td>
                                                <td class="contact">{{ $inv->fbr_invoice_number ?? 'N/A' }}</td>
                                                <td class="employee">
                                                    @if ($inv->invoice_status === 1)
                                                        <span class="badge text-bg-secondary">Draft</span>
                                                    @elseif ($inv->invoice_status === 2)
                                                        <span class="badge text-bg-success">Posted</span>
                                                    @else
                                                        <span class="badge text-bg-secondary">N/A</span>
                                                    @endif
                                                </td>
                                                <td class="">{{ number_format($inv->totalAmountExcludingTax, 2) }}
                                                </td>
                                                <td class="">{{ number_format($inv->totalAmountIncludingTax, 2) }}
                                                </td>

                                                <td class="employee extra-column">{{ $inv->invoice_type }}</td>
                                                <td class="email extra-column">{{ $inv->fbr_environment ?? '-' }}</td>

                                                <td class="email extra-column">{{ $inv->seller->bus_name ?? '-' }}</td>

                                                <td class="extra-column">{{ number_format($inv->totalSalesTax, 2) }}</td>
                                                <td class="extra-column">{{ number_format($inv->totalfurtherTax, 2) }}</td>
                                                <td class="extra-column">{{ number_format($inv->totalextraTax, 2) }}</td>
                                                <td class="extra-column">
                                                    @if ($inv->tampered)
                                                        <span class="badge bg-danger">Header Tampered</span>
                                                    @endif
                                                    @if ($inv->tampered_lines)
                                                        <span class="badge bg-warning text-dark">Line Item Tampered</span>
                                                    @endif
                                                </td>

                                                <td class="actioncolumn">
                                                    <button type="button"
                                                        class="btn btn-xs btn-outline-secondary toggle-details"
                                                        data-bs-toggle="tooltip" title="Show Details">+</button>


                                                    <button type="button" class="btn btn-xs btn-outline-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#itemsModal{{ $inv->invoice_id }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="View Item">
                                                        <i class="fa-solid fa-eye fa-fw"></i>
                                                    </button>

                                                    {{-- <a href="#" class="btn btn-xs btn-outline-info show-invoice"
                                                        data-invoice-id="{{ Crypt::encryptString($inv->invoice_id) }}"
                                                        data-bs-toggle="tooltip" title="View Invoice">
                                                        <i class="fa-solid fa-print fa-fw"></i>
                                                    </a> --}}

                                                    <a href="#" class="btn btn-xs btn-outline-info preview-invoice"
                                                        data-invoice-id="{{ Crypt::encryptString($inv->invoice_id) }}"
                                                        title="Preview Invoice" data-bs-toggle="modal"
                                                        data-bs-target="#invoicePreviewModal">
                                                        <i class="fa-solid fa-print fa-fw"></i>
                                                    </a>
                                                    @if ($inv->invoice_status === 1)
                                                        <a href="{{ route('invoices.edit', Crypt::encryptString($inv->invoice_id)) }}"
                                                            class="btn btn-xs btn-outline-warning"
                                                            data-bs-toggle="tooltip" title="Edit Invoice">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>
                                                    @endif


                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="16" class="text-center">No Invoice found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="paginationtble-bottom">
                                {{ $invoices->links() }}
                            </div>
                            @foreach ($invoices as $invoice)
                                <div class="modal fade" id="itemsModal{{ $invoice->invoice_id }}" tabindex="-1"
                                    aria-labelledby="itemsModalLabel{{ $invoice->invoice_id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Invoice Item Details</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                @if ($invoice->details->count())
                                                    <div class="table-responsive">
                                                        <table class="table table-hover align-middle">
                                                            <thead class="table-light">
                                                                <tr class="text-uppercase small text-muted">
                                                                    <th>Description</th>
                                                                    <th>Qty</th>
                                                                    <th>Price</th>
                                                                    <th>Tax</th>
                                                                    <th>Total (Inc)</th>
                                                                    <th>Total (Exc)</th>
                                                                    <th>Sales Tax</th>
                                                                    <th>Extra</th>
                                                                    <th>Further</th>
                                                                    <th>Fed</th>
                                                                    <th>SRO #</th>
                                                                    <th>Serial #</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($invoice->details as $item)
                                                                    <tr>
                                                                        <td class="fw-medium">
                                                                            {{ $item->item->item_description ?? 'N/A' }}
                                                                        </td>
                                                                        <td>{{ $item->quantity }}</td>
                                                                        <td>{{ number_format($item->item->item_price, 2) }}
                                                                        </td>
                                                                        <td>{{ number_format($item->item->item_tax_rate, 2) }}
                                                                        </td>
                                                                        <td class="text-success">
                                                                            {{ number_format($item->total_value, 2) }}</td>
                                                                        <td>{{ number_format($item->value_excl_tax, 2) }}
                                                                        </td>
                                                                        <td>{{ number_format($item->sales_tax_withheld, 2) }}
                                                                        </td>
                                                                        <td>{{ number_format($item->extra_tax, 2) }}</td>
                                                                        <td>{{ number_format($item->further_tax, 2) }}</td>
                                                                        <td>{{ number_format($item->fed_payable, 2) }}</td>
                                                                        <td>{{ $item->sro_schedule_no }}</td>
                                                                        <td>{{ $item->sro_item_serial_no }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <p class="text-muted">No items found for this invoice.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Preview Modal -->
    <div class="modal fade" id="invoicePreviewModal" tabindex="-1" aria-labelledby="invoicePreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" style="max-width: 60vw;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoicePreviewModalLabel">Invoice Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="invoicePreviewFrame" src=""
                        style="width: 100%; height: 850px; border: 1px solid #ccc;"></iframe>
                </div>
            </div>
        </div>
    </div>


    <!-- Invoice Preview Modal -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Invoice Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="height: 85vh;">
                    <div id="invoiceContent" style="height: 100%; overflow-y: auto;">
                        <!-- Invoice content will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="printInvoiceBtn">Print</button>
                </div>
            </div>
        </div>
    </div>

    <script nonce="{{ $nonce ?? '' }}">
        document.addEventListener('click', function(e) {
            const target = e.target.closest('.show-invoice');
            if (target) {
                e.preventDefault();
                const invoiceId = target.getAttribute('data-invoice-id');
                const contentDiv = document.getElementById('invoiceContent');
                contentDiv.innerHTML =
                    '<div class="d-flex justify-content-center align-items-center" style="height: 100%;"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
                const modal = new bootstrap.Modal(document.getElementById('invoiceModal'));
                modal.show();

                fetch(`/invoices/modal-preview/${invoiceId}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        contentDiv.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        contentDiv.innerHTML =
                            '<div class="alert alert-danger">Error loading invoice preview.</div>';
                    });
                document.getElementById('printInvoiceBtn').onclick = function() {
                    const printContent = document.getElementById('invoiceContent').innerHTML;
                    const originalContent = document.body.innerHTML;

                    document.body.innerHTML = printContent;
                    window.print();
                    document.body.innerHTML = originalContent;
                    location.reload();
                };
            }
        });
    </script>

    <script nonce="{{ $nonce ?? '' }}">
        $(document).ready(function() {
            $('#projectTableT').on('click', '.toggle-details', function() {
                var btn = $(this);
                var tr = btn.closest('tr');
                if (tr.next().hasClass('details-row')) {
                    tr.next().remove();
                    btn.text('+');
                    return;
                }
                $('.details-row').remove();
                $('.toggle-details').text('+');
                btn.text('-');
                var hiddenCells = tr.children('td.extra-column');
                var labels = ["Type", "FBR Env", "Seller", "Total Sales Tax", "Total Further Tax",
                    "Total Extra Tax", "Tempered"
                ];
                var detailsHtml = '<div class="card card-body m-0 px-3 py-2"><div class="row">';
                hiddenCells.each((i, cell) => {
                    detailsHtml +=
                        `<div class="col-sm-12 col-md-3"><strong>${labels[i]}:</strong> ${$(cell).html()}</div>`;
                });
                detailsHtml += '</div></div>';
                tr.after(
                    `<tr class="details-row"><td colspan="${tr.children('td').length}" class="p-0">${detailsHtml}</td></tr>`
                );
            });
        });
    </script>
    <script nonce="{{ $nonce ?? '' }}">
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.preview-invoice').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    let invoiceId = this.getAttribute('data-invoice-id');
                    let previewUrl = `/invoices/${invoiceId}/download`; // Your preview URL

                    // Set iframe src to load PDF
                    document.getElementById('invoicePreviewFrame').src = previewUrl;

                    // Manually open the modal using Bootstrap 5 JS API
                    let modalElement = document.getElementById('invoicePreviewModal');
                    let modal = bootstrap.Modal.getOrCreateInstance(modalElement);
                    modal.show();
                });
            });

            // Optional: Clear iframe src when modal closes to free memory
            let modalElement = document.getElementById('invoicePreviewModal');
            modalElement.addEventListener('hidden.bs.modal', function() {
                document.getElementById('invoicePreviewFrame').src = '';
            });
        });
    </script>
@endsection
