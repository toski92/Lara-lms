<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ChatController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\CourseController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\QuestionController;
use App\Http\Controllers\Backend\ReviewController;
use App\Http\Controllers\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\WishListController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
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
    Route::get('/become-a-teacher', [UserController::class, 'register_teacher'])->name('become-a-teacher');
    Route::get('/wishlist', [WishListController::class, 'index'])->name('user.wishlist');
    Route::get('/get-wishlist-course',[WishListController::class, 'show']);
    Route::get('/wishlist-remove/{id}',[WishListController::class, 'destroy']);
    Route::get('/my-course',[OrderController::class, 'MyCourse'])->name('my.course');
    Route::get('/course-view/{course_id}',[OrderController::class, 'CourseView'])->name('course.view');
    Route::post('user-question',[QuestionController::class,'store'])->name('user.question');
    Route::get('live-chat', [UserController::class, 'LiveChat'])->name('live.chat');
});
// Admin group middleware
Route::middleware(['auth','roles:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'admin_dashboard'])->name('admin.dashboard');
    Route::get('/categories', [CategoryController::class, 'index'])->name('all.category')->middleware('permission:category.all');
    Route::get('/add-category', [CategoryController::class, 'create'])->name('add.category');
    Route::post('/add-category', [CategoryController::class, 'store'])->name('store.category');
    Route::get('/edit-category/{id}', [CategoryController::class, 'edit'])->name('edit.category');
    Route::post('/update-category', [CategoryController::class, 'update'])->name('update.category');
    Route::get('/delete-category/{id}', [CategoryController::class, 'destroy'])->name('delete.category');

    Route::get('/users', [AdminController::class, 'user'])->name('all.users');
    Route::get('/edit-user/{id}', [AdminController::class, 'edit'])->name('edit.user');
    Route::post('/update-user', [AdminController::class, 'update'])->name('update.user');
    Route::get('/delete-user/{id}', [AdminController::class, 'destroy'])->name('delete.user');


    Route::get('/subcategories', [SubCategoryController::class, 'index'])->name('all.subcategory')->middleware('permission:subcategory.all');
    Route::get('/add-subcategory', [SubCategoryController::class, 'create'])->name('add.subcategory');
    Route::post('/add-subcategory', [SubCategoryController::class, 'store'])->name('store.subcategory');
    Route::get('/edit-subcategory/{id}', [SubCategoryController::class, 'edit'])->name('edit.subcategory');
    Route::post('/update-subcategory', [SubCategoryController::class, 'update'])->name('update.subcategory');
    Route::get('/delete-subcategory/{id}', [SubCategoryController::class, 'destroy'])->name('delete.subcategory');

    Route::get('/admin/courses',[AdminController::class, 'all_courses'])->name('admin.course');
    Route::post('/update-course-status',[AdminController::class, 'update_course_status'])->name('update.course.status');
    Route::get('/admin-course-details/{id}',[AdminController::class, 'admin_course_details'])->name('admin.course.details');

    Route::get('all-admin',[AdminController::class, 'AllAdmin'])->name('all.admin');
    Route::get('add-admin',[AdminController::class, 'AddAdmin'])->name('add.admin');
    Route::post('store-admin',[AdminController::class, 'StoreAdmin'])->name('store.admin');
    Route::get('edit-admin/{id}',[AdminController::class, 'EditAdmin'])->name('edit.admin');
    Route::post('update-admin/{id}',[AdminController::class, 'UpdateAdmin'])->name('update.admin');
    Route::get('delete-admin/{id}',[AdminController::class, 'DeleteAdmin'])->name('delete.admin');

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

    Route::get('report-view',[ReportController::class, 'index'])->name('report.view');
    Route::post('search-by-date',[ReportController::class, 'SearchByDate'])->name('search.by.date');
    Route::post('search-by-month',[ReportController::class, 'SearchByMonth'])->name('search.by.month');
    Route::post('search-by-year',[ReportController::class, 'SearchByYear'])->name('search.by.year');

    Route::get('admin-pending-review', [ReviewController::class, 'AdminPendingReview'])->name('admin.pending.review');
    Route::post('update-review-status',[ReviewController::class, 'UpdateReviewStatus'])->name('update.review.status');
    Route::get('admin-active-review',[ReviewController::class, 'AdminActiveReview'])->name('admin.active.review');

    Route::get('blog-category',[BlogController::class, 'index'])->name('blog.category');
    Route::post('blog-category-store',[BlogController::class, 'store'])->name('blog.category.store');
    Route::get('edit-blog-category/{id}',[BlogController::class, 'create']);
    Route::post('blog-category-update',[BlogController::class, 'update'])->name('blog.category.update');
    Route::get('delete-blog-category/{id}',[BlogController::class, 'destroy'])->name('delete.blog.category');
    Route::get('blog-post',[BlogController::class, 'BlogPost'])->name('blog.post');
    Route::get('add-blog-post',[BlogController::class, 'AddBlogPost'])->name('add.blog.post');
    Route::post('store-blog-post',[BlogController::class, 'StoreBlogPost'])->name('store.blog.post');
    Route::get('/edit/post/{id}',[BlogController::class, 'EditBlogPost'])->name('edit.post');
    Route::post('/update/blog/post',[BlogController::class, 'UpdateBlogPost'])->name('update.blog.post');
    Route::get('/delete/post/{id}',[BlogController::class, 'DeleteBlogPost'])->name('delete.post');

    Route::get('all-permission',[RoleController::class, 'index'])->name('all.permission');
    Route::get('add-permission',[RoleController::class, 'create'])->name('add.permission');
    Route::post('store-permission',[RoleController::class, 'store'])->name('store.permission');
    Route::get('edit-permission/{id}',[RoleController::class, 'edit'])->name('edit.permission');
    Route::post('update-permission',[RoleController::class, 'update'])->name('update.permission');
    Route::get('delete-permission/{id}',[RoleController::class, 'destroy'])->name('delete.permission');
    Route::get('import-permission',[RoleController::class, 'ImportPermission'])->name('import.permission');
    Route::get('export',[RoleController::class, 'Export'])->name('export');
    Route::post('import',[RoleController::class, 'Import'])->name('import');
    Route::get('all-roles',[RoleController::class, 'AllRoles'])->name('all.roles');
    Route::get('add-roles',[RoleController::class, 'AddRoles'])->name('add.roles');
    Route::post('store-roles',[RoleController::class, 'StoreRoles'])->name('store.roles');
    Route::get('edit-roles/{id}',[RoleController::class, 'EditRoles'])->name('edit.roles');
    Route::post('update-roles',[RoleController::class, 'UpdateRoles'])->name('update.roles');
    Route::get('delete-roles/{id}',[RoleController::class, 'DeleteRoles'])->name('delete.roles');
    Route::get('add-roles-permission',[RoleController::class, 'AddRolesPermission'])->name('add.roles.permission');
    Route::post('role-permission-store',[RoleController::class, 'RolePermissionStore'])->name('role.permission.store');
    Route::get('all-roles-permission',[RoleController::class, 'AllRolesPermission'])->name('all.roles.permission');
    Route::get('admin-edit-roles/{id}',[RoleController::class, 'AdminEditRoles'])->name('admin.edit.roles');
    Route::post('admin-roles-update/{id}',[RoleController::class, 'AdminUpdateRoles'])->name('admin.roles.update');
    Route::get('admin-delete-roles/{id}',[RoleController::class, 'AdminDeleteRoles'])->name('admin.delete.roles');
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
    Route::get('/instructor-all-order',[OrderController::class, 'InstructorAllOrder'])->name('instructor.all.order');
    Route::get('/instructor-order-details/{payment_id}',[OrderController::class, 'InstructorOrderDetails'])->name('instructor.order.details');
    Route::get('/instructor-invoice/{payment_id}',[OrderController::class, 'InstructorOrderInvoice'])->name('instructor.order.invoice');

    Route::get('instructor-question',[QuestionController::class, 'index'])->name('instructor.all.question');
    Route::get('question-details/{id}',[QuestionController::class, 'show'])->name('question.details');
    Route::post('instructor-replay',[QuestionController::class, 'InstructorReplay'])->name('instructor.replay');
    Route::get('instructor-all-coupon',[CouponController::class, 'InstructorAllCoupon'])->name('instructor.all.coupon');
    Route::get('instructor-add-coupon',[CouponController::class, 'InstructorAddCoupon'])->name('instructor.add.coupon');
    Route::post('instructor-store-coupon',[CouponController::class, 'InstructorStoreCoupon'])->name('instructor.store.coupon');

    Route::get('instructor-edit-coupon/{id}',[CouponController::class, 'InstructorEditCoupon'])->name('instructor.edit.coupon');
    Route::post('instructor-update-coupon',[CouponController::class, 'InstructorUpdateCoupon'])->name('instructor.update.coupon');
    Route::get('instructor-delete-coupon/{id}',[CouponController::class, 'InstructorDeleteCoupon'])->name('instructor.delete.coupon');

    Route::get('instructor-all-review',[ReviewController::class, 'InstructorAllReview'])->name('instructor.all.review');
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
Route::post('/inscoupon-apply', [CartController::class, 'InsCouponApply']);
Route::get('/coupon-calculation', [CartController::class, 'CouponCalculation']);
Route::get('/coupon-remove', [CartController::class, 'CouponRemove']);
Route::get('/checkout', [CartController::class, 'CheckoutCreate'])->name('checkout');
Route::post('/payment', [CartController::class, 'Payment'])->name('payment');
Route::post('/buy-course/{id}', [CartController::class, 'BuyToCart']);
Route::post('/stripe_order', [CartController::class, 'StripeOrder'])->name('stripe_order');
Route::post('store-review', [ReviewController::class, 'store'])->name('store.review');
Route::get('blog-details/{slug}', [BlogController::class, 'BlogDetails']);
Route::get('blog-cat-list/{id}', [BlogController::class, 'BlogCatList']);
Route::get('/blog', [BlogController::class, 'BlogList'])->name('blog');
Route::post('/mark-notification-as-read/{notification}', [CartController::class, 'MarkAsRead']);
Route::post('/send-message', [ChatController::class, 'store']);
Route::get('/user-all', [ChatController::class, 'index']);
Route::get('/user-message/{id}', [ChatController::class, 'UserMsgById']);

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
