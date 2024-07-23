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
	Route::post('/refresh', [AuthController::class, 'refresh']);
});

Route::group(['middleware' => ['jwt.verify']], function ()
{
	Route::get('/branches', [AuthController::class, 'list_branch']);
	Route::get('/categories', [AuthController::class, 'list_category']);
	Route::post('/categorieswithpolicy', [AuthController::class, 'categorieswithpolicy']);
	Route::get('/tripTypes', [AuthController::class, 'list_triptype']);
	Route::get('/claimList', [AuthController::class, 'claimList']);
	Route::get('/user-profile', [AuthController::class, 'userProfile']); 
	Route::get('/employeeNames', [AuthController::class, 'employeeNames']);    
	Route::post('/approvalStatus', [AuthController::class, 'approvalStatus']);    
	Route::post('/policies', [AuthController::class, 'policies']);   

	Route::post('/tripClaim', [AuthController::class, 'tripClaim']);    
	Route::post('/employeeStatus', [AuthController::class, 'employeeStatus']);   //not done 
	Route::post('/claimResubmit', [AuthController::class, 'claimResubmit']);    
	Route::get('/claimsForApproval', [AuthController::class, 'claimsForApproval']);      
	Route::post('/notificationList', [AuthController::class, 'notificationList']);    //not done
	Route::post('/notificationChange', [AuthController::class, 'notificationChange']);    //not done
});