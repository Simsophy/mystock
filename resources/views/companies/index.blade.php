@extends('layouts.app')

@section('title', ' Company  ')

@section('content')

<h1> Company Info</h1>
@if(check('category', 'edit'))
<a href="{{ route('company.edit') }}" class="btn btn-sm btn-primary">Edit Company</a>

<form action="{{ route('company.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    @foreach($companies as $company)

    <table class="table table-bordered">
        <tbody>
           <tr>
    <th style="width: 240px;">Logo</th>
    <td>
        <img src="{{ $company->logo ? asset('storage/' . $company->logo) : asset('images/default-logo.png') }}" alt="logo" width="500" height="300" 
        style="display:block; margin-bottom:10px;">
        {{-- Removed file input for logo upload --}}
        {{-- <input type="file" name="logo" accept="image/*"> --}}
        @error('logo')<div class="text-danger">{{ $message }}</div>@enderror
    </td>
</tr>


            <tr>
                <th>Name</th>
                <td>
                    <input type="text" name="name" value="{{ old('name', $company->name) }}" class="form-control" required maxlength="255">
                    @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                </td>
            </tr>

            <tr>
                <th>Website</th>
                <td>
                    <input type="url" name="website" value="{{ old('website', $company->website) }}" class="form-control">
                    @error('website')<div class="text-danger">{{ $message }}</div>@enderror
                </td>
            </tr>

            <tr>
                <th>Phone</th>
                <td>
                    <input type="text" name="phone" value="{{ old('phone', $company->phone) }}" class="form-control" maxlength="20">
                    @error('phone')<div class="text-danger">{{ $message }}</div>@enderror
                </td>
            </tr>

            <tr>
                <th>Email</th>
                <td>
                    <input type="email" name="email" value="{{ old('email', $company->email) }}" class="form-control">
                    @error('email')<div class="text-danger">{{ $message }}</div>@enderror
                </td>
            </tr>

            <tr>
                <th>VAT/TIN</th>
                <td>
                    <input type="text" name="vattin" value="{{ old('vattin', $company->vattin) }}" class="form-control" maxlength="100">
                    @error('vattin')<div class="text-danger">{{ $message }}</div>@enderror
                </td>
            </tr>

            <tr>
                <th>Address</th>
                <td>
                    <textarea name="address" class="form-control" rows="3">{{ old('address', $company->address) }}</textarea>
                    @error('address')<div class="text-danger">{{ $message }}</div>@enderror
                </td>
            </tr>

            <tr>
                <th>Description</th>
                <td>
                    <textarea name="description" class="form-control" rows="5">{{ old('description', $company->description) }}</textarea>
                    @error('description')<div class="text-danger">{{ $message }}</div>@enderror
                </td>
            </tr>

            <tr>
                <th>Map URL</th>
                <td>
                    <input type="url" name="map_url" value="{{ old('map_url', $company->map_url) }}" class="form-control">
                    @error('map_url')<div class="text-danger">{{ $message }}</div>@enderror

                    @if($company->map_url)
                        <div class="map-responsive" style="margin-top: 10px;">
                            <iframe src="{{ $company->map_url }}" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    @endif
                </td>
            </tr>

          
        </tbody>
    </table>
</form>

<style>
    .map-responsive {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 aspect ratio */
        height: 0;
        overflow: hidden;
    }
    .map-responsive iframe {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        border: 0;
    }
</style>
@endforeach
@endsection
