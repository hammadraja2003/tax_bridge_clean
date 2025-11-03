<nav class="dark-sidebar semi-nav">
    <div class="app-logo">
        <a class="logo d-none d-sm-inline-block" href="#">
            <img src="{{ businessLogo() }}" alt="Company Logo" class="dark-logo">
            <img src="{{ asset('assets/images/logo/favicon.ico.png') }}" alt="#" class="light-logo">
        </a>
        <span class="bg-light-light toggle-semi-nav">
            <i class="ti ti-chevrons-right f-s-20"></i>
        </span>
    </div>
    <div class="app-nav" id="app-simple-bar">
        <ul class="main-nav p-0 mt-2">
            <li class="no-sub">
                <a class="" href="/dashboard">
                    <i class="ti ti-home"></i> dashboard
                    <span class="badge text-bg-success badge-notification ms-2"></span>
                </a>
            </li>
            <li class="no-sub">
                @php
                    $isInvoiceRoute = request()->routeIs('invoices.*');
                    $isCompanyeRoute = request()->routeIs('company.*');
                    $isBuyersRoute = request()->routeIs('buyers.*');
                    $isItemsRoute = request()->routeIs('items.*');
                    $isActivityRoute = request()->routeIs('activity.*');
                    $isAuditRoute = request()->routeIs('audit_logs.*');
                    $isFbrErrorRoute = request()->routeIs('fbr.errors');
                    $isFbrViewRoute = request()->routeIs('fbr.view');
                    $isImportRoute = request()->routeIs('invoices.import.*');
                @endphp
                <a class="{{ $isInvoiceRoute ? 'activeTab' : '' }}" href="{{ route('invoices.index') }}">
                    <i class="ti ti-chart-treemap"></i>Invoices
                </a>
            </li>
            <li>
                <a class="" href="#maps" data-bs-toggle="collapse" aria-expanded="false">
                    <i class="fa-solid fa-brands fa-connectdevelop fa-fw"></i>Settings
                </a>
                <ul class="collapse" id="maps">
                    <li>
                        <a class="{{ $isCompanyeRoute ? 'activeTab' : '' }}"
                            href="{{ route('company.configuration') }}">Configuration</a>
                    </li>
                    <li>
                        <a class="{{ $isBuyersRoute ? 'activeTab' : '' }}"
                            href="{{ route('buyers.index') }}">Clients</a>
                    </li>
                    <li>
                        <a class="{{ $isItemsRoute ? 'activeTab' : '' }}" href="{{ route('items.index') }}">Items /
                            Services</a>
                    </li>
                    <li>
                        <a class="{{ $isActivityRoute ? 'activeTab' : '' }}"
                            href="{{ route('activity.logs') }}">Activity
                            Logs</a>
                    </li>
                    <li>
                        <a class="{{ $isAuditRoute ? 'activeTab' : '' }}" href="{{ route('audit_logs.index') }}">Audit
                            Logs</a>
                    </li>
                    <li>
                        <a class="{{ $isFbrErrorRoute ? 'activeTab' : '' }}" href="{{ route('fbr.errors') }}">
                            Fbr Posting Errors
                        </a>
                    </li>
                    <li>
                        <a class="{{ $isFbrViewRoute  ? 'activeTab' : '' }}" href="{{ route('fbr.view') }}">
                            Fbr Lookups
                        </a>
                    </li>
                    {{-- <li>
                        <a class="{{ $isImportRoute  ? 'activeTab' : '' }}"  href="{{ route('invoices.import.form') }}">
                            Import Invoices
                        </a>
                    </li> --}}
                </ul>
            </li>
            <li class="no-sub">
                <form class="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                </form>
                <a href="#" id="logout-link">
                    <i class="ti ti-logout pe-1 f-s-20"></i> Logout
                </a>
            </li>
        </ul>
    </div>
    <div class="menu-navs">
        <span class="menu-previous"><i class="ti ti-chevron-left"></i></span>
        <span class="menu-next"><i class="ti ti-chevron-right"></i></span>
    </div>
</nav>
