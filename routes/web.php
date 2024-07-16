<?php

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




##############################  User login logout details#####################################

Route::get('/', function () {
    return view('auth.login');
});
Route::post('/auth_login', [App\Http\Controllers\LogincheckController::class, 'login'])->name('login');
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

####################################################################################################
##############################  Claim details#########################################

Route::get('/claim_request', [App\Http\Controllers\ClaimController::class, 'index'])->name('claim_request');
Route::get('/claim-view', [App\Http\Controllers\ClaimController::class, 'view'])->name('claim-view');

########################################################################################################

Route::get('/claim_request', [App\Http\Controllers\ClaimController::class, 'index'])->name('claim_request');
Route::get('/claim-view', [App\Http\Controllers\ClaimController::class, 'view'])->name('claim-view');



##############################  branch details#########################################


Route::get('/branch', [App\Http\Controllers\BranchController::class, 'index'])->name('branch');
Route::get('/get_branch_list', [App\Http\Controllers\BranchController::class, 'get_branch_list'])->name('get_branch_list');
Route::get('/add_branch', [App\Http\Controllers\BranchController::class, 'add_branch'])->name('add_branch');
Route::get('/view_branch/{id}', [App\Http\Controllers\BranchController::class, 'view_branch'])->name('view_branch');
Route::get('/edit_branch/{id}', [App\Http\Controllers\BranchController::class, 'edit_branch'])->name('edit_branch');
Route::post('/update_branch_submit', [App\Http\Controllers\BranchController::class, 'update_branch_submit'])->name('update_branch_submit');
Route::post('/add_branch_submit', [App\Http\Controllers\BranchController::class, 'submit'])->name('add_branch_submit');
Route::get('/delete_branch/{id}', [App\Http\Controllers\BranchController::class, 'delete_branch'])->name('delete_branch');
Route::post('/delete_multi_branch', [App\Http\Controllers\BranchController::class, 'delete_multi_branch'])->name('delete_multi_branch'); 

########################################################################################################


##############################  grade details#########################################


Route::get('/grade', [App\Http\Controllers\GradeController::class, 'index'])->name('grade');
Route::get('/get_grade_list', [App\Http\Controllers\GradeController::class, 'get_grade_list'])->name('get_grade_list');
Route::get('/add_grade', [App\Http\Controllers\GradeController::class, 'add_grade'])->name('add_grade');
Route::get('/view_grade/{id}', [App\Http\Controllers\GradeController::class, 'view_grade'])->name('view_grade');
Route::get('/edit_grade/{id}', [App\Http\Controllers\GradeController::class, 'edit_grade'])->name('edit_grade');
Route::post('/update_grade_submit', [App\Http\Controllers\GradeController::class, 'update_grade_submit'])->name('update_grade_submit');
Route::post('/add_grade_submit', [App\Http\Controllers\GradeController::class, 'submit'])->name('add_grade_submit');
Route::get('/delete_grade/{id}', [App\Http\Controllers\GradeController::class, 'delete_grade'])->name('delete_grade');
Route::post('/delete_multi_grade', [App\Http\Controllers\GradeController::class, 'delete_multi_grade'])->name('delete_multi_grade'); 

########################################################################################################


##############################  Users details#########################################


Route::get('/list_users', [App\Http\Controllers\UserController::class, 'list_users'])->name('list_users');
Route::get('/get_user_list', [App\Http\Controllers\UserController::class, 'get_user_list'])->name('get_user_list');
Route::get('/add_user', [App\Http\Controllers\UserController::class, 'add_user'])->name('add_user');
Route::get('/view_user/{id}', [App\Http\Controllers\UserController::class, 'view_user'])->name('view_user');
Route::get('/edit_user/{id}', [App\Http\Controllers\UserController::class, 'edit_user'])->name('edit_user');
Route::post('/update_user_submit', [App\Http\Controllers\UserController::class, 'update_user_submit'])->name('update_user_submit');
Route::post('/add_user_submit', [App\Http\Controllers\UserController::class, 'submit'])->name('add_user_submit');
Route::get('/delete_user/{id}', [App\Http\Controllers\UserController::class, 'delete_user'])->name('delete_user');
Route::post('/delete_multi_user', [App\Http\Controllers\UserController::class, 'delete_multi_user'])->name('delete_multi_user'); 

########################################################################################################


##############################  category details#########################################


Route::get('/claim_category', [App\Http\Controllers\CategoryController::class, 'index'])->name('claim_category');
Route::get('/get_category_list', [App\Http\Controllers\CategoryController::class, 'get_category_list'])->name('get_category_list');
Route::get('/add_category', [App\Http\Controllers\CategoryController::class, 'add_category'])->name('add_category');
Route::get('/view_category/{id}', [App\Http\Controllers\CategoryController::class, 'view_category'])->name('view_category');
Route::get('/edit_category/{id}', [App\Http\Controllers\CategoryController::class, 'edit_category'])->name('edit_category');
Route::post('/update_category_submit', [App\Http\Controllers\CategoryController::class, 'update_category_submit'])->name('update_category_submit');
Route::post('/add_category_submit', [App\Http\Controllers\CategoryController::class, 'submit'])->name('add_category_submit');
Route::get('/delete_category/{id}', [App\Http\Controllers\CategoryController::class, 'delete_category'])->name('delete_category');
Route::post('/delete_multi_category', [App\Http\Controllers\CategoryController::class, 'delete_multi_category'])->name('delete_multi_category'); 

########################################################################################################


##############################  sub category details#########################################


Route::get('/sub_claim_category', [App\Http\Controllers\CategoryController::class, 'sub_claim_category'])->name('sub_claim_category');
Route::get('/get_subcategory_list', [App\Http\Controllers\CategoryController::class, 'get_subcategory_list'])->name('get_subcategory_list');
Route::get('/add_subcategory', [App\Http\Controllers\CategoryController::class, 'add_subcategory'])->name('add_subcategory');
Route::get('/view_subcategory/{id}', [App\Http\Controllers\CategoryController::class, 'view_subcategory'])->name('view_subcategory');
Route::get('/edit_subcategory/{id}', [App\Http\Controllers\CategoryController::class, 'edit_subcategory'])->name('edit_subcategory');
Route::post('/update_subcategory_submit', [App\Http\Controllers\CategoryController::class, 'update_subcategory_submit'])->name('update_subcategory_submit');
Route::post('/add_subcategory_submit', [App\Http\Controllers\CategoryController::class, 'subcategorysubmit'])->name('add_category_submit');
Route::get('/delete_subcategory/{id}', [App\Http\Controllers\CategoryController::class, 'delete_subcategory'])->name('delete_subcategory');
Route::post('/delete_multi_subcategory', [App\Http\Controllers\CategoryController::class, 'delete_multi_subcategory'])->name('delete_multi_subcategory'); 

########################################################################################################


##############################  Trip type mgmt details#########################################


Route::get('/trip_type_mgmt', [App\Http\Controllers\TripController::class, 'index'])->name('trip_type_mgmt');
Route::get('/get_triptype_list', [App\Http\Controllers\TripController::class, 'get_triptype_list'])->name('get_triptype_list');
Route::get('/add_triptype', [App\Http\Controllers\TripController::class, 'add_triptype'])->name('add_triptype');
Route::get('/view_triptype/{id}', [App\Http\Controllers\TripController::class, 'view_triptype'])->name('view_triptype');
Route::get('/edit_triptype/{id}', [App\Http\Controllers\TripController::class, 'edit_triptype'])->name('edit_triptype');
Route::post('/update_triptype_submit', [App\Http\Controllers\TripController::class, 'update_triptype_submit'])->name('update_triptype_submit');
Route::post('/add_triptype_submit', [App\Http\Controllers\TripController::class, 'submit'])->name('add_triptype_submit');
Route::get('/delete_triptype/{id}', [App\Http\Controllers\TripController::class, 'delete_triptype'])->name('delete_triptype');
Route::post('/delete_multi_triptype', [App\Http\Controllers\TripController::class, 'delete_multi_triptype'])->name('delete_multi_triptype'); 

########################################################################################################
Route::get('/view_user/{id}', [App\Http\Controllers\TripController::class, 'view_user'])->name('view_user');

