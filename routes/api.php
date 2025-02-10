<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\UserProfileController;
use App\Http\Controllers\Message\MessageController;
use App\Http\Controllers\Message\MessageReplyController;
use App\Http\Controllers\Property\PropertyController;
use App\Http\Controllers\Property\PropertyImageController;
use App\Http\Controllers\Property\SubPropertiesController;
use App\Http\Controllers\Property\FloorPlansController;
use App\Http\Controllers\Property\PropertyAttachmentController;

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

        // Properties-related routes
        Route::prefix('properties')->group(function () {
            // General-properties related routes
            Route::controller(PropertyController::class)->group(function () {
                Route::get('/get-user', 'getUserProperties');
                Route::post('/create-or-update/{property?}', 'createOrUpdate');
                Route::get('/edit/{property}', 'edit');
            });

            // Property-images related routes
            Route::controller(PropertyImageController::class)->group(function () {
                Route::post('/images/create-or-update/{property}', 'imagesCreateOrUpdate');
                Route::get('/images/edit/{property}', 'getImages');
                Route::post('/image/delete/{property}/{image}', 'deleteImage');
                Route::post('/thumbnail/update/{property}/{image}', 'updateThumbnail');
            });

            // Property-attachments related routes
            Route::controller(PropertyAttachmentController::class)->group(function () {
                Route::post('/attachments/create-or-update/{property}', 'storeOrUpdate');
                Route::get('/attachments/edit/{property}', 'edit');
                Route::post('/attachments/delete/{attachment}', 'delete');
            });

            // Sub-properties related routes
            Route::controller(SubPropertiesController::class)->group(function () {
                Route::post('/{property}/sub-properties/{subProperty?}',  'createOrUpdate');
            });

            // Sub-properties related routes
            Route::controller(FloorPlansController::class)->group(function () {
                Route::post('/{property}/floor-plans/{floorPlan?}',  'createOrUpdate');
            });
        });


            Route::post('/messages/create', [MessageController::class, 'send']);
            Route::get('/messages', [MessageController::class, 'index']);
            Route::get('/messages/{message}', [MessageController::class, 'show']);
            Route::post('/messages/update/{message}', [MessageController::class, 'update']);
            Route::post('/messages/delete/{message}', [MessageController::class, 'destroy']);


            Route::post('/messages/{message}/replies', [MessageReplyController::class, 'reply']);

    });
});
