@extends('layouts.admin')
@section('content')
    <h2 class="mb-4 text-center">Invoice Submission Form</h2>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @php
        $isEdit = isset($invoice);
    @endphp
    <div class="container-fluid">
        <form class="app-form needs-validation" id="invoiceForm" method="POST"
            action="{{ $isEdit ? route('invoice.update', $invoice->invoice_id) : route('create-new-invoice') }}">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif
            <input type="hidden" name="invoice_status" id="invoice_status" value="1">
            <!-- Invoice Info -->
            <div class="card mb-4">
                <div class="card-header">Invoice Info</div>
                <div class="card-body row g-3">
                    <div class="col-md-3">
                        <label class="form-label required">Invoice Type</label>
                        <select name="invoiceType" id="invoiceType" class="form-select" required>
                            <option value="">Select Invoice Type</option>
                            <option value="Sale Invoice"
                                {{ old('invoiceType', $isEdit ? $invoice->invoice_type : '') === 'Sale Invoice' ? 'selected' : '' }}>
                                Sales Invoice
                            </option>
                            <option value="Debit Note"
                                {{ old('invoiceType', $isEdit ? $invoice->invoice_type : '') === 'Debit Note' ? 'selected' : '' }}>
                                Debit Note
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label required">Invoice Date</label>
                        <input type="date" name="invoiceDate" id="invoiceDate" class="form-control" required
                            value="{{ old('invoiceDate', $isEdit ? \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') : '') }}" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" id="dueDate" class="form-control"
                            value="{{ old('due_date', $isEdit && $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d') : '') }}" />
                    </div>
                    <div class="col-md-3" id="invoiceRefWrapper">
                        <label class="form-label">Invoice Ref No (if Debit Note)</label>
                        <input type="text" name="invoiceRefNo" id="invoiceRefNo" class="form-control"
                            value="{{ old('invoiceRefNo', $isEdit ? $invoice->invoice_ref_no : '') }}" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Scenario ID</label>
                        <select name="scenarioId" class="form-select" id="scenarioId">
                            {!! renderScenarioOptions(old('scenarioId', $isEdit ? $invoice->scenario_id : null)) !!}
                        </select>
                    </div>
                </div>
            </div>
            <!-- Seller Info -->
            <div class="card mb-4">
                <div class="card-header">Seller Info</div>
                <div class="card-body row g-3">
                    <div class="col-md-4">
                        <label class="form-label required">NTN / CNIC</label>
                        <input type="hidden" name="seller_id" value="{{ $seller->bus_config_id }}" />
                        <input type="text" name="sellerNTNCNIC" class="form-control" value="{{ $seller->bus_ntn_cnic }}"
                            required readonly />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Business Name</label>
                        <input type="text" name="sellerBusinessName" class="form-control"
                            value="{{ $seller->bus_name }}" required readonly />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Province</label>
                        <input type="text" name="sellerProvince" class="form-control"
                            value="{{ $seller->bus_province }}" required readonly />
                    </div>
                    <div class="col-md-12">
                        <label class="form-label required">Address</label>
                        <textarea name="sellerAddress" class="form-control" required readonly>{{ $seller->bus_address }}</textarea>
                    </div>
                </div>
            </div>
            <!-- Buyer Info -->
            <div class="card mb-4">
                <div class="card-header">
                    Client Info
                </div>
                <div class="card-body row g-3">
                    <div class="col-md-12">
                        <div class="py-2">
                            <strong class="note-color">Note:</strong> If <em>Client Registration Type</em> is <strong>Unregistered</strong>,
                            then all
                            fields
                            except <strong>Registration Type</strong> are optional.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Select Client</label>
                        <select id="byr_id" class="form-select" name="byr_id" required>
                            <option value="">-- Choose Client --</option>
                            @foreach ($buyers as $b)
                                <option value="{{ $b->byr_id }}"
                                    {{ old('byr_id', $isEdit ? $invoice->buyer_id : '') == $b->byr_id ? 'selected' : '' }}>
                                    {{ $b->byr_name }}
                                </option>
                            @endforeach
                        </select>
                        <span id="buyerLoader" class="ms-2 d-none">
                            <i class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">NTN / CNIC</label>
                        <input type="text" name="buyerNTNCNIC" class="form-control optional-field" readonly
                            value="{{ old('buyerNTNCNIC', $isEdit ? $invoice->buyerNTNCNIC : '') }}" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Business Name</label>
                        <input type="text" name="buyerBusinessName" class="form-control optional-field" readonly
                            value="{{ old('buyerBusinessName', $isEdit ? $invoice->buyerBusinessName : '') }}" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Province</label>
                        <input type="text" name="buyerProvince" class="form-control optional-field" readonly
                            value="{{ old('buyerProvince', $isEdit ? $invoice->buyerProvince : '') }}" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Registration Type</label>
                        <select name="buyerRegistrationType" id="buyerRegistrationType" class="form-select" required
                            readonly>
                            <option value="">-- Select --</option>
                            <option value="Registered"
                                {{ old('buyerRegistrationType', $isEdit ? $invoice->buyerRegistrationType : '') === 'Registered' ? 'selected' : '' }}>
                                Registered
                            </option>
                            <option value="Unregistered"
                                {{ old('buyerRegistrationType', $isEdit ? $invoice->buyerRegistrationType : '') === 'Unregistered' ? 'selected' : '' }}>
                                Unregistered
                            </option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Address</label>
                        <textarea name="buyerAddress" class="form-control optional-field" readonly>{{ old('buyerAddress', $isEdit ? $invoice->buyerAddress : '') }}</textarea>
                    </div>
                </div>
            </div>
            <!-- Invoice Items -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Invoice Items</span>
                </div>
                <div class="card-body" id="itemsContainer">
                    <!-- Dynamic items will be appended here -->
                </div>
            </div>
            <!-- Invoice Summary -->
            <div class="card mb-4">
                <div class="card-header">Invoice Summary</div>
                <div class="card-body row">
                    <!-- Left Side: Notes -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea id="notes" name="notes" rows="12" class="form-control"
                                placeholder="Additional comments or special instructions...">{{ old('notes', $isEdit ? $invoice->notes : '') }}</textarea>
                        </div>
                    </div>
                    <!-- Right Side: Summary Fields with Label + Input in Same Row -->
                    <div class="col-md-6">
                        @php
                            $fields = [
                                'totalAmountExcludingTax' => 'Total Invoice Amount Excluding Tax',
                                'totalAmountIncludingTax' => 'Total Invoice Amount Including Tax',
                                'totalSalesTax' => 'Total Sales Tax',
                                'totalfurtherTax' => 'Total Further Tax',
                                'totalextraTax' => 'Total Extra Tax',
                                'totalFedTax' => 'Total FED Tax',
                                'totalDiscount' => 'Total Discount',
                                'shipping_charges' => 'Shipping Charges',
                                'other_charges' => 'Other Charges',
                                'discount_amount' => 'Discount Amount',
                                'payment_status' => 'Payment Status',
                            ];
                            $requiredFields = ['totalAmountExcludingTax', 'totalAmountIncludingTax', 'totalSalesTax'];
                            $readOnlyFields = ['totalAmountExcludingTax', 'totalAmountIncludingTax', 'totalSalesTax' , 'totalfurtherTax' ,'totalextraTax' ,  'totalFedTax' , 'totalDiscount' ];
                            $paymentStatusOptions = ['Pending', 'Partially Paid', 'Fully Paid'];
                        @endphp
                        @foreach ($fields as $id => $label)
                            @php
                                $isRequired = in_array($id, $requiredFields);
                                $isReadOnly = in_array($id, $readOnlyFields);
                                // $value = $isEdit ? $invoice->$id ?? '' : ($id === 'payment_status' ? '' : '0');
                                $value = old(
                                    $id,
                                    $isEdit ? $invoice->$id ?? '' : ($id === 'payment_status' ? '' : '0'),
                                );
                            @endphp
                            <div class="mb-2 row align-items-center">
                                <label for="{{ $id }}"
                                    class="col-md-5 col-form-label {{ $isRequired ? 'required' : '' }}">{{ $label }}</label>
                                <div class="col-md-7">
                                    @if ($id === 'payment_status')
                                        <select id="{{ $id }}" name="{{ $id }}"
                                            class="form-control" {{ $isRequired ? 'required' : '' }}>
                                            <option value="">Select Status</option>
                                            @foreach ($paymentStatusOptions as $option)
                                                <option value="{{ $option }}"
                                                    {{ $value === $option ? 'selected' : '' }}>{{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input id="{{ $id }}" name="{{ $id }}" class="form-control"
                                            value="{{ $value }}" {{ $isRequired ? 'required' : '' }}  {{ $isReadOnly ? 'readonly' : '' }}/>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body row g-3">
                    <div class="col-md-12 text-end">
                        <button id="draftBtn" type="submit" class="btn btn-primary">Save As Draft</button>
                        <button id="submitBtn" type="submit" class="btn btn-primary">Post To FBR</button>
                    </div>
                </div>
            </div>
            @if (old('items'))
                <script nonce="{{ $nonce }}">
                    window.oldItems = @json(old('items'));
                </script>
            @endif
        </form>
    </div>
    <!-- Item Template (hidden) -->
    <template id="itemTemplate">
        <div class="item-group border rounded p-3 mb-4 position-relative">
            <button type="button" class="btn-close position-absolute end-0 top-0 remove-item"
                aria-label="Close"></button>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label required">Select Item/Service</label>
                    <select name="items[][item_id]" class="form-select item-select" required>
                        <option value="">--Choose Item--</option>
                        @foreach ($items as $i)
                            <option value="{{ $i->item_id }}" data-price="{{ $i->item_price }}"
                                data-tax="{{ $i->item_tax_rate }}" data-uom="{{ $i->item_uom }}"
                                data-hs="{{ $i->item_hs_code }}" data-description="{{ $i->item_description }}">
                                {{ $i->item_description }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label required">HS Code <i class="bi bi-info-circle" data-bs-toggle="tooltip"
                            title="Harmonized System Code — HS Code is applicable to manufacturer-cum-retailer issuing electronic invoice."></i></label>
                    <input type="text" name="items[][hsCode]" class="form-control" readonly required />
                </div>
                <div class="col-md-6">
                    <label class="form-label required">Product Description <i class="bi bi-info-circle"
                            data-bs-toggle="tooltip" title="A clear description of the service or product."></i></label>
                    <input type="text" name="items[][productDescription]" class="form-control" readonly required />
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Item Price</label>
                    <input type="number" min="0" step="0.01" name="items[][item_price]" class="form-control"
                        required readonly />
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Tax Rate in % <i class="bi bi-info-circle"
                            data-bs-toggle="tooltip"
                            title="Represents Sales Tax Rate. It matches SalesTaxApplicable."></i></label>
                    <input type="text" name="items[][rate]" class="form-control" required readonly />
                </div>
                <div class="col-md-3">
                    <label class="form-label required">UoM <i class="bi bi-info-circle" data-bs-toggle="tooltip"
                            title="Unit of Measure. E.g. Units, Boxes, Kg, or in this case Hours."></i></label>
                    <input type="text" name="items[][uoM]" class="form-control" required readonly />
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Quantity <i class="bi bi-info-circle" data-bs-toggle="tooltip"
                            title="Quantity being sold or delivered."></i></label>
                    <input type="number" min="0" step="0.01" name="items[][quantity]" class="form-control"
                        required />
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Total Value Excluding Tax <i class="bi bi-info-circle"
                            data-bs-toggle="tooltip"
                            title="Per unit value **excluding** sales tax. So for 2 units, subtotal = 2 × 1000 = 2000."></i></label>
                    <input type="number" min="0" step="0.01" name="items[][valueSalesExcludingST]"
                        class="form-control" readonly required />
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Total Value Including Tax <i class="bi bi-info-circle"
                            data-bs-toggle="tooltip"
                            title="Total value **including tax**. 18% tax on Rs. 2000 = 360, so total = 2000 + 360 = 2360"></i>
                    </label>
                    <input type="number" min="0" step="0.01" name="items[][totalValues]"
                        class="form-control" readonly required />
                </div>
                <div class="col-md-3">
                    <label class="form-label">Retail Price <i class="bi bi-info-circle" data-bs-toggle="tooltip"
                            title="If the item is price-controlled (e.g., FMCG), the notified retail price goes here. For services or custom billing, leave as 0."></i></label>
                    <input type="number" name="items[][fixedNotifiedValueOrRetailPrice]" class="form-control" />
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Sales Tax Applicable <i class="bi bi-info-circle"
                            data-bs-toggle="tooltip" title="Numeric value of applicable sales tax %."></i></label>
                    <input type="number" min="0" step="0.01" name="items[][SalesTaxApplicable]"
                        class="form-control" required />
                </div>
                <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-6"> 
                                <label class="form-label">Further Tax % </label>
                                <input type="number" step="0.01" name="items[][furtherTax_percentage]" class="form-control" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Further Tax <i class="bi bi-info-circle"
                                        data-bs-toggle="tooltip"
                                        title="If buyer is **unregistered**, FBR may require Further Tax (typically 3% or more). Optional but calculated if applicabl"></i></label>
                                <input type="number" name="items[][furtherTax]" class="form-control" />
                            </div>
                        </div>
                </div>
                <div class="col-md-3">

                 <div class="row">
                            <div class="col-md-6"> 
                                  <label class="form-label">Extra Tax % </label>
                    <input type="number" step="0.01" name="items[][extraTax_percentage]" class="form-control" />
                            </div>
                            <div class="col-md-6">
                               <label class="form-label">Extra Tax <i class="bi bi-info-circle" data-bs-toggle="tooltip"
                            title=" Only applies to specific industries — e.g., sugar, cement. Leave blank if not applicable."></i></label>
                    <input type="number" name="items[][extraTax]" class="form-control" />
                            </div>
                        </div>

                  
                </div>  
                <div class="col-md-3">
                    <label class="form-label">FED Payable %</label>
                    <input type="number" step="0.01" name="items[][fedPayable_percentage]" class="form-control" />
                </div>
                <div class="col-md-3">
                    <label class="form-label">FED Payable <i class="bi bi-info-circle"
                            data-bs-toggle="tooltip"
                            title="Federal Excise Duty — applicable in certain services/goods (e.g., telecom, beverages). 0 if not applicable."></i></label>
                    <input type="number" name="items[][fedPayable]" class="form-control" />
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Sale Type <i class="bi bi-info-circle" data-bs-toggle="tooltip"
                            title=" Important: Describes the nature of sale.Could be: 'Goods at reduced rate', 'Exempt', 'Zero-rated', 'Services', etc."></i></label>
                    <input type="text" name="items[][saleType]" class="form-control saleTypeInput"
                        placeholder="e.g. Goods at standard rate" required readonly />
                </div>
                <div class="col-md-3">
                    <label class="form-label">Discount <i class="bi bi-info-circle" data-bs-toggle="tooltip"
                            title="Any discount offered per item — reduces `valueSalesExcludingST`."></i></label>
                    <input type="number" name="items[][discount]" class="form-control" />
                </div>
                <div class="col-md-3">
                    <label class="form-label">SRO Schedule No <i class="bi bi-info-circle"
                            data-bs-toggle="tooltip"
                            title="Optional: Relevant only if item has special treatment via an SRO (Statutory Regulatory Order)"></i></label>
                    <input type="text" name="items[][sroScheduleNo]" class="form-control" />
                </div>
                <div class="col-md-3">
                    <label class="form-label">SRO Item Serial No  <i class="bi bi-info-circle"
                            data-bs-toggle="tooltip"
                            title="Optional: Used if your item appears in an SRO Schedule and has a specific serial number."></i></label>
                    <input type="text" name="items[][sroItemSerialNo]" class="form-control" />
                </div>
                
                <div class="col-md-3">
                    <label class="form-label required">Tax Withheld <i class="bi bi-info-circle" data-bs-toggle="tooltip"
                            title="If you're withholding tax on behalf of the seller (as a withholding agent), mention here Else 0."></i></label>
                    <input type="number" name="items[][SalesTaxWithheldAtSource]" class="form-control" required />
                </div>
                <div class="col-12 text-end">
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2 add-item">+ Add Another
                        Item</button>
                </div>
            </div>
        </div>
    </template>
    <script type="application/json" id="invoice-data">
    {!! json_encode([
        'isEdit' => $isEdit,
        'existingItems' => $invoice->items ?? [],
    ]) !!}
    </script>
    <script nonce="{{ $nonce }}">
        window.buyerId = "{{ $invoice->buyer_id ?? '' }}";
    </script>
    <script src="{{ asset('assets/js/customInvoice.js') }}"></script>
@endsection
