@extends('layouts.app')

@section('title', 'Products')
@section('page_title', 'Products')
@section('css')
@endsection

@section('content')
<div class="row py-2">
    <!-- Buttons and Forms -->
    <div class="col-md-3 mb-2">
        <a href="{{ route('product.create') }}" class="btn btn-primary btn-sm w-100">
            <i class="fas fa-plus-circle"></i> Create Product
        </a>
    </div>

    <div class="col-md-3 mb-2">
        <a href="{{ route('product.export', request()->all()) }}" class="btn btn-success btn-sm w-100">
            <i class="fas fa-file-export"></i> Export CSV
        </a>
    </div>

    <div class="col-md-6 mb-2">
        <form action="{{ route('product.import') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
            @csrf
            <input type="file" name="file" required class="form-control form-control-sm">
            <button type="submit" class="btn btn-primary btn-sm">Import Excel</button>
            <a href="{{ route('product.low') }}" class="btn btn-warning btn-sm">
                <i class="fas fa-exclamation-circle"></i> Low Stock
            </a>
        </form>
    </div>
</div>

<!-- Search Form -->
<div class="row mb-3">
    <div class="col-md-6">
        <form action="{{ route('product.search') }}" method="GET" class="d-flex align-items-center gap-2">
            <label for="cid" class="mb-0">Category:</label>
            <select name="cid" id="cid" class="form-select form-select-sm" style="width: auto;">
                <option value="all">All</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ request('cid') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>

            <label for="q" class="mb-0">Search:</label>
            <input type="text" name="q" id="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Enter name..." style="width: 200px;">

            <button type="submit" class="btn btn-info btn-sm">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="card mt-3">
    <div class="card-body">
        @component('coms.alert') @endcomponent

        <table class="table table-bordered table-sm">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Onhand</th>
                    <th>Alert</th>
                    <th>Unit</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $index => $p)
                    <tr>
                        <td>{{ $products->firstItem() + $index }}</td>
                        <td>{{ $p->code }}</td>
                        <td>{{ $p->name }}</td>
                        <td>{{ number_format($p->price, 2) }}</td>
                        <td>{{ $p->cname }}</td>
                        <td>{{ $p->onhand }}</td>
                        <td>{{ $p->alert }}</td>
                        <td>{{ $p->uname }}</td>
                        <td>
                            <a href="{{ route('product.detail', $p->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('product.edit', $p->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                            <a href="{{ route('product.delete', $p->id) }}" class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure you want to delete this product?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
