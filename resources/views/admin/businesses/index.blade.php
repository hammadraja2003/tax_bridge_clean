@extends('admin.layouts.adminlayout')
@section('content')
    <div class="container-fluid">
        <div class="row table_setting">
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Business Details</h5>
                    </div>
                    <div class="card-body p-0">
                        {{--  Table --}}
                        <div id="myTable">
                            <div class="list-table-header d-flex justify-content-between align-items-center p-3">
                                <a href="{{ route('admin.register') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-plus fa-fw"></i>Add New Business
                                </a>
                                <form class="app-form app-icon-form" action="#">
                                    <div class="position-relative">
                                        <input type="search" class="form-control search" placeholder="Search..."
                                            aria-label="Search">
                                    </div>
                                </form>
                            </div>
                            <div class="app-scroll overflow-auto">
                                <table id="businessTable" class="table table-striped table-bordered m-0 align-middle">
                                    <thead>
                                        <tr class="app-sort">
                                            {{-- <th class="w-50">Name</th>
                                            <th class="w-50">NTN/CNIC</th>
                                            <th class="w-50">Users</th> --}}
                                            <th class="w-50">Business Info</th>
                                            <th class="w-50">Contact Details</th>
                                            <th class="w-50">Banking Info</th>
                                            <th class="w-50">Stats</th>
                                            <th class="w-50">Environment</th>
                                            {{-- <th class="w-50">Scenarios</th> --}}
                                            <th class="w-50">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="businessData">
                                        @forelse ($businesses as $b)
                                            <tr class="border-t">
                                                <!-- Business Info -->
                                                <td>
                                                    <div class="flex items-center gap-2">
                                                        @if (!empty($b->bus_logo))
                                                            @php
                                                                $disk = env(
                                                                    'FILESYSTEM_DISK',
                                                                    config('filesystems.default', 'uploads'),
                                                                );
                                                                $url = null;

                                                                try {
                                                                    if ($disk === 's3') {
                                                                        // Generate temporary signed URL (valid 1 hour)
                                                                        $url = Storage::disk($disk)->temporaryUrl(
                                                                            $b->bus_logo,
                                                                            now()->addHour(),
                                                                        );
                                                                    } else {
                                                                        // Local or public disks
                                                                        $url = Storage::disk($disk)->url(
                                                                            $b->bus_logo,
                                                                        );
                                                                    }
                                                                } catch (\Throwable $e) {
                                                                    $url = null;
                                                                }
                                                            @endphp

                                                            @if ($url)
                                                                <img src="{{ $url }}" alt="Company Logo"
                                                                    style="max-width: 100px; height: auto; border: 1px solid #ddd; padding: 5px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                                                            @else
                                                                <p class="text-danger">⚠️ Unable to load logo from storage
                                                                </p>
                                                            @endif
                                                        @else
                                                            <p class="text-muted">No logo uploaded</p>
                                                        @endif
                                                        <div>
                                                            <div class="font-semibold">{{ $b->bus_name }}</div>
                                                            <div class="text-sm text-gray-600">{{ $b->bus_ntn_cnic }}</div>
                                                            <div class="text-xs text-gray-500">{{ $b->bus_reg_num }}</div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Contact Details -->
                                                <td>
                                                    <div class="text-sm">
                                                        <div class="font-medium">{{ $b->bus_contact_person }}</div>
                                                        <div class="text-gray-600">{{ $b->bus_contact_num }}</div>
                                                        <div class="text-xs text-gray-500">{{ $b->bus_province }}</div>
                                                    </div>
                                                </td>

                                                <!-- Banking Info -->
                                                <td>
                                                    <div class="text-sm">
                                                        <div class="font-medium">{{ $b->bus_account_title }}</div>
                                                        <div class="text-gray-600">{{ $b->bus_account_number }}</div>
                                                        <div class="text-xs text-gray-500">{{ $b->bus_acc_branch_name }}
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Stats -->
                                                <td>
                                                    <div class="text-sm">
                                                        <div><span class="font-medium">Users:</span> {{ $b->users_count }}
                                                        </div>
                                                        <div><span class="font-medium">Scenarios:</span>
                                                            {{ $b->scenarios_count }}</div>
                                                    </div>
                                                </td>

                                                <!-- Environment -->
                                                <td>
                                                    <span
                                                        class="px-2 py-1 text-xs rounded {{ $b->fbr_env === 'sandbox' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                                        {{ strtoupper($b->fbr_env ?? 'N/A') }}
                                                    </span>
                                                </td>
                                                {{-- <td>{{ $b->bus_name }}</td>
                                                <td>{{ $b->bus_ntn_cnic }}</td>
                                                <td>{{ $b->users_count }}</td> --}}
                                                {{-- <td>{{ $b->scenarios_count }}</td> --}}
                                                <td>
                                                    <a href="{{ route('admin.businesses.show', \Illuminate\Support\Facades\Crypt::encryptString($b->bus_config_id)) }}"
                                                        class="btn btn-xs btn-outline-warning" title="View Business"
                                                        data-bs-toggle="tooltip" data-bs-placement="top">
                                                        <i class="ti ti-eye"></i>
                                                    </a>

                                                    <a href="{{ route('admin.businesses.create-user', \Illuminate\Support\Facades\Crypt::encryptString($b->bus_config_id)) }}"
                                                        class="btn btn-xs btn-outline-warning" title="Add User"
                                                        data-bs-toggle="tooltip" data-bs-placement="top">
                                                        <i class="ti ti-user"></i>
                                                    </a>

                                                    @if ($b->db_username == 'dummy' || $b->db_password == 'dummy')
                                                        <a href="{{ route('admin.db.clone.form') }}"
                                                            class="btn btn-xs btn-outline-primary" title="Clone Database"
                                                            data-bs-toggle="tooltip" data-bs-placement="top">
                                                            <i class="ti ti-database"></i>
                                                        </a>
                                                    @endif
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No Business found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="paginationtble-bottom">
                                    {{ $businesses->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
