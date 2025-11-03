@extends('layouts.admin')
@section('content')
    <div class="container">
        <h2 class="mb-4">Import Invoices from Excel</h2>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>There were issues with your file:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mt-4 text-right" style="text-align: right;">
            <p>
                <strong style="color: red;">
                    <i class="fa fa-download" aria-hidden="true"></i> Download Sample Template
                </strong>
                <a href="{{ asset('assets/fbr_invoice_template.xlsx') }}" target="_blank"
                    style="color: red; text-decoration: none; margin-left: 5px;">
                    Click Here
                </a>
            </p>
        </div>
        <form action="{{ route('invoices.import.process') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="excel_file" class="form-label">Upload Excel File (.xlsx)</label>
                <input type="file" name="excel_file" id="excel_file" class="form-control" accept=".xlsx" required>
            </div>
            <div class="mb-3">
                <label for="send_to_fbr" class="form-label">Send to FBR after saving?</label>
                <select name="send_to_fbr" id="send_to_fbr" class="form-select">
                    <option value="1">Yes, send</option>
                    <option value="0" selected>No, just save as draft</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Import Invoices</button>
        </form>
    </div>
@endsection
