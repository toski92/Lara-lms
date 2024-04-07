@extends('frontend.master')
@section('home')
<!-- ================================
    START BREADCRUMB AREA
================================= -->
<section class="breadcrumb-area section-padding img-bg-2">
    <div class="overlay"></div>
    <div class="container">
        <div class="flex-wrap breadcrumb-content d-flex align-items-center justify-content-between">
            <div class="section-heading">
                <h2 class="text-white section__title">Contact</h2>
            </div>
            <ul class="flex-wrap generic-list-item generic-list-item-white generic-list-item-arrow d-flex align-items-center">
                <li><a href="index.html">Home</a></li>
                <li>Pages</li>
                <li>Contact</li>
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
<section class="contact-area section--padding position-relative">
    <span class="ring-shape ring-shape-1"></span>
    <span class="ring-shape ring-shape-2"></span>
    <span class="ring-shape ring-shape-3"></span>
    <span class="ring-shape ring-shape-4"></span>
    <span class="ring-shape ring-shape-5"></span>
    <span class="ring-shape ring-shape-6"></span>
    <span class="ring-shape ring-shape-7"></span>
    <div class="container">
        <div class="row">
            <div class="mx-auto col-lg-7">
                <div class="card card-item">
                    <div class="card-body">
                        <h3 class="pb-4 text-center card-title fs-24 lh-35">Contact Us!</h3>
                        <div class="section-block"></div>
                        <form method="POST" class="pt-4" action="{{ route('store.contact') }}">
                            @csrf
                            <div class="input-box">
                                <div class="form-group">
                                    <input class="form-control form--control" type="text" name="name" placeholder="Name">
                                </div>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div><!-- end input-box -->
                            <div class="input-box">
                                <div class="form-group">
                                    <input class="form-control form--control" type="email" name="email" placeholder="Email">
                                </div>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div><!-- end input-box -->
                            <div class="input-box">
                                <div class="mb-3 input-group">
                                    <textarea class="form-control form--control" type="text" name="message" placeholder="Message"></textarea>
                                </div>
                                @error('message')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div><!-- end input-box -->
                            <div class="btn-box">
                                <button class="btn theme-btn" type="submit">Send Message <i class="ml-1 la la-arrow-right icon"></i></button>
                            </div><!-- end btn-box -->
                        </form>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div><!-- end col-lg-7 -->
        </div><!-- end row -->
    </div><!-- end container -->
</section><!-- end contact-area -->
<!-- ================================
       END CONTACT AREA
================================= -->
@endsection
