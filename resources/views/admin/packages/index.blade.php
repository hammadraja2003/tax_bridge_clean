@extends('admin.layouts.adminlayout')
@section('content')
<div class="container-fluid">
    <div class="row table_setting">
        <div class="col-xxl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Packages</h5>
                    <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">
                        <i class="fa-solid fa-plus fa-fw"></i> Add New Package
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="app-scroll overflow-auto">
                        <table class="table table-striped table-bordered m-0 align-middle">
                            <thead>
                                <tr class="app-sort">
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Billing Cycle</th>
                                    <th>Features</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($packages as $package)
                                <tr>
                                    <td>{{ $package->package_name }}</td>
                                    <td>{{ number_format($package->package_price, 2) }}</td>
                                    <td>{{ ucfirst($package->package_billing_cycle) }}</td>
                                    <td>
                                        @foreach ($package->features as $f)
                                            <span class="badge bg-info">{{ $f->feature_key }}: {{ $f->limit_value }} {{ $f->limit_type }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.packages.edit', $package->package_id) }}" class="btn btn-xs btn-outline-warning">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.packages.destroy', $package->package_id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-outline-danger" onclick="return confirm('Are you sure?')">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No Packages found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="paginationtble-bottom mt-2">
                        {{ $packages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
