@extends('layouts.app')

@section('title', 'Low Stock Products')
@section('page_title', 'Low Stock Products')
@section('css')

@endsection
@section('content')
<div class="card mt-3">
    <div class="card-header bg-warning text-white">
        <strong>Low Stock Products</strong>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>On Hand</th>
                    <th>Alert</th>
                    <th>Unit</th>
                </tr>
            </thead>
         <tbody>
    @foreach ($products as $index => $p)
    <tr>
        <td>{{ $products->firstItem() + $index }}</td>
        <td>{{ $p->code }}</td>
        <td>{{ $p->name }}</td>
        <td>{{ number_format($p->price) }}</td>
        <td>{{ $p->cname }}</td>
        <td>{{ $p->onhand }}</td>
        <td>{{ $p->alert }}</td>
        <td>{{ $p->uname }}</td>
    </tr>
    @endforeach
</tbody>

        </table>

        <div class="mt-2">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
