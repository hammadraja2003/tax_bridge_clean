@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="row table_setting">
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">FBR Post Errors</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="myTable">
                            <div class="list-table-header d-flex justify-content-end align-items-center p-3">
                                <form class="app-form app-icon-form" action="#">
                                    <div class="position-relative">
                                        <input type="search" class="form-control search" placeholder="Search..."
                                            aria-label="Search">
                                    </div>
                                </form>
                            </div>
                            <div class="app-scroll overflow-auto">
                                <table id="projectTableT" class="table table-striped table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th>Date/Time</th>
                                            <th>Type</th>
                                            <th>FBR ENV</th>
                                            <th>Status Code</th>
                                            <th>Status</th>
                                            <th>Error Code</th>
                                            <th>Error</th>
                                            <th>Invoice Statuses</th>
                                            <th>Raw Response</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($fbr_errors as $err)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($err->error_time)->format('d-M-Y h:i:s A') }}
                                                </td>
                                                <td>{{ $err->type }}</td>
                                                <td>{{ $err->fbr_env }}</td>
                                                <td>{{ $err->status_code }}</td>
                                                <td>
                                                    <span
                                                        class="{{ strtolower($err->status) === 'failed' || strtolower($err->status) === 'invalid' ? 'text-danger' : 'text-success' }}">
                                                        {{ $err->status }}
                                                    </span>
                                                </td>
                                                <td>{{ $err->error_code }}</td>
                                                <td>
                                                    <div class="collapsible-text" title="Click to expand"
                                                        data-full='@json($err->error)'
                                                        data-short='@json(\Illuminate\Support\Str::limit($err->error, 30))'
                                                        style="cursor:pointer; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                        {{ \Illuminate\Support\Str::limit($err->error, 30) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if (!empty($err->invoice_statuses))
                                                        <div class="json-container">
                                                            <button class="btn btn-sm btn-light toggle-json mb-1">+</button>
                                                            <pre class="json-content mb-0 d-none">{{ json_encode($err->invoice_statuses, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (!empty($err->raw_response))
                                                        <div class="json-container">
                                                            <button class="btn btn-sm btn-light toggle-json mb-1">+</button>
                                                            <pre class="json-content mb-0 d-none">{{ json_encode($err->raw_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center text-muted">No FBR posting errors
                                                    found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="paginationtble-bottom">{!! $fbr_errors->links() !!}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script nonce="{{ $nonce ?? '' }}">
            document.addEventListener('DOMContentLoaded', function() {
                // Toggle JSON blocks (unchanged)
                document.querySelectorAll('.toggle-json').forEach(button => {
                    button.addEventListener('click', function() {
                        const pre = this.nextElementSibling;
                        pre.classList.toggle('d-none');
                        this.textContent = pre.classList.contains('d-none') ? '+' : '-';
                    });
                });
                // Collapsible error text
                document.querySelectorAll('.collapsible-text').forEach(el => {
                    const fullText = (el.dataset.full ? JSON.parse(el.dataset.full) : '');
                    const shortText = (el.dataset.short ? JSON.parse(el.dataset.short) : fullText);
                    // Show short by default
                    el.textContent = shortText;
                    el.dataset.expanded = '0';
                    el.addEventListener('click', function() {
                        const expanded = this.dataset.expanded === '1';
                        this.textContent = expanded ? shortText : fullText;
                        this.dataset.expanded = expanded ? '0' : '1';
                        this.title = expanded ? 'Click to expand' : 'Click to collapse';
                    });
                });
            });
        </script>
    @endpush
@endsection
