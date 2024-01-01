@extends('instructor.instructor_dashboard')
@section('instructor')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="mb-3 page-breadcrumb d-none d-sm-flex align-items-center">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="p-0 mb-0 breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add Course</li>
                </ol>
            </nav>
        </div>

    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="p-4 card-body">
            <h5 class="mb-4">Add Course</h5>

            <form id="myForm" action="{{ route('store.category') }}" method="post" class="row g-3" enctype="multipart/form-data">
                @csrf

                <div class="form-group col-md-6">
                    <label for="input1" class="form-label">Course Name</label>
                    <input type="text" name="course_name" class="form-control" id="input1"  >
                </div>

                <div class="form-group col-md-6">
                    <label for="input1" class="form-label">Course Title </label>
                    <input type="text" name="course_title" class="form-control" id="input1"  >
                </div>



                <div class="form-group col-md-6">
                    <label for="input2" class="form-label">Feature Image </label>
                    <input class="form-control" name="feature_image" type="file" id="image">
                </div>

                <div class="col-md-6">
                    <img id="showImage" src="{{ url('upload/no_image.jpg')}}" alt="Admin" class="p-1 rounded-circle bg-primary" width="100">
                </div>


                <div class="form-group col-md-6">
                    <label for="input1" class="form-label">Course Intro Video </label>
                    <input type="file" name="video" class="form-control"  accept="video/mp4, video/webm" >
                </div>

                <div class="form-group col-md-6">

                </div>

            <div class="form-group col-md-6">
                <label for="input1" class="form-label">Course Category </label>
                <select name="category_id" class="mb-3 form-select" aria-label="Default select example">
                    <option selected="" disabled>Open this select menu</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                    @endforeach

                </select>
            </div>


            <div class="form-group col-md-6">
                <label for="input1" class="form-label">Course Subcategory </label>
                <select name="subcategory_id" class="mb-3 form-select" aria-label="Default select example">
                    <option></option>

                </select>
            </div>


            <div class="form-group col-md-6">
                <label for="input1" class="form-label">Certificate Available </label>
                <select name="certificate" class="mb-3 form-select" aria-label="Default select example">
               <option selected="" disabled>Open this select menu</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>


            <div class="form-group col-md-6">
                <label for="input1" class="form-label">Level </label>
                <select name="level" class="mb-3 form-select" aria-label="Default select example">
               <option selected="" disabled>Open this select menu</option>
                    <option value="Begineer">Begineer</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Advance">Advance</option>
                </select>
            </div>


            <div class="form-group col-md-3">
                <label for="input1" class="form-label">Course Price </label>
                <input type="text" name="selling_price" class="form-control" id="input1"  >
            </div>


            <div class="form-group col-md-3">
                <label for="input1" class="form-label">Discount Price </label>
                <input type="text" name="discount_price" class="form-control" id="input1"  >
            </div>


            <div class="form-group col-md-3">
                <label for="input1" class="form-label">Duration </label>
                <input type="text" name="duration" class="form-control" id="input1"  >
            </div>


            <div class="form-group col-md-3">
                <label for="input1" class="form-label">Resources </label>
                <input type="text" name="resources" class="form-control" id="input1"  >
            </div>

            <div class="form-group col-md-12">
                <label for="input1" class="form-label">Course Excerpt </label>
                <textarea name="excerpt" class="form-control" id="input11" placeholder="Brief Description ..." rows="3"></textarea>
            </div>

            <div class="form-group col-md-12">
                <label for="input1" class="form-label">Course Description </label>
                <textarea name="description" class="form-control" id="myeditorinstance"></textarea>
            </div>

            <p>Course Metas </p>

            <div class="row add_item">

                <div class="col-md-6">
                    <div class="mb-3">
                            <label for="metas" class="form-label"> Metas </label>
                            <input type="text" name="course_metas[]" id="metas" class="form-control" placeholder="metas ">
                    </div>
                </div>
                <div class="form-group col-md-6" style="padding-top: 30px;">
                    <a class="btn btn-success addeventmore"><i class="fa fa-plus-circle"></i> Add More..</a>
                </div>
        </div> <!---end row-->


            <hr>
            <div class="row">

                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="bestseller" value="1" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">BestSeller</label>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="featured" value="1" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">Featured</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="highestrated" value="1" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">Highest Rated</label>
                    </div>
                </div>

            </div>



            <div class="col-md-12">
                <div class="gap-3 d-md-flex d-grid align-items-center">
                    <button type="submit" class="px-4 btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
        </div>
    </div>




</div>

<!--========== Start of add multiple class with ajax ==============-->
<div style="visibility: hidden">
    <div class="whole_extra_item_add" id="whole_extra_item_add">
       <div class="whole_extra_item_delete" id="whole_extra_item_delete">
          <div class="container mt-2">
             <div class="row">


                <div class="form-group col-md-6">
                   <label for="metas">Metas</label>
                   <input type="text" name="course_metas[]" id="metas" class="form-control" placeholder="Metas  ">
                </div>
                <div class="form-group col-md-6" style="padding-top: 20px">
                   <span class="btn btn-success btn-sm addeventmore"><i class="fa fa-plus-circle">Add</i></span>
                   <span class="btn btn-danger btn-sm removeeventmore"><i class="fa fa-minus-circle">Remove</i></span>
                </div>
             </div>
          </div>
       </div>
    </div>
 </div>


 <!----For Section-------->
 <script type="text/javascript">
    $(document).ready(function(){
       var counter = 0;
       $(document).on("click",".addeventmore",function(){
             var whole_extra_item_add = $("#whole_extra_item_add").html();
             $(this).closest(".add_item").append(whole_extra_item_add);
             counter++;
       });
       $(document).on("click",".removeeventmore",function(event){
             $(this).closest("#whole_extra_item_delete").remove();
             counter -= 1
       });
    });
 </script>
 <!--========== End of add multiple class with ajax ==============-->

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
                    required : 'Please Select Category Image',
                },

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

<script type="text/javascript">
    $(document).ready(function(){
        $('#image').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showImage').attr('src',e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });
    });
</script>

<script type="text/javascript">

    $(document).ready(function(){
        $('select[name="category_id"]').on('change', function(){
            var category_id = $(this).val();
            if (category_id) {
                $.ajax({
                    url: "{{ url('/subcategory/ajax') }}/"+category_id,
                    type: "GET",
                    dataType:"json",
                    success:function(data){
                        $('select[name="subcategory_id"]').html('');
                        var d =$('select[name="subcategory_id"]').empty();
                        $.each(data, function(key, value){
                            $('select[name="subcategory_id"]').append('<option value="'+ value.id + '">' + value.subcategory_name + '</option>');
                        });
                    },
                });
            } else {
                alert('danger');
            }
        });
    });
</script>
@endsection
