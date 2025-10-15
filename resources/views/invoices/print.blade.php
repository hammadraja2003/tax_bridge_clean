<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'TaxBridge | Invoicing Management System') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #000;
            margin: 20px;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: auto;
            box-sizing: border-box;
            padding: 10px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo img {
            width: 180px;
        }

        .top-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            /* margin-top: 10px; */
            gap: 20px;
        }

        .top-section {
            flex: 1;
        }

        .top-section h1 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        .invoice-table,
        .payment-table {
            width: 100%;
            border-collapse: collapse;
            /* margin-top: 20px; */
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #000;
            padding: 8px 12px;
            text-align: left;
        }

        .invoice-table th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .totals {
            margin-top: 4px;
            float: right;
            width: 300px;
        }

        .totals table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals td {
            padding: 6px 10px;
        }

        .totals .label {
            font-weight: bold;
        }

        .due-info {
            /* margin-top: 40px; */
        }

        .payment-advice {
            /* margin-top: 50px; */
            margin-top: 10px;
            border-top: 2px dashed #000;
            /* padding-top: 20px; */
            position: relative;
        }

        .payment-advice::before {
            content: "✂";
            position: absolute;
            top: -20px;
            left: 20px;
            background: #fff;
            padding: 0 5px;
            font-size: 25px;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            text-align: center;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>

<body onload="window.print()">
    <!-- Printable Area -->
    <div class="container" id="invoiceArea">
        <div class="header">
            <div class="logo">
                <img src="{{ businessLogo() }}" alt="Logo" style="height: 45px;">
            </div>
        </div>
        <div class="top-row">
            <div class="top-section"
                <h1>SALE TAX INVOICE</h1>
                <p><strong>{{ $invoice->buyer->byr_name }}</strong><br>
                    {{ $invoice->buyer->byr_address }}</p>
            </div>
            <div class="top-section">
                <p><strong>Invoice
                        Date:</strong><br>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}<br><br>
                    <strong>Invoice Number:</strong> <br>{{ $invoice->invoice_no }}<br><br>
                    @if (!empty($invoice->invoice_ref_no))
                        <p><strong>Reference:</strong> <br>{{ $invoice->invoice_ref_no }}</p>
                    @endif
            </div>
            <div class="top-section text-right">
                <p><strong>{{ $invoice->seller->bus_name }}</strong><br>
                    {{ $invoice->seller->bus_address }}<br>
                    Reg No: {{ $invoice->seller->bus_reg_num }}<br>
                    NTN:{{ $invoice->seller->bus_ntn_cnic }}</p>
            </div>
        </div>
        <!-- Invoice Items Table -->
        @php
            $subTotal = 0;
            $totalTax = 0;
            $totalExtraTax = 0;
            $totalFurtherTax = 0;
            $totalFed = 0;
            foreach ($invoice->details as $detail) {
                $price = floatval($detail->item->item_price ?? 0);
                $qty = floatval($detail->quantity ?? 0);
                $taxRate = floatval($detail->item->item_tax_rate ?? 0);
                $lineTotal = $price * $qty;
                $taxAmount = round($lineTotal * ($taxRate / 100), 2);
                $rowTotal = $lineTotal + $taxAmount;
                // Store results
                $detail->line_total = $lineTotal;
                $detail->tax_amount = $taxAmount;
                $detail->total_amount = $rowTotal;
                // Aggregate
                $subTotal += $lineTotal;
                $totalTax += $taxAmount;
                $totalExtraTax += $detail->extra_tax;
                $totalFurtherTax += $detail->further_tax;
                $totalFed += $detail->fed_payable;
            }
            $invoice->sub_total = round($subTotal, 2);
            $invoice->total_tax = round($totalTax, 2);
            $invoice->total = round($subTotal + $totalTax + $totalExtraTax + $totalFurtherTax + $totalFed, 2);
        @endphp
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Tax</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->details as $detail)
                    <tr>
                        <td>{{ $detail->item->name ?? $detail->item->item_description }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ number_format($detail->item->item_price ?? 0, 2) }}</td>
                        <td>{{ $detail->item->item_tax_rate ?? 15 }}%</td>
                        <td>{{ number_format($detail->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="display: flex; justify-content: space-between; margin-top:20px;">
            <!-- Due Info Section (Left Side) -->
            <div class="due-info" style="flex: 1; max-width: 45%; text-align: left;">
                <p>
                    <strong>Due Date:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}<br>
                    <strong>Bank Details:</strong><br>
                    <strong>Title:</strong> {{ $invoice->seller->bus_acc_branch_name }}<br>
                    <strong>Account No:</strong> {{ $invoice->seller->bus_account_number }}<br>
                    <strong>IBAN:</strong> {{ $invoice->seller->bus_IBAN }}<br>
                    <strong>SWIFT CODE:</strong> {{ $invoice->seller->bus_swift_code }}<br>
                    <strong>Branch CODE:</strong> {{ $invoice->seller->bus_acc_branch_code }}
                </p>
            </div>
            <!-- Totals Section (Right Side) -->
            <div class="totals" style="flex: 1; max-width: 45%; text-align: right;">
                <table>
                    <tr>
                        <td class="label">Subtotal</td>
                        <td class="text-right">{{ number_format($invoice->sub_total, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Sales Tax</td>
                        <td class="text-right">{{ number_format($invoice->total_tax, 2) }}</td>
                    </tr>
                    @if ($totalExtraTax > 0)
                        <tr>
                            <td class="label">Extra Tax</td>
                            <td class="text-right">{{ number_format($totalExtraTax, 2) }}</td>
                        </tr>
                    @endif
                    @if ($totalFurtherTax > 0)
                        <tr>
                            <td class="label">Further Tax</td>
                            <td class="text-right">{{ number_format($totalFurtherTax, 2) }}</td>
                        </tr>
                    @endif
                    @if ($totalFed > 0)
                        <tr>
                            <td class="label">FED </td>
                            <td class="text-right">{{ number_format($totalFed, 2) }}</td>
                        </tr>
                    @endif
                    <tr style="border-top: 2px solid #000;">
                        <td class="label"><strong>Grand Total</strong></td>
                        <td class="text-right"><strong>{{ number_format($invoice->total, 2) }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- @php
            $disk = env('FILESYSTEM_DISK', config('filesystems.default', 'uploads'));

            // Public URLs (for <img src>)
            $qrCodeUrl = $invoice->qr_code ? Storage::disk($disk)->url($invoice->qr_code) : null;

            $logoUrl = asset('assets/fbr-digital-invoicing-logo.png');
        @endphp

        @if ($invoice->is_posted_to_fbr == 1)
            <div style="margin-top: 30px; text-align: right;">
               
                <img src="{{ $logoUrl }}" alt="FBR Digital Invoicing System Logo"
                    style="width:1in; height:1in; object-fit:contain; margin-left:10px;">

                @if ($qrCodeUrl)
                    <img src="{{ $qrCodeUrl }}" alt="QR Code"
                        style="width:1in; height:1in; object-fit:contain; margin-left:10px;">
                @endif

             
                @if (!empty($invoice->fbr_invoice_number))
                    <p style="margin-top: 5px; font-weight: bold;">
                        FBR Invoice #: {{ $invoice->fbr_invoice_number }}
                    </p>
                @endif
            </div>
        @endif --}}

        {{-- @php
            $disk = env('FILESYSTEM_DISK', config('filesystems.default', 'uploads'));

            // Try to generate QR code URL safely
            try {
                $qrCodeUrl = !empty($invoice->qr_code) ? Storage::disk($disk)->url($invoice->qr_code) : null;
            } catch (\Throwable $e) {
                $qrCodeUrl = null;
            }

            // Static FBR logo from assets
            $logoUrl = asset('assets/fbr-digital-invoicing-logo.png');
        @endphp

        @if ($invoice->is_posted_to_fbr == 1)
            <div style="margin-top: 30px; text-align: right;">
              
                <img src="{{ $logoUrl }}" alt="FBR Digital Invoicing System Logo"
                    style="width:1in; height:1in; object-fit:contain; margin-left:10px;">

                @if ($qrCodeUrl)
                    <img src="{{ $qrCodeUrl }}" alt="QR Code"
                        style="width:1in; height:1in; object-fit:contain; margin-left:10px;">
                @else
                    <p style="color:red; margin-top:5px;">⚠️ QR Code unavailable</p>
                @endif

                @if (!empty($invoice->fbr_invoice_number))
                    <p style="margin-top: 5px; font-weight: bold;">
                        FBR Invoice #: {{ $invoice->fbr_invoice_number }}
                    </p>
                @endif
            </div>
        @endif --}}

        @php
            $disk = env('FILESYSTEM_DISK', config('filesystems.default', 'uploads'));

            try {
                if (!empty($invoice->qr_code)) {
                    // ✅ Use temporaryUrl for S3, url() for local/public
                    if ($disk === 's3') {
                        $qrCodeUrl = Storage::disk($disk)->temporaryUrl($invoice->qr_code, now()->addMinutes(5));
                    } else {
                        $qrCodeUrl = Storage::disk($disk)->url($invoice->qr_code);
                    }
                } else {
                    $qrCodeUrl = null;
                }
            } catch (\Throwable $e) {
                $qrCodeUrl = null;
            }

            // Static FBR logo
            $logoUrl = asset('assets/fbr-digital-invoicing-logo.png');
        @endphp

        @if ($invoice->is_posted_to_fbr == 1)
            <div style="margin-top: 30px; text-align: right;">
                {{-- FBR Logo --}}
                <img src="{{ $logoUrl }}" alt="FBR Digital Invoicing System Logo"
                    style="width:1in; height:1in; object-fit:contain; margin-left:10px;">

                {{-- QR Code --}}
                @if ($qrCodeUrl)
                    <img src="{{ $qrCodeUrl }}" alt="QR Code"
                        style="width:1in; height:1in; object-fit:contain; margin-left:10px;">
                @else
                    <p style="color:red; margin-top:5px;">⚠️ QR Code unavailable</p>
                @endif

                {{-- FBR Invoice Number --}}
                @if (!empty($invoice->fbr_invoice_number))
                    <p style="margin-top: 5px; font-weight: bold;">
                        FBR Invoice #: {{ $invoice->fbr_invoice_number }}
                    </p>
                @endif
            </div>
        @endif



        <!-- Payment Advice -->
        <div class="payment-advice">
            <h2>PAYMENT ADVICE</h2>
            <div style="display: flex; justify-content: space-between; gap: 20px;">
                <div style="width: 48%;">
                    <p><strong>To:</strong> {{ $invoice->seller->bus_name }}<br>
                        {{ $invoice->seller->bus_address }}<br>
                        Company Reg No: {{ $invoice->seller->bus_reg_num }}<br>
                        NTN: {{ $invoice->seller->bus_ntn_cnic }}</p>
                </div>
                <div style="width: 48%;">
                    <table style="width: 100%;">
                        <tr>
                            <td><strong>Customer</strong></td>
                            <td>{{ $invoice->buyer->byr_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Invoice No.</strong></td>
                            <td>{{ $invoice->invoice_no }}</td>
                        </tr>
                        <tr>
                            <td><strong>Amount Due</strong></td>
                            <td>{{ number_format($invoice->total, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Due Date</strong></td>
                            <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Amount Enclosed</strong></td>
                            <td>________________________</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="font-size: 12px; padding-top: 2px;">Enter the amount you are paying above</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        </br>
        <!-- Footer -->
        <div class="footer" style="text-align: center;">
            Company Registration No: {{ $invoice->seller->bus_reg_num }} | Registered Office:
            {{ $invoice->seller->bus_address }}
        </div>
    </div>
</body>

</html>
