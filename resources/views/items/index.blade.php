@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="row table_setting">
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Service Details</h5>
                    </div>
                    <div class="card-body p-0">
                        <div id="myTable">
                            <div class="list-table-header d-flex justify-content-between align-items-center p-3">
                                <a href="{{ route('items.create') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-plus fa-fw"></i>Add New Item / Services
                                </a>
                                <form class="app-form app-icon-form" action="#">
                                    <div class="position-relative">
                                        <input type="search" class="form-control search" placeholder="Search..."
                                            aria-label="Search">
                                    </div>
                                </form>
                            </div>
                            <div class="app-scroll overflow-auto">
                                <table id="projectTableT" class="table table-striped table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>HS Code</th>
                                            <th>Price</th>
                                            <th>Tax Rate</th>
                                            <th>UOM</th>
                                            <th>Tempered</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list" id="t-data">
                                        @forelse  ($items as $item)
                                            <tr @if ($item->tampered) class="table-warning" @endif>
                                                <td class="employee">{{ $item->item_description }}</td>
                                                <td class="email">{{ $item->item_hs_code }}</td>
                                                <td class="contact">{{ $item->item_price }}</td>
                                                <td class="status">{{ $item->item_tax_rate }}</td>
                                                <td>{{ $item->item_uom }}</td>
                                                <td>
                                                    @if ($item->tampered)
                                                        <span class="text-danger fw-bold">⚠ Data tampered!</span>
                                                    @else
                                                        <span class="text-success">OK</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('items.edit', Crypt::encryptString($item->item_id)) }}"
                                                        class="btn btn-xs btn-outline-warning">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                    <form action="{{ route('items.delete', $item->item_id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="btn btn-xs btn-outline-danger">
                                                            <i class="ti ti-trash f-s-20"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No Items found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="paginationtble-bottom">   
                                        {{ $items->links() }} 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
