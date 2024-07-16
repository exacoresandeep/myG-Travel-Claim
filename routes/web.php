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

