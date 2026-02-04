@extends('layouts.app')

@section('title', 'Edit User')
@section('page_title', 'Edit User')
@section('css')
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit User: {{ $user->name }}</h3>
    </div>
    <div class="card-body">
       <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Name -->
    <div class="form-group mb-3">
        <label for="name">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        @error('name')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <!-- Username -->
    <div class="form-group mb-3">
        <label for="username">Username</label>
        <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
        @error('username')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <!-- Email -->
    <div class="form-group mb-3">
        <label for="email">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        @error('email')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <!-- Role -->
    <x-form.select
        name="role_id"
        title="Role"
        required="required"
        :data="$roles"
        :selected="$user->role_id"
        option-label="name"
        option-value="id"
        row="col"
    />

    <!-- Language -->
    <div class="form-group mb-3">
        <label for="lang">Language</label>
        <select name="lang" class="form-control" required>
            <option value="en" {{ old('lang', $user->lang) == 'en' ? 'selected' : '' }}>English</option>
            <option value="kh" {{ old('lang', $user->lang) == 'kh' ? 'selected' : '' }}>Khmer</option>
            <option value="cn" {{ old('lang', $user->lang) == 'cn' ? 'selected' : '' }}>Chinese</option>
        </select>
        @error('lang')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <!-- Photo -->
    <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="photo" class="col-sm-3">Photo</label>
                        <div class="col-sm-9">
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*" onchange="preview(event)">
                            <div class="mt-2">
                                <img src="{{asset($user->photo)}}" alt="" id="img" width="150">
                            </div>
                            <div class="mt-2">
                                <button class="btn btn-primary btn-sm">
                                    <i class="fas fa-save"></i> Save
                                </button>
                                <a href="{{route('user.index')}}" class="btn btn-danger btn-sm">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                
            </form>
        </div>
    </div>
@endsection
@section('js')
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $('.select2').select2();
    });
    function preview(event)
    {
        var img = document.getElementById('img');
        img.src = URL.createObjectURL(event.target.files[0]);
    }
</script>
@endsection
