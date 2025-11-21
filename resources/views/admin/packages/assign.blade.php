@extends('admin.layouts.adminlayout')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-12">
                <div class="card">

                    <div class="card-header">
                        <h5>Assign Package to Business</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.business_packages.assign') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Select Business</label>
                                    <select name="business_id" class="form-control" required>
                                        <option value="">-- Select Business --</option>
                                        @foreach ($businesses as $b)
                                            <option value="{{ $b->bus_config_id }}">{{ $b->bus_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Select Package</label>
                                    <select name="package_id" id="packageSelect" class="form-control" required>
                                        <option value="">-- Select Package --</option>
                                        @foreach ($packages as $p)
                                            <option value="{{ $p->package_id }}" data-price="{{ $p->package_price }}"
                                                data-features='@json($p->features)'>
                                                {{ $p->package_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row g-3 mt-3">
                                <div class="col-md-4 d-flex align-items-center">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="is_trial" id="isTrial">
                                        <label class="form-check-label" for="isTrial">
                                            Assign as Trial
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Trial Days</label>
                                    <input type="number" name="trial_days" id="trialDays" class="form-control"
                                        min="1" value="7" disabled>
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <label class="form-label">Package Price</label>
                                    <input type="number" id="packagePrice" class="form-control" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Discount (%)</label>
                                    <input type="number" name="discount" id="discount" class="form-control" min="0"
                                        max="100" value="0">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Price After Discount</label>
                                    <input type="number" name="price_after_discount" id="priceAfterDiscount"
                                        class="form-control" readonly>
                                </div>
                            </div>


                            <div class="row g-3 mt-2">
                                <div class="mb-3">
                                    <label class="form-label">Package Features (Editable)</label>
                                    <div id="featuresWrapper">
                                        <small>Select a package to see features</small>
                                    </div>
                                    <button type="button" id="addFeature" class="btn btn-sm btn-secondary mt-2">+ Add
                                        Feature</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end mb-3">
                                    <button type="submit" class="btn btn-success">Assign Package</button>
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
            let featureIndex = 0;

            document.getElementById('packageSelect').addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                const wrapper = document.getElementById('featuresWrapper');
                const features = selected.dataset.features ? JSON.parse(selected.dataset.features) : [];

                wrapper.innerHTML = ''; // Clear previous

                features.forEach((f, index) => {
                    addFeatureRow(f.feature_key, f.limit_type, f.limit_value, index);
                    featureIndex = index + 1;
                });


                const price = selected.dataset.price ? parseFloat(selected.dataset.price) : 0;
                document.getElementById('packagePrice').value = price;
                calculateFinalPrice();
            });
            document.getElementById('discount').addEventListener('input', calculateFinalPrice);

            function calculateFinalPrice() {
                const price = parseFloat(document.getElementById('packagePrice').value) || 0;
                const discount = parseFloat(document.getElementById('discount').value) || 0;
                const finalPrice = price - (price * discount / 100);
                document.getElementById('priceAfterDiscount').value = finalPrice.toFixed(2);
            }

            // Add a new feature row
            document.getElementById('addFeature').addEventListener('click', function() {
                addFeatureRow('', 'monthly', 0, featureIndex);
                featureIndex++;
            });

            // Event delegation for removing feature rows
            document.getElementById('featuresWrapper').addEventListener('click', function(e) {
                if (e.target.classList.contains('removeFeature')) {
                    const wrapper = document.getElementById('featuresWrapper'); // define wrapper here
                    const rows = wrapper.querySelectorAll('.featureRow');
                    if (rows.length > 1) {
                        e.target.closest('.featureRow').remove();
                    } else {
                        alert('At least one feature is required.');
                    }
                }
            });

            function addFeatureRow(featureKey = '', limitType = 'monthly', limitValue = 0, index) {
                const wrapper = document.getElementById('featuresWrapper');
                const row = document.createElement('div');
                row.className = 'featureRow mb-2 row align-items-center';

                row.innerHTML = `
                    <div class="col-4">
                        <input type="text" name="features[${index}][feature_key]" class="form-control" placeholder="Feature Key" value="${featureKey}" required>
                    </div>
                    <div class="col-4">
                        <select name="features[${index}][limit_type]" class="form-control" required>
                            <option value="monthly" ${limitType=='monthly'?'selected':''}>Monthly</option>
                            <option value="quarterly" ${limitType=='quarterly'?'selected':''}>Quarterly</option>
                            <option value="yearly" ${limitType=='yearly'?'selected':''}>Yearly</option>
                            <option value="total" ${limitType=='total'?'selected':''}>Total</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <input type="number" name="features[${index}][limit_value]" class="form-control" placeholder="Limit Value" value="${limitValue}" required>
                    </div>
                    <div class="col-1">
                        <button type="button" class="btn btn-danger removeFeature">x</button>
                    </div>
                `;
                wrapper.appendChild(row);
            }

            const isTrialCheckbox = document.getElementById('isTrial');
            const trialDaysInput = document.getElementById('trialDays');
            const discountInput = document.getElementById('discount');
            const priceAfterDiscountInput = document.getElementById('priceAfterDiscount');

            isTrialCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    trialDaysInput.disabled = false;
                    // Disable price and discount for trial
                    discountInput.value = 0;
                    priceAfterDiscountInput.value = 0;
                    discountInput.disabled = true;
                } else {
                    trialDaysInput.disabled = true;
                    trialDaysInput.value = 7;
                    discountInput.disabled = false;
                    calculateFinalPrice();
                }
            });
        </script>
    @endpush
@endsection
