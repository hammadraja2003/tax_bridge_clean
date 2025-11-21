@extends('admin.layouts.adminlayout')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Edit Package</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.packages.update', $package->package_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Package Name</label>
                                    <input type="text" name="package_name" class="form-control"
                                        value="{{ old('package_name', $package->package_name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Description</label>
                                    <textarea name="package_description" class="form-control">{{ old('package_description', $package->package_description) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Price</label>
                                    <input type="number" name="package_price" class="form-control" step="0.01"
                                        value="{{ old('package_price', $package->package_price) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Billing Cycle</label>
                                    <select name="package_billing_cycle" class="form-control" required>
                                        <option value="monthly"
                                            {{ $package->package_billing_cycle == 'monthly' ? 'selected' : '' }}>Monthly
                                        </option>
                                        <option value="quarterly"
                                            {{ $package->package_billing_cycle == 'quarterly' ? 'selected' : '' }}>Quarterly
                                        </option>
                                        <option value="yearly"
                                            {{ $package->package_billing_cycle == 'yearly' ? 'selected' : '' }}>Yearly
                                        </option>
                                        <option value="custom"
                                            {{ $package->package_billing_cycle == 'custom' ? 'selected' : '' }}>Custom
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <h6 class="mt-4">Features</h6>
                            <div id="featuresWrapper">
                                @foreach ($package->features as $index => $feature)
                                    <div class="featureRow mb-2 row">
                                        <div class="col-4">
                                            <input type="text" name="features[{{ $index }}][feature_key]"
                                                class="form-control" value="{{ $feature->feature_key }}"
                                                placeholder="Feature Key" required>
                                        </div>
                                        <div class="col-4">
                                            <select name="features[{{ $index }}][limit_type]" class="form-control"
                                                required>
                                                <option value="monthly"
                                                    {{ $feature->limit_type == 'monthly' ? 'selected' : '' }}>Monthly
                                                </option>
                                                <option value="quarterly"
                                                    {{ $feature->limit_type == 'quarterly' ? 'selected' : '' }}>Quarterly
                                                </option>
                                                <option value="yearly"
                                                    {{ $feature->limit_type == 'yearly' ? 'selected' : '' }}>
                                                    Yearly</option>
                                                <option value="total"
                                                    {{ $feature->limit_type == 'total' ? 'selected' : '' }}>
                                                    Total</option>
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <input type="number" name="features[{{ $index }}][limit_value]"
                                                class="form-control" value="{{ $feature->limit_value }}"
                                                placeholder="Limit Value" required>
                                        </div>
                                        <div class="col-1">
                                            <button type="button" class="btn btn-danger removeFeature">x</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" id="addFeature" class="btn btn-sm btn-secondary mb-3">+ Add
                                Feature</button>

                            <div class="row">
                                <div class="col-12 d-flex justify-content-end mb-3">
                                    <button type="submit" class="btn btn-success  me-2">Update Package</button>
                                    <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let featureIndex = {{ count($package->features) }};
            document.getElementById('addFeature').addEventListener('click', function() {
                const wrapper = document.getElementById('featuresWrapper');
                const row = document.querySelector('.featureRow').cloneNode(true);
                row.querySelectorAll('input, select').forEach(function(el) {
                    el.name = el.name.replace(/\d+/, featureIndex);
                    el.value = '';
                });
                wrapper.appendChild(row);
                featureIndex++;
            });

            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('removeFeature')) {
                    const rows = document.querySelectorAll('.featureRow');
                    if (rows.length > 1) {
                        e.target.closest('.featureRow').remove();
                    }
                }
            });
        </script>
    @endpush
@endsection
