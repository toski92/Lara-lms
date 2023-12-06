@extends('frontend.dashboard.user_dashboard')
@section('user_dashboard')
@php
    $user = Auth::user();
    $profile_img = (empty($user->photo)) ? asset('backend/assets/images/avatars/avatar-2.png') : asset("upload/$user->photo")
@endphp
<div class="breadcrumb-content d-flex flex-wrap align-items-center justify-content-between mb-5">
    <div class="media media-card align-items-center">
        <div class="media-img media--img media-img-md rounded-full">
            <img class="rounded-full" src="{{ $profile_img }}" alt="Student thumbnail image">
        </div>
        <div class="media-body">
            <h2 class="section__title fs-30">Howdy, {{ $user->name }}</h2>
        </div><!-- end media-body -->
    </div><!-- end media -->
</div><!-- end breadcrumb-content -->
<div class="tab-pane fade show active" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
    <div class="setting-body">
        <h3 class="fs-17 font-weight-semi-bold pb-4">Edit Profile</h3>

        <form method="post" enctype="multipart/form-data" action="{{ route('profile.update') }}" class="row pt-40px">
            @method('PATCH')
            @csrf
            <div class="media media-card align-items-center  col-lg-12">
                <div class="media-img media-img-lg mr-4 bg-gray">
                    <img class="mr-3" src="{{ $profile_img }}" alt="avatar image">
                </div>
                <div class="media-body">
                    <div class="file-upload-wrap file-upload-wrap-2">
                        <input type="file" name="photo" class="multi file-upload-input with-preview" multiple>
                        <span class="file-upload-text"><i class="la la-photo mr-2"></i>Upload a Photo</span>
                    </div><!-- file-upload-wrap -->
                </div>
            </div><!-- end media -->
            <div class=" col-lg-12 row">
                <div class="input-box col-lg-6">
                    <label class="label-text">Name</label>
                    <div class="form-group">
                        <input class="form-control form--control" type="text" name="name" value="{{ $user->name }}">
                        <span class="la la-user input-icon"></span>
                    </div>
                </div><!-- end input-box -->
                <div class="input-box col-lg-6">
                    <label class="label-text">Username</label>
                    <div class="form-group">
                        <input class="form-control form--control" type="text" name="username" value="{{ $user->username }}">
                        <span class="la la-user input-icon"></span>
                    </div>
                </div><!-- end input-box -->
                <div class="input-box col-lg-6">
                    <label class="label-text">Email</label>
                    <div class="form-group">
                        <input class="form-control form--control" type="email" name="email" value="{{ $user->email }}">
                        <span class="la la-envelope input-icon"></span>
                    </div>
                </div><!-- end input-box -->
                <div class="input-box col-lg-6">
                    <label class="label-text">Phone Number</label>
                    <div class="form-group">
                        <input class="form-control form--control" type="text" name="phone" value="{{ $user->phone }}">
                        <span class="la la-phone input-icon"></span>
                    </div>
                </div><!-- end input-box -->
                <div class="input-box col-lg-12">
                    <label class="label-text">Address</label>
                    <div class="form-group">
                        <input class="form-control form--control" type="text" name="address" value="{{ $user->address }}">
                        <span class="la la-envelope input-icon"></span>
                    </div>
                </div><!-- end input-box -->
                <div class="input-box col-lg-12 py-2">
                    <button class="btn theme-btn">Save Changes</button>
                </div><!-- end input-box -->
            </div>
        </form>
        {{-- <div class="card"> --}}
            <form action="" method="post" action="{{ route('profile.password.update') }}">
                @method('PUT')
                @csrf
                <div class="row align-items-center">
                    <div class="col-lg-6 input-box">
                        <label class="label-text">Old Password</label>
                        <div class="form-group">
                            <input type="password" name="old_password" class="form-control form--control @error('old_password')
                                is-invalid
                            @enderror" id="old_password"/>
                            @error('old_password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 input-box">
                        <label class="label-text">New Password</label>
                        <div class="form-group">
                            <input type="password" name="new_password" class="form-control form--control @error('new_password')
                                is-invalid
                            @enderror" id="new_password"/>
                            @error('new_password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 input-box">
                        <label class="label-text">Confirm New Password</label>
                        <div class="form-group">
                            <input type="password" name="new_password_confirmation" class="form-control form--control" id="confirm_password"/>
                        </div>
                    </div>

                    <div class="col-lg-6 input-box mt-4">
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary px-4" value="Change Password" />
                        </div>
                    </div>
                </div>
            </form>
        {{-- </div> --}}
    </div><!-- end setting-body -->
</div><!-- end tab-pane -->
@endsection
