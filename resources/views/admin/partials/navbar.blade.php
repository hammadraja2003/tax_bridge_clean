<nav class="dark-sidebar semi-nav">
    <div class="app-logo">
        <a class="logo d-none d-sm-inline-block" href="#">
            <img src="{{ businessLogo() }}" alt="Company Logo" class="dark-logo">
            <img src="{{ asset('assets/images/logo/favicon.ico.png') }}" alt="#" class="light-logo">
        </a>
        <span class="bg-light-light toggle-semi-nav iconsidebaropn">
            <i class="ti ti-chevrons-right f-s-20"></i>
        </span>
    </div>
    <div class="app-nav" id="app-simple-bar">
        <ul class="main-nav p-0 mt-2">
            {{-- Dashboard --}}
            <li class="no-sub">
                <a class="{{ request()->routeIs('admin.admin_dashboard') ? 'activeTab' : '' }}"
                    href="{{ route('admin.admin_dashboard') }}">
                    <i class="ti ti-home"></i> Dashboard
                </a>
            </li>
            {{-- Register --}}
            {{-- <li class="no-sub">
                <a class="{{ request()->routeIs('admin.register.*') ? 'activeTab' : '' }}"
                    href="{{ route('admin.register') }}">
                    <i class="ti ti-building"></i> Register
                </a>
            </li> --}}
            {{-- Businesses --}}
            <li class="no-sub">
                <a class="{{ request()->routeIs('admin.businesses.*') ? 'activeTab' : '' }}"
                    href="{{ route('admin.businesses.index') }}">
                    <i class="ti ti-building"></i> Businesses
                </a>
            </li>
            {{-- Logs --}}
            @php $logsActive = request()->routeIs('admin.logs.*'); @endphp
            <li class="no-sub">
                <a class="{{ $logsActive ? 'activeTab' : '' }}" href="{{ route('admin.logs.show') }}">
                    <i class="ti ti-notebook"></i> Logs
                </a>
            </li>
            {{-- Database Utilities --}}
            {{-- <li class="no-sub">
                <a class="{{ request()->routeIs('admin.db.clone.*') ? 'activeTab' : '' }}"
                    href="{{ route('admin.db.clone.form') }}">
                    <i class="ti ti-database"></i> Database Utilities
                </a>
            </li> --}}
            {{-- Logout --}}
            <li class="no-sub">
                <form class="logout-form" id="logout-form" action="{{ route('admin.admin_logout') }}" method="POST">
                    @csrf
                </form>
                <a href="#" id="logout-link"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
