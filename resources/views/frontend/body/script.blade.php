<script type="text/javascript">

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        }
    })
    function addToWishList(course_id){
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "/add-to-wishlist/"+course_id,
            success:function(data){
                console.log(data);
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 6000
                })
                if ($.isEmptyObject(data.error)) {

                    Toast.fire({
                    type: 'success',
                    icon: 'success',
                    title: data.success,
                    })
                }else{

                    Toast.fire({
                        type: 'error',
                        icon: 'error',
                        title: data.error,
                    })
                }
            }
        })
        setTimeout(function() {
                location.reload();
            }, 2000);
        }

</script>
{{-- /// End Wishlist Add Option // --}}

 {{-- /// Start Load Wishlist Data // --}}
<script type="text/javascript">
    function wishlist(){
        $.ajax({
            type: "GET",
            dataType: 'json',
            url: "/get-wishlist-course/",
            success:function(response){
                // console.log(response.wishlist)
                var rows = ""
                $.each(response.wishlist, function(key, value){
                    $('#wishQty').text(response.wishQty);
                    rows += `
                        <div class="col-lg-4 responsive-column-half">
                            <div class="card card-item">
                                <div class="card-image">
                                    <a href="courses/${value.course.id}/${value.course.slug}" class="d-block">
                                        <img class="card-img-top" src="/${value.course.feature_image}" alt="Card image cap">
                                    </a>

                                </div><!-- end card-image -->
                                <div class="card-body">
                                    <h6 class="ribbon ribbon-blue-bg fs-14 mb-3">${value.course.level}</h6>
                                    <h5 class="card-title"><a href="courses/${value.course.id}/${value.course.slug}">${value.course.course_name}</a></h5>
                                    <div class="d-flex justify-content-between align-items-center">

                                        ${value.course.discount_price == null
                                        ?`<p class="card-price text-black font-weight-bold">$${value.course.selling_price}</p>`
                                        :`<p class="card-price text-black font-weight-bold">$${value.course.discount_price} <span class="before-price font-weight-medium">$${value.course.selling_price}</span></p>`
                                        }

                                        <div class="icon-element icon-element-sm shadow-sm cursor-pointer" data-toggle="tooltip" data-placement="top" title="Remove from Wishlist" id="${value.id}" onclick="wishlistRemove(this.id)"><i class="la la-heart"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `
                });
                $('#wishlist').html(rows);
            }
        })
    }
    wishlist();


    /// WishList Remove Start  //
    function wishlistRemove(id){
        $.ajax({
            type: "GET",
            dataType: 'json',
            url: "/wishlist-remove/"+id,
            success:function(data){
             wishlist();
                 // Start Message
            const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 6000
            })
            if ($.isEmptyObject(data.error)) {

                    Toast.fire({
                    type: 'success',
                    icon: 'success',
                    title: data.success,
                    })
            }else{

           Toast.fire({
                    type: 'error',
                    icon: 'error',
                    title: data.error,
                    })
                }
              // End Message
            }
        })
    }
</script>

