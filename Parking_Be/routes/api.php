<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminThongBaoController;
use App\Http\Controllers\BaiXeController;
use App\Http\Controllers\CanHoController;
use App\Http\Controllers\ChiTietChucVuController;
use App\Http\Controllers\ChucNangController;
use App\Http\Controllers\ChucVuController;
use App\Http\Controllers\CuDanController;
use App\Http\Controllers\GiaoDichController;
use App\Http\Controllers\LichSuRaVaoBaiXeController;
use App\Http\Controllers\LoaiXeController;
use App\Http\Controllers\ViTriDatController;
use App\Http\Controllers\XeController;
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
    Route::post('/doi-mat-khau', [AdminController::class, 'doiPass']);
    Route::get('/lay-du-lieu-profile', [AdminController::class, 'getDataProfile']);

    Route::group(['prefix'  =>  '/chuc-vu'], function () {
        Route::get('/lay-du-lieu', [ChucVuController::class, 'getData']);
        Route::post('/them-du-lieu', [ChucVuController::class, 'themChucVu']);
        Route::post('/thong-tin-cap-nhat', [ChucVuController::class, 'capnhatChucVu']);
        Route::post('/doi-trang-thai', [ChucVuController::class, 'doiTrangThaiChucVu']);
        Route::delete('/thong-tin-xoa/{id}', [ChucVuController::class, 'xoaChucVu']);
        Route::post('/thong-tin-tim', [ChucVuController::class, 'timChucVu']);
    });

    Route::group(['prefix'  =>  '/phan-quyen'], function () {
        Route::get('/data/{id_chuc_vu}', [ChiTietChucVuController::class, 'getData']);
        Route::post('/phan-quyen-chuc-vu/create', [ChiTietChucVuController::class, 'store']);
        Route::post('/phan-quyen-chuc-vu/delete', [ChiTietChucVuController::class, 'destroy']);
        Route::get('/chuc-nang/data', [ChucNangController::class, 'getData']);
    });

    Route::group(['prefix'  =>  '/cu-dan'], function () {
        Route::get('/lay-du-lieu', [CuDanController::class, 'getData']);
        Route::post('/them-du-lieu', [CuDanController::class, 'themCuDan']);
        Route::post('/thong-tin-cap-nhat', [CuDanController::class, 'capnhatCuDan']);
        Route::post('/doi-trang-thai', [CuDanController::class, 'doiTrangThaiCuDan']);
        Route::delete('/thong-tin-xoa/{id}', [CuDanController::class, 'xoaCuDan']);
        Route::post('/doi-mat-khau', [CuDanController::class, 'doiPass']);

    });

    Route::group(['prefix'  =>  '/can-ho'], function () {
        Route::get('/lay-du-lieu', [CanHoController::class, 'getData']);
        Route::post('/them-du-lieu', [CanHoController::class, 'themCanHo']);
        Route::post('/thong-tin-cap-nhat', [CanHoController::class, 'capnhatCanHo']);
        Route::delete('/thong-tin-xoa/{id}', [CanHoController::class, 'xoaCanHo']);
    });

    Route::group(['prefix'  =>  '/xe'], function () {
        Route::get('/lay-du-lieu', [XeController::class, 'getData']);
        Route::post('/them-du-lieu', [XeController::class, 'themXe']);
        Route::post('/thong-tin-cap-nhat', [XeController::class, 'capnhatXe']);
        Route::post('/doi-trang-thai', [XeController::class, 'doiTrangThaiXe']);
        Route::delete('/thong-tin-xoa/{id}', [XeController::class, 'xoaXe']);
    });
    Route::group(['prefix'  =>  '/loai-xe'], function () {
        Route::get('/lay-du-lieu', [LoaiXeController::class, 'getData']);
        Route::post('/them-du-lieu', [LoaiXeController::class, 'themLoaiXe']);
        Route::post('/thong-tin-cap-nhat', [LoaiXeController::class, 'capnhatLoaiXe']);
        Route::post('/doi-trang-thai', [LoaiXeController::class, 'doiTrangThaiLoaiXe']);
        Route::delete('/thong-tin-xoa/{id}', [LoaiXeController::class, 'xoaLoaiXe']);
    });


    Route::group(['prefix'  =>  '/lich-su-ra-vao'], function () {
        Route::get('/lay-du-lieu', [LichSuRaVaoBaiXeController::class, 'getLichSuRaVao']);
    });

    Route::group(['prefix'  =>  '/thong-bao'], function () {
        Route::get('/lay-du-lieu', [AdminThongBaoController::class, 'getData']);
    });

    Route::group(['prefix'  =>  '/giao-dich'], function () {
        Route::get('/lay-du-lieu', [GiaoDichController::class, 'getData']);
    });

    Route::group(['prefix'  =>  '/bai-xe'], function () {
        Route::get('/lay-du-lieu', [BaiXeController::class, 'getData']);
        Route::post('/them-du-lieu', [BaiXeController::class, 'themBaiXe']);
        Route::post('/thong-tin-cap-nhat', [BaiXeController::class, 'capnhatBaiXe']);
        Route::delete('/thong-tin-xoa/{id}', [BaiXeController::class, 'xoaBaiXe']);
    });

    Route::group(['prefix'  =>  '/vi-tri-dat'], function () {
        Route::get('/lay-du-lieu', [ViTriDatController::class, 'getData']);
        Route::post('/them-du-lieu', [ViTriDatController::class, 'themViTriDat']);
        Route::post('/thong-tin-cap-nhat', [ViTriDatController::class, 'capnhatViTriDat']);
        Route::delete('/thong-tin-xoa/{id}', [ViTriDatController::class, 'xoaViTriDat']);
    });











});
