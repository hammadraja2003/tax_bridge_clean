@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="row project_dashboard">
            <!-- Cards -->
            <div class="col-md-6 col-lg-3">
                <div class="card project-cards">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <h6>Total Clients
                                <!-- <span class="badge text-success">+3.1%</span> -->
                            </h6>
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <h4 class="text-success f-w-600 counting" data-count="{{ $totalClients }}">{{ $totalClients }}
                                </h4>
                                <!-- <p class="m-0 text-secondary">All Clients This Month</p> -->
                            </div>
                        </div>
                        <div class="project-card-icon project-success bg-light-success h-55 w-55 d-flex-center b-r-100">
                            <i class="ti ti-users f-s-30 mb-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card project-cards">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <h6>Total Invoices
                                <!-- <span class="badge text-warning">0.20%</span> -->
                            </h6>
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <h4 class=" text-warning f-w-600 counting" data-count="{{ $totalInvoices }}">
                                    {{ $totalInvoices }}</h4>
                                <!-- <p class="m-0 text-secondary">Total Invoices This Month</p> -->
                            </div>
                        </div>
                        <div class="project-card-icon project-secondary bg-light-warning h-55 w-55 d-flex-center b-r-100">
                            <i class="ti ti-file-invoice f-s-30 mb-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card project-cards">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <h6>Total FBR Posted<span class="badge text-success">{{ $fbrpostedPercentage }}%</span></h6>
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <h4 class="text-success f-w-600 counting" data-count="{{ $fbrPostedInvoices }}">
                                    {{ $fbrPostedInvoices }}</h4>
                                <!-- <p class="m-0 text-secondary">Posted This Month</p> -->
                            </div>
                        </div>
                        <div class="project-card-icon project-success bg-light-success h-55 w-55 d-flex-center b-r-100">
                            <i class="ti ti-file-upload f-s-30 mb-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card project-cards">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <h6 class="text-secondary">Total Draft <span
                                    class="badge text-danger">{{ $draftPercentage }}%</span></h6>
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <h4 class=" text-danger f-w-600 counting inline" data-count={{ $draftInvoices }}>
                                    {{ $draftInvoices }}</h4>
                                <!-- <p class="m-0 text-secondary">Finished This Month</p> -->
                            </div>
                        </div>
                        <div class="project-card-icon project-primary bg-light-danger h-60 w-60 d-flex-center b-r-100">
                            <i class="ti ti-browser-check f-s-36 mb-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Cards end -->
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Top Five Clients - Revenue Basis</h5>
                    </div>
                    <div class="card-body">
                        <div id="pie1" style="min-height: 340px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Top Five Services - Revenue Basis</h5>
                    </div>
                    <div class="card-body">
                        <div id="pie2"></div>
                    </div>
                </div>
            </div>
            <!-- Basic Column Chart start -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Month-wise Tax Details</h5>
                    </div>
                    <div class="card-body">
                        <div id="column1"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Month-wise Invoice Details</h5>
                    </div>
                    <div class="card-body">
                        <div id="column4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script nonce="{{ $nonce }}">
            var salesTaxData = @json($salesTaxData ?? []);
            var furtherTaxData = @json($furtherTaxData ?? []);
            var extraTaxData = @json($extraTaxData ?? []);
            var monthLabels = @json($monthlyLabels ?? []);
        </script>
        <script nonce="{{ $nonce }}">
            const invoiceMonthlyStats = @json($invoiceMonthlyStats);
        </script>
        <script nonce="{{ $nonce }}">
            window.topClientData = {
                names: @json($topClientNames),
                totals: @json($topClientTotals),
            };
        </script>
        <script nonce="{{ $nonce }}">
            window.topServicesRevenueData = {
                names: @json($topServiceNamesRevenue),
                totals: @json($topServiceTotalsRevenue),
                percentages: @json($topServicePercentagesRevenue),
            };
        </script>
        <!-- js-->
        <script src="{{ asset('assets/js/pie_charts.js') }}"></script>
        <script src="{{ asset('assets/js/column.js') }}"></script>
    @endpush
@endsection
