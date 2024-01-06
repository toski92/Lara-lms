@extends('instructor.instructor_dashboard')
@section('instructor')

<div class="page-content">
    <!--breadcrumb-->
    <div class="mb-3 page-breadcrumb d-none d-sm-flex align-courses-center">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="p-0 mb-0 breadcrumb">
                    <li class="breadcrumb-course"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-course active" aria-current="page">All Course</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
           <a href="{{ route('add.course') }}" class="px-5 btn btn-primary">Add Course </a>
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
                            <th>Sl</th>
                            <th>Image </th>
                            <th>Course Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($courses as $key=> $course)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td> <img src="{{ asset($course->feature_image) }}" alt="" style="width: 70px; height:40px;"> </td>
                            <td>{{ $course->course_name }}</td>
                            <td>{{ $course['category']['category_name'] }}</td>
                            <td>{{ $course->selling_price }}</td>
                            <td>{{ $course->discount_price }}</td>
                            <td>
       <a href="{{ route('edit.course',$course->id) }}" class="px-5 btn btn-info">Edit </a>
       <a href="{{ route('delete.course',$course->id) }}" class="px-5 btn btn-danger" id="delete">Delete </a>
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
