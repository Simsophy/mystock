@extends('layouts.app')

@section('title', 'Roles')

@section('page_title', 'Roles')

@section('content')
<p class="my-2">
    <a href="{{ route('role.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus-circle"></i> Create Role
    </a>
</p>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        @if($roles->count() > 0)
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
<tr>
    <td>{{ $role->id }}</td>
    <td>{{ $role->name }}</td> <td>
        </td>

                    <td>
                        <a href="{{ route('permission.index', $role->id) }}" class="btn btn-info btn-xs">
                            <i class="fas fa-lock"></i> Permissions
                        </a>
                        <a href="{{ route('role.edit', $role->id) }}" class="btn btn-success btn-xs">
    <i class="fas fa-edit"></i> Edit
</a>


                        <form action="{{ route('role.destroy', $role->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-xs"><i class="fas fa-trash"></i> Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>No roles found.</p>
        @endif
    </div>
</div>
@endsection
