<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'auth'], function ($router) 
{
	Route::post('/login', [AuthController::class, 'login']);
	Route::post('/logout', [AuthController::class, 'logout']);
	Route::post('/refresh_token', [Authcontroller::class , 'refresh_token'])->name('refresh_token');
	Route::post('/hrmstokengeneration', [AuthController::class, 'hrmstokengeneration']);
});

Route::group(['middleware' => ['jwt.verify']], function ()
{
	Route::get('/branches', [AuthController::class, 'list_branch']);
	Route::get('/categories', [AuthController::class, 'list_category']);
	Route::get('/categorieswithpolicy', [AuthController::class, 'categorieswithpolicy']);
	Route::get('/tripTypes', [AuthController::class, 'list_triptype']);
	Route::get('/claimList', [AuthController::class, 'claimList']);
	Route::get('/user-profile', [AuthController::class, 'userProfile']); 
	Route::post('/employeeNames', [AuthController::class, 'employeeNames']);    
	Route::post('/approvalStatus', [AuthController::class, 'approvalStatus']);    
	Route::post('/policies', [AuthController::class, 'policies']);   
	Route::post('/fileUpload', [AuthController::class, 'fileUpload']);
	Route::post('/tripClaim', [AuthController::class, 'tripClaim']);    
	Route::post('/employeeStatus', [AuthController::class, 'employeeStatus']);   
	Route::post('/claimResubmit', [AuthController::class, 'claimResubmit']);    
	Route::get('/claimsForApproval', [AuthController::class, 'claimsForApproval']);      
	Route::post('/notificationList', [AuthController::class, 'notificationList']);    //not done
	Route::post('/notificationChange', [AuthController::class, 'notificationChange']);    //not done
	Route::post('/storeAttendance', [AuthController::class, 'storeAttendance']);
	Route::post('/userUpdate', [AuthController::class, 'userUpdate']);
	
});