@extends('frontend.master')
@section('home')

<!-- ================================
    START BREADCRUMB AREA
================================= -->
<section class="breadcrumb-area section-padding img-bg-2">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content d-flex flex-wrap align-items-center justify-content-between">
            <div class="section-heading">
                <h2 class="section__title text-white">Shopping Cart</h2>
            </div>
            <ul class="generic-list-item generic-list-item-white generic-list-item-arrow d-flex flex-wrap align-items-center">
                <li><a href="index.html">Home</a></li>
                <li>Pages</li>
                <li>Shopping Cart</li>
            </ul>
        </div><!-- end breadcrumb-content -->
    </div><!-- end container -->
</section><!-- end breadcrumb-area -->
<!-- ================================
    END BREADCRUMB AREA
================================= -->

<!-- ================================
       START CONTACT AREA
================================= -->
<section class="cart-area section-padding">
    @php
        $cart = Gloudemans\Shoppingcart\Facades\Cart::count();
    @endphp
    @if ($cart===0)
    <div class="container">No Course in your cart</div>
    @else
    <div class="container">
        <div class="table-responsive">
            <table class="table generic-table">
                <thead>
                <tr>
                    <th scope="col">Image</th>
                    <th scope="col">Product Details</th>
                    <th scope="col">Price</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody id="cartPage">

                </tbody>
            </table>
            <div class="d-flex flex-wrap align-items-center justify-content-between pt-4">
                @if (session('coupon'))
                {{-- <pre>{{ json_encode(session('coupon'), JSON_PRETTY_PRINT) }}</pre> --}}
                @else
                <form method="#">
                    <div class="input-group mb-2" id="couponField">
                        <input class="form-control form--control pl-3" type="text"  id="coupon_name" placeholder="Coupon code">
                        <div class="input-group-append">
                            <a type="submit" onclick="applyCoupon()" class="btn theme-btn">Apply Code</a>
                        </div>
                    </div>
                </form>
                @endif

                {{-- <a href="#" class="btn theme-btn mb-2">Update Cart</a> --}}
            </div>
        </div>
        <div class="col-lg-4 ml-auto">
            <div class="bg-gray p-4 rounded-rounded mt-40px" id="couponCalField">
            </div>
            <a href="{{ route('checkout') }}" class="btn theme-btn w-100">Checkout <i class="la la-arrow-right icon ml-1"></i></a>
        </div>
    </div><!-- end container -->
    @endif
</section>
<!-- ================================
       END CONTACT AREA
================================= -->







@endsection
