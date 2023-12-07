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
                    <li class="breadcrumb-item active" aria-current="page">Add Category</li>
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
        <div class="card-body p-4">
            <h5 class="mb-4">Add Category</h5>
            <form id="myForm" method="POST" action="{{ route('store.category') }}" enctype="multipart/form-data" class="row g-3">
                @csrf
                <div class="col-md-6 form-group">
                    <label for="category_name" class="form-label">Category Name</label>
                    <input type="text" class="form-control" name="category_name" id="category_name">
                </div>
                <div class="col-md-6"></div>
                <div class="col-md-6 form-group">
                    <label for="image" class="form-label">Category Image</label>
                    <input type="file" class="form-control" name="image" id="image">
                </div>
                <div class="col-md-6">
                    <img src="{{ asset("upload/no_image.jpg") }}" width="80" id="show_image" alt="">
                </div>
                <div class="col-md-12">
                    <div class="d-md-flex d-grid align-items-center gap-3">
                        <button type="submit" class="btn btn-primary px-4">Add Category</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                category_name: {
                    required : true,
                },
                image: {
                    required : true,
                },

            },
            messages :{
                category_name: {
                    required : 'Please Enter Category Name',
                },
                image: {
                    required : 'Select Category Image',
                }
            },
            errorElement : 'span',
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });
    });

</script>
<script>
    $(document).ready(function(){
        // Listen for changes in the file input
        $('#image').change(function(){
            // Get the selected file
            var file = this.files[0];

            // Check if a file is selected
            if (file) {
                // Create a FileReader to read the selected file
                var reader = new FileReader();

                // Set up a function to run when the file is loaded
                reader.onload = function(e){
                    // Update the src attribute of the img element
                    $('#show_image').attr('src', e.target.result);
                }

                // Read the selected file as a data URL
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endsection
