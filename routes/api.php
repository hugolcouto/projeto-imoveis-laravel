<?php

use App\Http\Controllers\Api\V1\Admin\RegisterAdminController;
use App\Http\Controllers\Api\V1\Admin\ShowAdminController;
use App\Http\Controllers\Api\V1\Customer\RegisterCustomerController;
use App\Http\Controllers\Api\V1\Customer\ShowCustomerController;
use App\Http\Controllers\Api\V1\Property\ListPropertiesController;
use App\Http\Controllers\Api\V1\Property\RegisterPropertyController;
use App\Http\Controllers\Api\V1\Property\ShowPropertyController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Admin
    Route::prefix("admins")->group(function () {
        Route::post('/create', RegisterAdminController::class)->name("api.v1.admins.store");
        Route::get("/{id}", ShowAdminController::class)
            ->whereNumber('id')
            ->name("api.v1.admins.getById");
    });

    Route::prefix("property")->group(function () {
        Route::post('/create', RegisterPropertyController::class)->name('api.v1.properties.store');
        Route::get("/{slug}", ShowPropertyController::class)->name('api.v1.properties.getBySlug');
        Route::get("/", ListPropertiesController::class)->name('api.v1.properties.list');
    });

    Route::prefix('customer')->group(function () {
        Route::post('/create', RegisterCustomerController::class)->name('api.v1.customers.store');
        Route::get('/{id}', ShowCustomerController::class)
            ->whereNumber('id')
            ->name('api.v1.customers.getById');
    });
});
