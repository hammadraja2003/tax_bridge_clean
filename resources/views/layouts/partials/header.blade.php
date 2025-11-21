<header class="header-main">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 d-flex align-items-center header-left">
                                <span class="header-toggle me-3">
                                    <i class="ti ti-category"></i>
                                </span>
                                <div class="header-searchbar">
                                    @if (getFbrEnv() === 'sandbox')
                                        <span class="badge bg-warning me-2">Sandbox Environment</span>
                                    @else
                                        <span class="badge bg-success me-2">Production Environment</span>
                                    @endif
                                </div>
                                @php
                                    use Carbon\Carbon;
                                    $trialDaysLeft = 0;
                                    $isTrial = session('is_trial');
                                    $trialEndDate = session('trial_end_date');
                                    if ($isTrial && $trialEndDate) {
                                        $today = Carbon::now();
                                        $trialEnd = Carbon::parse($trialEndDate);
                                        $trialDaysLeft = (int) $today->diffInDays($trialEnd, false);
                                        if ($trialDaysLeft < 0) {
                                            $trialDaysLeft = 0;
                                        }
                                    }
                                @endphp
                                @if ($trialDaysLeft > 0)
                                    <span class="badge bg-warning me-2">Trial Days Left: {{ $trialDaysLeft }}</span>
                                @endif
                            </div>
                            <div class="col-6 d-flex align-items-center justify-content-end header-right">
                                <ul class="d-flex align-items-center">
                                    <li class="header-search">
                                    </li>
                                    <li class="header-apps">
                                        <div class="flex-grow-1 ps-2">
                                            <h6 class="mb-0 text-uppercase">
                                                @if (auth()->check())
                                                    {{ auth()->user()->name }}
                                                @endif
                                            </h6>
                                        </div>
                                    </li>
                                    <li class="header-profile">
                                        <div class="flex-shrink-0 dropdown">
                                            <a href="#" class="d-block head-icon pe-0" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <img src=" {{ asset('assets/images/avtar/men.png') }}" alt="mdo"
                                                    class="rounded-circle h-35 w-35">
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end header-card border-0 px-2">
                                                <li class="dropdown-item d-flex align-items-center p-2">
                                                    <span class="h-35 w-35 d-flex-center b-r-50 position-relative">
                                                        <img src="{{ asset('assets/images/avtar/men.png') }}"
                                                            alt="" class="img-fluid b-r-50">
                                                        <span
                                                            class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle animate__animated animate__fadeIn animate__infinite animate__fast"></span>
                                                    </span>
                                                    <div class="flex-grow-1 ps-2">
                                                        <h6 class="mb-0">
                                                            @if (auth()->check())
                                                                {{ auth()->user()->name }}
                                                            @endif
                                                        </h6>
                                                        <p class="f-s-12 mb-0 text-secondary">Online</p>
                                                    </div>
                                                </li>
                                                <li class="app-divider-v dotted py-1"></li>
                                                @php
                                                    use Illuminate\Support\Facades\Crypt;
                                                    $encryptedId = auth()->check()
                                                        ? Crypt::encrypt(auth()->user()->id)
                                                        : null;
                                                @endphp
                                                @if (auth()->check())
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center"
                                                            href="{{ route('edit-profile', ['id' => $encryptedId]) }}">
                                                            <i class="ti ti-user-circle pe-1 f-s-18"></i> Profile
                                                        </a>
                                                    </li>
                                                @endif
                                                <li class="app-divider-v dotted py-1"></li>
                                                <li>
                                                    <form class="logout-form" method="POST"
                                                        action="{{ route('logout') }}">
                                                        @csrf
                                                        <button type="submit"
                                                            class="dropdown-item d-flex align-items-center text-danger border-0 bg-transparent w-100 text-start">
                                                            <i class="ti ti-logout pe-1 f-s-18"></i> Log Out
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
