@extends('admin.layouts.adminlayout')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Create Package</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.packages.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Package Name</label>
                                    <input type="text" name="package_name" class="form-control"
                                        value="{{ old('package_name') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Description</label>
                                    <textarea name="package_description" class="form-control">{{ old('package_description') }}</textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Price</label>
                                    <input type="number" name="package_price" class="form-control" step="0.01"
                                        value="{{ old('package_price') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Billing Cycle</label>
                                    <select name="package_billing_cycle" class="form-control" required>
                                        <option value="monthly">Monthly</option>
                                        <option value="quarterly">Quarterly</option>
                                        <option value="yearly">Yearly</option>
                                        <option value="custom">Custom</option>
                                    </select>
                                </div>
                            </div>

                            <h6 class="mt-4">Features</h6>
                            <div id="featuresWrapper">
                                <!-- Features will be added here dynamically -->
                            </div>
                            <button type="button" id="addFeature" class="btn btn-sm btn-secondary mb-3">+ Add
                                Feature</button>

                            <div class="row">
                                <div class="col-12 d-flex justify-content-end mb-3">
                                    <button type="submit" class="btn btn-success me-2">Create Package</button>
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
            document.addEventListener('DOMContentLoaded', function() {
                let featureIndex = 0;
                const wrapper = document.getElementById('featuresWrapper');
                const addFeatureBtn = document.getElementById('addFeature');

                // Function to create a new feature row
                function createFeatureRow(index) {
                    const row = document.createElement('div');
                    row.classList.add('featureRow', 'mb-2', 'row');

                    row.innerHTML = `
                        <div class="col-4">
                            <input type="text" name="features[${index}][feature_key]" class="form-control" placeholder="Feature Key" required>
                        </div>
                        <div class="col-4">
                            <select name="features[${index}][limit_type]" class="form-control" required>
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="yearly">Yearly</option>
                                <option value="total">Total</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <input type="number" name="features[${index}][limit_value]" class="form-control" placeholder="Limit Value" required>
                        </div>
                        <div class="col-1">
                            <button type="button" class="btn btn-danger btn-sm removeFeature">×</button>
                        </div>
                    `;
                    return row;
                }

                // Add first row on page load
                wrapper.appendChild(createFeatureRow(featureIndex));
                featureIndex++;

                // Add new feature row
                addFeatureBtn.addEventListener('click', function() {
                    wrapper.appendChild(createFeatureRow(featureIndex));
                    featureIndex++;
                });

                // Remove feature row (Event delegation)
                wrapper.addEventListener('click', function(e) {
                    if (e.target.classList.contains('removeFeature')) {
                        const rows = wrapper.querySelectorAll('.featureRow');
                        if (rows.length > 1) {
                            e.target.closest('.featureRow').remove();
                        } else {
                            alert('At least one feature is required.');
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
