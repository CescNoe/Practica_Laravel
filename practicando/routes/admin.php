<?php
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\VehicleController;   
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\ModelController;
use BaconQrCode\Common\Mode;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdminController::class, 'index']);
Route::resource('vehicles', VehicleController::class)->names('admin.vehicles');
Route::resource('brands', BrandController::class)->names('admin.brands');
Route::resource('models', ModelController::class)->names('admin.models');
Route::get('vehicles/models/{brand_id}', [VehicleController::class, 'getModelsByBrand']);