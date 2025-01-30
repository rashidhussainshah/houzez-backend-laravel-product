<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\Property\FloorPlanController;
use App\Http\Controllers\Property\SubPropertyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\Property\PropertyController;
use App\Http\Controllers\Property\PropertyImageController;

Route::prefix('v1')->group(function () {
    // Authentication routes
    Route::controller(UserController::class)->group(function () {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
    });

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // User-related routes
        Route::controller(UserController::class)->group(function () {
            Route::get('/user', 'getUser');
            Route::post('/logout', 'logout');
            Route::post('/change-password', 'changePassword');
            Route::delete('/delete-account', 'deleteAccount');
        });

        // Profile-related routes
        Route::prefix('profile')->controller(UserProfileController::class)->group(function () {
            Route::get('/get-information',  'getProfileInformation');
            Route::post('/update-information', 'updateInformation');
            Route::get('/get-picture',  'getProfilePicture');
            Route::post('/update-picture',  'updateProfilePicture');
            Route::get('/get-social-media', 'getSocialMedia');
            Route::post('/update-social-media', 'updateSocialMedia');
        });

        Route::prefix('properties')->controller(PropertyController::class)->group(function () {
            Route::post('/create-or-update/{id?}', 'storeOrUpdate');
            Route::get('/edit/{property}', 'edit');
            Route::post('/images/create-or-update/{property}', 'imagesCreateOrUpdate');
            Route::get('/images/edit/{property}', 'egitImages');
            Route::post('/{property}/floor-plan/{floorPlanId?}',  'planStoreOrUpdate');
            Route::post('/{property}/sub-properties/{subPropertyId?}',  'subPropertyStoreOrUpdate');
        });

            Route::post('/messages/create', [MessageController::class, 'send']);
            Route::post('/messages/{message}/replies', [MessageController::class, 'reply']);
    });
});
