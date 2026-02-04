@extends('layouts.app')

@section('title', 'Categories')
@section('page_title', 'Categories')
@section('css')
@endsection

@section('content')
<p class="my-1">
  @if(check('category', 'insert'))
    <a href="{{ route('category.create') }}" class="btn btn-success btn-sm">
        <i class="fa fa-plus-circle"></i> Create
    </a>
@endif

        
   @if(Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


</p>


<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>No.</th>
            <th>Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $i => $cat)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $cat->name }}</td>
                <td>
                  @if(check('category', 'edit'))
                    <a href="{{ route('category.edit', $cat->id) }}" class="btn btn-success btn-xs">
                        <i class="fas fa-edit" title="Edit"></i> Edit
                    </a>
                       @if(check('category', 'delete'))
                        <a href="{{ route('category.delete', $cat->id) }}" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('You want to delete?')">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                  @endif
                </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
@section('js')
@endsection
@endsection
