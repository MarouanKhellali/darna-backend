<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\OrderContoller;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertiesController;
use App\Http\Controllers\PropertyReviewController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SellerController;

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
    Route::post('/signup',[AuthController::class,'signup'] );
    Route::post('/login',[AuthController::class,'login'] );

Route::group(['middleware' => 'check.token'], function () {


    Route::get('/showSellers',[SellerController::class,'show'] );
    Route::get('/deleteSeller',[SellerController::class,'delete'] );
    Route::post('/acceptSeller',[SellerController::class,'acceptSeller'] );
    Route::post('/refuseSeller',[SellerController::class,'refuseSeller'] );
    
    Route::get('/showClients',[ClientController::class,'show'] );
    Route::get('/deleteClient',[ClientController::class,'delete'] );


    Route::get('/showMsgs',[ChatController::class,'show'] );
    Route::get('/showMessagedUsers',[ChatController::class,'showMessagedUsers'] );
    Route::post('/storeMsgs',[ChatController::class,'store'] );
    Route::post('/updateStatus',[ChatController::class,'update'] );

    Route::get('/showOrders',[OrderContoller::class,'show'] );
    Route::post('/storeOrder',[OrderContoller::class,'store'] );
    Route::post('/updateOrder',[OrderContoller::class,'update'] );


    Route::get('/showProperties',[PropertiesController::class,'show'] );
    Route::post('/storeProperty',[PropertiesController::class,'store'] );
    Route::get('/deleteProperty',[PropertiesController::class,'delete'] );
    Route::get('/checkProperty',[PropertiesController::class,'check'] );

    Route::get('/showReviews',[ReviewController::class,'show'] );
    Route::post('/storeReviews',[ReviewController::class,'store'] );
    Route::get('/checkReview',[ReviewController::class,'check'] );

    Route::get('/showPropertyReviews',[PropertyReviewController::class,'show'] );
    Route::post('/storePropertyReviews',[PropertyReviewController::class,'store'] );

    Route::get('/showProfile',[ProfileController::class,'show'] );
    Route::post('/updateUser',[AuthController::class,'updateUser'] );
    Route::post('/updatePhoto',[ProfileController::class,'updatePhoto'] );

  
    Route::get('/logout',[AuthController::class,'logout'] );
});
