<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CourseController;
use App\Http\Controllers\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile', [ProfileController::class, 'update_password'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/register-teacher', [UserController::class, 'register_teacher'])->name('register.teacher');
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
});

Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
Route::get('/courses/{id}/{slug}', [IndexController::class, 'index']);

require __DIR__.'/auth.php';
