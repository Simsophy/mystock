@extends('layouts.app')

@section('title', 'Users')
@section('page_title', 'User List')

@section('content')

<!-- Add User Button -->
<a href="{{ route('user.create') }}" class="btn btn-success mb-3">
    <i class="fas fa-plus-circle"></i> Add User
</a>

<!-- Flash Messages -->
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('fail'))
    <div class="alert alert-danger">{{ session('fail') }}</div>
@endif

<!-- Users Table -->
<table class="table table-bordered table-hover table-striped">
    <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>Logo</th>
            <th>Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role_Id</th>
            <th>Role</th>
            <th>Language</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td>{{ $loop->iteration }}</td>
            
            <td>
              

   


            </td>
            
            <td>{{ $user->name }}</td>
            <td>{{ $user->username }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role_id}}</td>
             <td>{{ $user->role_name ?? 'No Role' }}</td>

            <td>{{ $user->lang}}</td>
            
            <td>
                <!-- Edit Button -->
                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i> 
                </a>

      <a href="{{ route('user.delete', $user->id) }}" 
   onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">
   Delete
</a>



                <!-- Change Password Button -->
                <button type="button" class="btn btn-sm btn-primary" onclick="togglePasswordForm({{ $user->id }})">
                    <i class="fas fa-key"></i> 
                </button>

                <!-- Inline Change Password Form -->
               <form action="{{ route('user.change-password', $user->id) }}" method="POST" 
      class="password-form mt-1" id="password-form-{{ $user->id }}" style="display:none;">
    @csrf
    <input type="password" name="new_password" placeholder="New password" required 
           class="form-control form-control-sm d-inline-block" style="width:150px;">
    <input type="password" name="new_password_confirmation" placeholder="Confirm password" required 
           class="form-control form-control-sm d-inline-block" style="width:150px;">

    <button type="submit" class="btn btn-sm btn-success">
        <i class="fas fa-save"></i> Save
    </button>

    <a href="{{ route('user.index') }}" class="btn btn-sm btn-secondary">
        Cancel
    </a>
</form>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>



@endsection
