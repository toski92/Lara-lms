<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\CourseController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\QuestionController;
use App\Http\Controllers\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\WishListController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
// use App\Models\User;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
// use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', [UserController::class, 'index'])->name('index');

Route::get('/dashboard', function () {
    return view('frontend.dashboard.index');
})->middleware(['auth', 'roles:user', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile', [ProfileController::class, 'update_password'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/register-teacher', [UserController::class, 'register_teacher'])->name('register.teacher');
    Route::get('/wishlist', [WishListController::class, 'index'])->name('user.wishlist');
    Route::get('/get-wishlist-course',[WishListController::class, 'show']);
    Route::get('/wishlist-remove/{id}',[WishListController::class, 'destroy']);
    Route::get('/my-course',[OrderController::class, 'MyCourse'])->name('my.course');
    Route::get('/course-view/{course_id}',[OrderController::class, 'CourseView'])->name('course.view');
    Route::post('user-question',[QuestionController::class,'store'])->name('user.question');
});
// Admin group middleware
Route::middleware(['auth','roles:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'admin_dashboard'])->name('admin.dashboard');
    Route::get('/categories', [CategoryController::class, 'index'])->name('all.category');
    Route::get('/add-category', [CategoryController::class, 'create'])->name('add.category');
    Route::post('/add-category', [CategoryController::class, 'store'])->name('store.category');
    Route::get('/edit-category/{id}', [CategoryController::class, 'edit'])->name('edit.category');
    Route::post('/update-category', [CategoryController::class, 'update'])->name('update.category');
    Route::get('/delete-category/{id}', [CategoryController::class, 'destroy'])->name('delete.category');

    Route::get('/users', [AdminController::class, 'user'])->name('all.users');
    Route::get('/edit-user/{id}', [AdminController::class, 'edit'])->name('edit.user');
    Route::post('/update-user', [AdminController::class, 'update'])->name('update.user');
    Route::get('/delete-user/{id}', [AdminController::class, 'destroy'])->name('delete.user');


    Route::get('/subcategories', [SubCategoryController::class, 'index'])->name('all.subcategory');
    Route::get('/add-subcategory', [SubCategoryController::class, 'create'])->name('add.subcategory');
    Route::post('/add-subcategory', [SubCategoryController::class, 'store'])->name('store.subcategory');
    Route::get('/edit-subcategory/{id}', [SubCategoryController::class, 'edit'])->name('edit.subcategory');
    Route::post('/update-subcategory', [SubCategoryController::class, 'update'])->name('update.subcategory');
    Route::get('/delete-subcategory/{id}', [SubCategoryController::class, 'destroy'])->name('delete.subcategory');

    Route::get('/admin/courses',[AdminController::class, 'all_courses'])->name('admin.course');
    Route::post('/update-course-status',[AdminController::class, 'update_course_status'])->name('update.course.status');
    Route::get('/admin-course-details/{id}',[AdminController::class, 'admin_course_details'])->name('admin.course.details');
    Route::get('/admin-coupon',[CouponController::class, 'index'])->name('admin.all.coupon');
    Route::get('/add-coupon',[CouponController::class, 'create'])->name('create.coupon');
    Route::post('/add-coupon',[CouponController::class, 'store'])->name('store.coupon');
    Route::get('edit-coupon/{id}',[CouponController::class, 'edit'])->name('edit.coupon');
    Route::post('update-coupon',[CouponController::class, 'update'])->name('update.coupon');
    Route::get('delete-coupon/{id}',[CouponController::class, 'destroy'])->name('delete.coupon');
    Route::get('pending-order',[OrderController::class, 'index'])->name('admin.pending.order');
    Route::get('pending-order-detail/{id}',[OrderController::class, 'show'])->name('admin.order.details');
    Route::get('/pending-confrim/{id}',[OrderController::class, 'PendingToConfirm'])->name('pending-confrim');
    Route::get('/confirm-order',[OrderController::class, 'AdminConfirmOrder'])->name('admin.confirm.order');
});
// Instructor group middleware
Route::middleware(['auth','roles:instructor'])->group(function () {
    Route::get('/instructor/dashboard', [InstructorController::class, 'instructor_dashboard'])->name('instructor.dashboard');
    Route::get('/courses', [CourseController::class, 'index'])->name('all.course');
    Route::get('/add-course', [CourseController::class, 'create'])->name('add.course');
    Route::post('/add-course', [CourseController::class, 'store'])->name('store.course');
    Route::get('/edit-course/{id}', [CourseController::class, 'edit'])->name('edit.course');
    Route::post('/update-course', [CourseController::class, 'update'])->name('update.course');
    Route::post('/update-feature-image', [CourseController::class, 'UpdateFeatureImage'])->name('update.feature.image');
    Route::post('/update-video', [CourseController::class, 'UpdateCourseVideo'])->name('update.course.video');
    Route::post('/update-meta', [CourseController::class, 'UpdateCourseMeta'])->name('update.course.meta');
    Route::get('/delete-course/{id}', [CourseController::class, 'destroy'])->name('delete.course');
    Route::get('/subcategory/ajax/{category_id}',[CourseController::class, 'GetSubCategory']);

    Route::get('/add-lecture/{id}',[CourseController::class, 'add_lecture'])->name('add.lecture');
    Route::post('/add-topic',[CourseController::class, 'add_topic'])->name('add.topic');
    Route::post('/save-lecture',[CourseController::class, 'save_lecture'])->name('save.lecture');
    Route::get('/get-lecture/{id}',[CourseController::class, 'get_lecture']);
    Route::post('/update-lecture',[CourseController::class, 'update_lecture'])->name('update.lecture');
    Route::get('/delete-lecture/{id}',[CourseController::class, 'delete_lecture'])->name('delete.lecture');
    Route::post('/delete-topic/{id}',[CourseController::class, 'delete_topic'])->name('delete.topic');
    Route::get('/instructor-all-order',[OrderController::class, 'Instructo rAllOrder'])->name('instructor.all.order');
    Route::get('/instructor-order-details/{payment_id}',[OrderController::class, 'InstructorOrderDetails'])->name('instructor.order.details');
    Route::get('/instructor-invoice/{payment_id}',[OrderController::class, 'InstructorOrderInvoice'])->name('instructor.order.invoice');

    Route::get('instructor-question',[QuestionController::class, 'index'])->name('instructor.all.question');
    Route::get('question-details/{id}',[QuestionController::class, 'show'])->name('question.details');
    Route::post('instructor-replay',[QuestionController::class, 'InstructorReplay'])->name('instructor.replay');
});

Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
Route::get('/courses/{id}/{slug}', [IndexController::class, 'index']);
Route::get('/category/{id}/{slug}', [IndexController::class, 'category']);
Route::get('/subcategory/{id}/{slug}', [IndexController::class, 'subcategory']);
Route::get('/instructor/{id}', [IndexController::class, 'instructor'])->name('instructor.details')->where('id', '[0-9]+');
Route::post('/add-to-wishlist/{course_id}', [WishListController::class, 'store']);
Route::post('/cart/{id}', [CartController::class, 'store']);
Route::get('/cart', [CartController::class, 'index']);
Route::get('/remove-cart/{rowId}', [CartController::class, 'destroy']);
Route::get('/mycart', [CartController::class, 'MyCart'])->name('mycart');
Route::get('/get-cart-course', [CartController::class, 'index']);
Route::post('/coupon-apply', [CartController::class, 'CouponApply']);
Route::get('/coupon-calculation', [CartController::class, 'CouponCalculation']);
Route::get('/coupon-remove', [CartController::class, 'CouponRemove']);
Route::get('/checkout', [CartController::class, 'CheckoutCreate'])->name('checkout');
Route::post('/payment', [CartController::class, 'Payment'])->name('payment');
Route::post('/buy-course/{id}', [CartController::class, 'BuyToCart']);
Route::post('/stripe_order', [CartController::class, 'StripeOrder'])->name('stripe_order');

// Route::get('/auth/google', function () {
//     return Socialite::driver('google')->redirect();
// });

// Route::get('/auth/google/callback', function () {
//     try {

//         $user = Socialite::driver('google')->user();

//         $finduser = User::where('google_id', $user->id)->first();

//         if($finduser)

//         {

//             Auth::login($finduser);

//             return redirect()->intended('dashboard');

//         }

//         else

//         {
//             $newUser = User::create([
//                 'name' => $user->name,
//                 'email' => $user->email,
//                 'google_id'=> $user->id,
//                 'password' => encrypt('123456dummy')
//             ]);

//             Auth::login($newUser);

//             // $url = '';
//             // if ($request->user()->role==='admin') {
//             //     $url = route('admin.dashboard');
//             // }elseif ($request->user()->role==='instructor') {
//             //     $url= route('instructor.dashboard');
//             // }elseif ($request->user()->role==='user') {
//             //     $url= route('dashboard');
//             // }

//             // return redirect()->intended($url);

//             return redirect()->intended('dashboard');
//         }

//     } catch (Exception $e) {
//         dd($e->getMessage());
//     }
// });

require __DIR__.'/auth.php';
