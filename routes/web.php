<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Controllers\Backend\SubCategoryController;
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
});

Route::get('/logout', [AdminController::class, 'logout'])->name('logout');

require __DIR__.'/auth.php';
