@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit SubCategory</li>
                </ol>
            </nav>
        </div>
        {{-- <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('add.category',) }}" class="btn btn-primary">Edit Category</a>
            </div>
        </div> --}}
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Edit Category</h5>
            <form id="myForm" method="POST" action="{{ route('update.subcategory') }}" class="row g-3">
                @csrf
                <input type="hidden" value="{{ $subcategory->id }}" class="form-control" name="id" id="id">
                <div class="col-md-6 form-group">
                    <label for="category_id" class="form-label">Category Name</label>
                    <select name="category_id" class="form-select mb-3" aria-label="Default select example">
                        <option selected="" disabled>Open this select menu</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id===$subcategory->category_id ? 'selected' : null }}>{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="category_name" class="form-label">SubCategory Name</label>
                    <input type="text" value="{{ $subcategory->subcategory_name }}" class="form-control" name="subcategory_name" id="subcategory_name">
                </div>
                <div class="col-md-12">
                    <div class="d-md-flex d-grid align-items-center gap-3">
                        <button type="submit" class="btn btn-primary px-4">Edit SubCategory</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
