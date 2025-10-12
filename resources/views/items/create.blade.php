@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <h2 class="mb-4 text-center">Add New Item / Service</h2>
        <form class="app-form needs-validation" novalidate method="POST" action="{{ route('items.store') }}">
            @csrf
            <div class="card mb-4">
                <div class="card-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label required">Item/Service Description</label>
                        <textarea name="item_description" class="form-control" placeholder="Enter Item/Service Description" required>{{ old('item_description') }}</textarea>
                        @error('item_description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">HS Code <i class="bi bi-info-circle" data-bs-toggle="tooltip"
                                title="Harmonized System Code — HS Code is applicable to manufacturer-cum-retailer issuing electronic invoice."></i></label>
                        <input type="text" name="item_hs_code" value="{{ old('item_hs_code') }}"
                            placeholder="Enter HS Code" class="form-control" required />
                        @error('item_hs_code')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Price</label>
                        <input type="number" step="0.01" name="item_price" value="{{ old('item_price') }}"
                            placeholder="Enter Price" class="form-control" required />
                        @error('item_price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Tax Rate in % (e.g. 18)</label>
                        <input type="number" step="0.01" name="item_tax_rate" value="{{ old('item_tax_rate') }}" class="form-control"
                            placeholder="Enter Tax Rate" required />
                        @error('item_tax_rate')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Unit of Measure (UOM) <i class="bi bi-info-circle"
                                data-bs-toggle="tooltip"
                                title="Unit of Measure. E.g. Units, Boxes, Kg, or in this case Hours."></i></label>
                        <input type="text" name="item_uom" value="{{ old('item_uom') }}" class="form-control"
                            placeholder="Enter Unit of Measure" required />
                        @error('item_uom')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Save Item</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
