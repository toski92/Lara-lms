@extends('admin.admin_dashboard')
@section('admin')
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">All Category</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('add.category') }}" class="btn btn-primary">Add Category</a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Category Image</th>
                            <th>Category Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $key => $category)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td><img src="{{ asset($category->image) }}" alt="{{ $category->name }}" style="width: 70px; height:30px"></td>
                            <td>{{ $category->category_name }}</td>
                            <td>
                                @if (Auth::user()->can('category.edit'))
                                <a href="{{ route('edit.category',$category->id) }}" class="btn btn-info">Edit</a>
                                @endif
                                @if (Auth::user()->can('category.delete'))
                                <a href="{{ route('delete.category',$category->id) }}" id="delete" class="btn btn-danger">Delete</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
