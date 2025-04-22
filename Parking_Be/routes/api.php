<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChucVuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


///      ADMIN
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/register', [AdminController::class, 'register']);
Route::get('/admin/check-token', [AdminController::class, 'checkToken']);
Route::post('/admin/logout', [AdminController::class, 'logout']);

Route::group(['prefix'  =>  '/admin'], function () {
    Route::get('/lay-du-lieu', [AdminController::class, 'getData']);
    Route::post('/them-tai-khoan', [AdminController::class, 'themAdmin']);
    Route::post('/thong-tin-cap-nhat', [AdminController::class, 'capnhatAdmin']);
    Route::post('/doi-trang-thai', [AdminController::class, 'doiTrangThaiAdmin']);
    Route::delete('/thong-tin-xoa/{id}', [AdminController::class, 'xoaAdmin']);
    Route::post('/thong-tin-tim', [AdminController::class, 'timAdmin']);
    Route::put('/doi-mat-khau', [AdminController::class, 'doiPass']);
    Route::get('/lay-du-lieu-profile', [AdminController::class, 'getDataProfile']);

    Route::group(['prefix'  =>  '/chuc-vu'], function () {
        Route::get('/lay-du-lieu', [ChucVuController::class, 'getData']);
        Route::post('/them-du-lieu', [ChucVuController::class, 'themChucVu']);
        Route::post('/thong-tin-cap-nhat', [ChucVuController::class, 'capnhatChucVu']);
        Route::post('/doi-trang-thai', [ChucVuController::class, 'doiTrangThaiChucVu']);
        Route::delete('/thong-tin-xoa/{id}', [ChucVuController::class, 'xoaChucVu']);
        Route::post('/thong-tin-tim', [ChucVuController::class, 'timChucVu']);
    });
});
