@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<div class="page-content">
    <!--breadcrumb-->
    <div class="mb-3 page-breadcrumb d-none d-sm-flex align-items-center">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="p-0 mb-0 breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                </ol>
            </nav>
        </div>
        {{-- <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('add.user',) }}" class="btn btn-primary">Edit user</a>
            </div>
        </div> --}}
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="p-4 card-body">
            <h5 class="mb-4">Edit User</h5>
            <form id="myForm" method="POST" action="{{ route('update.user') }}" class="row g-3">
                @csrf
                <input type="hidden" value="{{ $user->id }}" class="form-control" name="id" id="id">
                <div class="col-md-6 form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" value="{{ $user->email }}" class="form-control" name="email" id="email">
                </div>
                <div class="col-md-6 form-group">
                    <label for="role" class="form-label">Role</label>
                    <select name="role" class="mb-3 form-select" aria-label="Default select example">
                        <option selected="" disabled>Open this select menu</option>
                        @foreach ($all_user->pluck('role')->unique() as $role)
                            <option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12">
                    <div class="gap-3 d-md-flex d-grid align-items-center">
                        <button type="submit" class="px-4 btn btn-primary">Edit user</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
