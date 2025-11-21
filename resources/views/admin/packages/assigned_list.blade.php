@extends('admin.layouts.adminlayout')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Business Assigned Packages</h5>
            <a href="{{ route('admin.business_packages.assign.form') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus fa-fw"></i> Assign New Package
            </a>
        </div>

        <!-- Filter Section -->
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.business_packages.index') }}" id="filterForm">
                <div class="row g-3">
                    <!-- Business Filter -->
                    <div class="col-md-3">
                        <label class="form-label">Business</label>
                        <select name="business_id" class="form-select">
                            <option value="">All Businesses</option>
                            @foreach($businesses as $business)
                                <option value="{{ $business->bus_config_id }}" 
                                    {{ request('business_id') == $business->bus_config_id ? 'selected' : '' }}>
                                    {{ $business->bus_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Package Filter -->
                    <div class="col-md-3">
                        <label class="form-label">Package</label>
                        <select name="package_id" class="form-select">
                            <option value="">All Packages</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->package_id }}" 
                                    {{ request('package_id') == $package->package_id ? 'selected' : '' }}>
                                    {{ $package->package_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="trial" {{ request('status') == 'trial' ? 'selected' : '' }}>Trial (All)</option>
                            <option value="trial_active" {{ request('status') == 'trial_active' ? 'selected' : '' }}>Trial Active</option>
                            <option value="trial_expired" {{ request('status') == 'trial_expired' ? 'selected' : '' }}>Trial Expired</option>
                        </select>
                    </div>

                    <!-- Expiring Soon -->
                    <div class="col-md-3">
                        <label class="form-label">Expiring Soon</label>
                        <select name="expiring_soon" class="form-select">
                            <option value="">Not Filtered</option>
                            <option value="7" {{ request('expiring_soon') == '7' ? 'selected' : '' }}>Within 7 Days</option>
                            <option value="15" {{ request('expiring_soon') == '15' ? 'selected' : '' }}>Within 15 Days</option>
                            <option value="30" {{ request('expiring_soon') == '30' ? 'selected' : '' }}>Within 30 Days</option>
                        </select>
                    </div>

                    <!-- Start Date Range -->
                    <div class="col-md-3">
                        <label class="form-label">Start Date From</label>
                        <input type="date" name="start_date_from" class="form-control" value="{{ request('start_date_from') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Start Date To</label>
                        <input type="date" name="start_date_to" class="form-control" value="{{ request('start_date_to') }}">
                    </div>

                    <!-- End Date Range -->
                    <div class="col-md-3">
                        <label class="form-label">End Date From</label>
                        <input type="date" name="end_date_from" class="form-control" value="{{ request('end_date_from') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">End Date To</label>
                        <input type="date" name="end_date_to" class="form-control" value="{{ request('end_date_to') }}">
                    </div>

                    <!-- Discount Range -->
                    <div class="col-md-3">
                        <label class="form-label">Min Discount %</label>
                        <input type="number" name="discount_min" class="form-control" min="0" max="100" 
                            value="{{ request('discount_min') }}" placeholder="e.g., 10">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Max Discount %</label>
                        <input type="number" name="discount_max" class="form-control" min="0" max="100" 
                            value="{{ request('discount_max') }}" placeholder="e.g., 50">
                    </div>

                    <!-- Price Range -->
                    <div class="col-md-3">
                        <label class="form-label">Min Price</label>
                        <input type="number" name="price_min" class="form-control" min="0" step="0.01" 
                            value="{{ request('price_min') }}" placeholder="e.g., 1000">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Max Price</label>
                        <input type="number" name="price_max" class="form-control" min="0" step="0.01" 
                            value="{{ request('price_max') }}" placeholder="e.g., 10000">
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-filter"></i> Apply Filters
                    </button>
                    <a href="{{ route('admin.business_packages.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-times"></i> Clear Filters
                    </a>
                    <button type="button" class="btn btn-outline-secondary" onclick="toggleFilters()">
                        <i class="fa-solid fa-chevron-up" id="toggleIcon"></i> <span id="toggleText">Hide Filters</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="card-body table-responsive">
            @if($assigned->count() > 0)
                <div class="alert alert-info">
                    <i class="fa-solid fa-info-circle"></i> Showing <strong>{{ $assigned->count() }}</strong> result(s)
                </div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Business</th>
                        <th>Package</th>
                        <th>Start - End</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>Final Price</th>
                        <th>Features</th>
                        <th>Usage</th>
                        <th>Status / Days Left</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $today = \Carbon\Carbon::now(); @endphp
                    @forelse ($assigned as $a)
                        @php
                            $isTrial = $a->is_trial;
                            $trialDaysLeft = 0;
                            if ($isTrial && $a->trial_end_date) {
                                $trialEnd = \Carbon\Carbon::parse($a->trial_end_date);
                                $trialDaysLeft = (int) $today->diffInDays($trialEnd, false);
                                if ($trialDaysLeft < 0) {
                                    $trialDaysLeft = 0;
                                }
                            }

                            $isExpired = $today->gt($a->end_date);
                            $daysLeft = (int) $today->diffInDays($a->end_date, false);
                            if ($daysLeft < 0) {
                                $daysLeft = 0;
                            }

                            $statusLabel = $isExpired ? 'Expired' : ($a->is_active ? 'Active' : 'Inactive');
                            $statusClass = $isExpired ? 'bg-danger' : ($a->is_active ? 'bg-success' : 'bg-secondary');
                        @endphp

                        <tr class="{{ $isExpired ? 'table-danger' : ($a->is_active ? 'table-success' : '') }}">
                            <td>
                                <strong>{{ $a->business->bus_name }}</strong><br>
                                <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td>
                                {{ $a->package->package_name }}<br>
                                <small class="text-muted">{{ ucfirst($a->package->package_billing_cycle) }} billing</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($a->start_date)->format('d-M-Y') }} →
                                {{ \Carbon\Carbon::parse($a->end_date)->format('d-M-Y') }}</td>
                            <td>{{ number_format($a->package->package_price, 2) }}</td>
                            <td>{{ $a->discount }}%</td>
                            <td><strong>{{ number_format($a->price_after_discout, 2) }}</strong></td>
                            <td>
                                <ul>
                                    @foreach ($a->features as $f)
                                        <li>{{ $f->feature_key }} ({{ $f->limit_type }}: {{ $f->limit_value }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td style="min-width: 280px;">
                                @foreach ($a->features as $f)
                                    @php
                                        $usage = $a->usage->firstWhere('feature_key', $f->feature_key);
                                        $used = $usage->used_count ?? 0;
                                        $limit = $f->limit_value;
                                        $percent = $limit > 0 ? round(($used / $limit) * 100) : 0;
                                    @endphp
                                    <div class="mb-1">
                                        <strong>{{ $f->feature_key }}</strong>
                                        <small class="text-muted">({{ $f->limit_type }} {{ $limit }})</small>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ $percent }}%" aria-valuenow="{{ $percent }}"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small>{{ $used }}/{{ $limit }}</small>
                                    </div>
                                @endforeach
                            </td>
                            <td>
                                @if ($isTrial)
                                    <span class="badge bg-info">Trial</span><br>
                                    <small>{{ $trialDaysLeft }} days left</small>
                                @else
                                    <small>{{ $daysLeft }} days left</small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 align-items-center">
                                    @if ($isExpired)
                                        <form action="{{ route('admin.business_packages.renew') }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <input type="hidden" name="business_packages_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encryptString($a->business_packages_id) }}" />
                                            <button type="button" class="btn btn-sm btn-warning renew-button"
                                                title="Renew">
                                                Renew
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.business_packages.toggle') }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <input type="hidden" name="business_packages_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encryptString($a->business_packages_id) }}" />

                                        @if ($a->is_active)
                                            <button type="submit" class="btn btn-sm btn-danger" title="Deactivate">
                                                Deactivate
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-success" title="Activate">
                                                Activate
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No packages found matching the selected filters.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function toggleFilters() {
            const filterForm = document.querySelector('#filterForm .row');
            const toggleIcon = document.getElementById('toggleIcon');
            const toggleText = document.getElementById('toggleText');
            
            if (filterForm.style.display === 'none') {
                filterForm.style.display = 'flex';
                toggleIcon.classList.remove('fa-chevron-down');
                toggleIcon.classList.add('fa-chevron-up');
                toggleText.textContent = 'Hide Filters';
            } else {
                filterForm.style.display = 'none';
                toggleIcon.classList.remove('fa-chevron-up');
                toggleIcon.classList.add('fa-chevron-down');
                toggleText.textContent = 'Show Filters';
            }
        }

        // Show filters if any filter is active
        window.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.toString() && urlParams.toString() !== '') {
                // Filters are active, keep them visible
            }
        });
    </script>
@endsection
