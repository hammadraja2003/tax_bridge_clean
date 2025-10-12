@extends('layouts.admin')
@section('content')
    @include('layouts.partials.errors')
    <div class="container-fluid">
        <h2 class="mb-4 text-center">Edit Item / Service</h2>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form class="app-form needs-validation" method="POST" action="{{ route('items.update', $item->item_id) }}">
            @csrf
            <div class="card mb-4">
                <div class="card-body row g-3">
                    {{-- Item Description --}}
                    <div class="col-md-6">
                        <label class="form-label required">Item/Service Description</label>
                        <input type="text" name="item_description" placeholder="Enter Item/Service Description"
                            value="{{ old('item_description', $item->item_description) }}"
                            class="form-control @error('item_description') is-invalid @enderror" required>
                        @error('item_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- HS Code --}}
                    <div class="col-md-6">
                        <label class="form-label required">
                            HS Code
                            <i class="bi bi-info-circle" data-bs-toggle="tooltip"
                                title="Harmonized System Code — HS Code is applicable to manufacturer-cum-retailer issuing electronic invoice."></i>
                        </label>
                        <input type="text" name="item_hs_code" placeholder="Enter HS Code" value="{{ old('item_hs_code', $item->item_hs_code) }}"
                            class="form-control @error('item_hs_code') is-invalid @enderror" required>
                        @error('item_hs_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Price --}}
                    <div class="col-md-6">
                        <label class="form-label required">Price</label>
                        <input type="number" step="0.01" name="item_price" placeholder="Enter Price"
                            value="{{ old('item_price', $item->item_price) }}"
                            class="form-control @error('item_price') is-invalid @enderror" required>
                        @error('item_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Tax Rate --}}
                    <div class="col-md-6">
                        <label class="form-label required">Tax Rate in % (e.g. 18)</label>
                        <input type="number" name="item_tax_rate" placeholder="Enter Tax Rate" value="{{ old('item_tax_rate', $item->item_tax_rate) }}"
                            class="form-control @error('item_tax_rate') is-invalid @enderror" required>
                        @error('item_tax_rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Unit of Measure --}}
                    <div class="col-md-6">
                        <label class="form-label required">
                            Unit of Measure (UOM)
                            <i class="bi bi-info-circle" data-bs-toggle="tooltip"
                                title="Unit of Measure. E.g. Units, Boxes, Kg, or in this case Hours."></i>
                        </label>
                        <input type="text" name="item_uom" placeholder="Enter Unit of Measure" value="{{ old('item_uom', $item->item_uom) }}"
                            class="form-control @error('item_uom') is-invalid @enderror" required>
                        @error('item_uom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Global Error (if needed) --}}
                    @if ($errors->has('error'))
                        <div class="col-12">
                            <div class="alert alert-danger">
                                {{ $errors->first('error') }}
                            </div>
                        </div>
                    @endif
                    {{-- Submit Button --}}
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Update Item</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
