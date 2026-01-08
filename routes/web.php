<?php

use App\Http\Controllers\ArsipPembayaranController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\HargaController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemPenawaranController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\KontrakController;
use App\Http\Controllers\MasterUserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PreOrderController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\SuplierController;
use App\Http\Controllers\TemplateOrderController;
use App\Http\Controllers\TipePembayaranController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public / Guest routes
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->middleware(['guest', 'throttle:login'])
    ->name('login.attempt');

// Authentication
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Landing / utility
    Route::get('/', function () {
        if (Auth::user()->level == 'admin') {
            return redirect('/master-user');
        }

        if (Auth::user()->level == 'pembayaran') {
            return redirect('/pembayaran');
        }

        return redirect('/preorder');
    })->name('home');

    Route::get('/konfirmasi', function () {
        return view('konfirmasi');
    });

    // Temporary routes
    Route::get('/stok', function () {
        return abort(403, 'Fitur masih dikembangkan');
    });

    Route::get('/dashboard', function () {
        return abort(403, 'Fitur masih dikembangkan');
    });

    // Gudang routes
    Route::get('/gudang/list-order', [GudangController::class, 'orderIndex']);

    // Suplier routes
    Route::get('/suplier/list-order', [SuplierController::class, 'orderIndex']);
    Route::get('/suplier/list-preorder', [SuplierController::class, 'preorderIndex']);

    // Pre Order routes
    Route::get('/preorder', [PreOrderController::class, 'index']);
    Route::get('/preorder/create', [PreOrderController::class, 'create']);
    Route::post('/preorder', [PreOrderController::class, 'store']);
    Route::post('/preorder/duplicate/{preorder}', [PreOrderController::class, 'duplicate']);
    Route::post('/preorder/template/{templateorder}', [PreOrderController::class, 'templateStore']);
    Route::post('/preorder/kirimWA/{preorder}', [PreOrderController::class, 'kirimSuplier']);
    Route::delete('/preorder/{preorder}', [PreOrderController::class, 'destroy']);
    Route::delete('/preorder/cancel/{preorder}', [PreOrderController::class, 'cancel']);
    Route::get('/preorder/notif-suplier', [PreOrderController::class, 'notifSuplier']);
    Route::get('/preorder/{preorder}', [PreOrderController::class, 'show']);
    Route::patch('/preorder/{preorder}', [PreOrderController::class, 'update']);
    Route::patch('/preorder/terima/{preorder}', [PreOrderController::class, 'updateTerima']);

    // Item Penawaran routes
    Route::post('/item-penawaran/create-from-template/{templateorder}', [ItemPenawaranController::class, 'storeFromTemplate']);
    Route::post('/item-penawaran/create-from-harga/{harga}', [ItemPenawaranController::class, 'storeFromHarga']);
    Route::get('/item-penawaran', [ItemPenawaranController::class, 'index']);
    // POTENTIAL DUPLICATE
    Route::get('/item-penawaran/delete/{itemPenawaran}', [ItemPenawaranController::class, 'destroy'])->name('item-penawaran.delete');
    Route::patch('/item-penawaran/{itemPenawaran}', [ItemPenawaranController::class, 'update']);
    Route::patch('/item-penawaran/bukti-gudang/{itemPenawaran}', [ItemPenawaranController::class, 'updateBuktiGudang']);
    // POTENTIAL DUPLICATE
    Route::delete('/item-penawaran/delete/{itemPenawaran}', [ItemPenawaranController::class, 'destroy'])->name('item-penawaran.delete');

    // Order routes
    Route::get('/order', [OrderController::class, 'index']);
    Route::get('/order/create', [OrderController::class, 'create']);
    Route::post('/order/dp/{order}', [OrderController::class, 'postDP']);
    Route::patch('/order/cancelOrderTerkirirm/{order}', [OrderController::class, 'cancelOrderTerkirim']);
    Route::delete('/order/{order}', [OrderController::class, 'destroy']);
    Route::post('/order/kirimSuplier/{order}', [OrderController::class, 'kirimSuplier']);
    Route::get('/order/notif-suplier', [OrderController::class, 'notifSuplier']);
    Route::get('/order/notif-gudang', [OrderController::class, 'notifGudang']);
    Route::get('/order/grab-spreadsheet', [OrderController::class, 'grabSpreadsheetKPI']);
    Route::get('/order/{order}', [OrderController::class, 'show']);
    Route::get('/order/kirimWAGudang/{order}', [OrderController::class, 'kirimWAGudang']);
    Route::get('/order/kirimWASuplier/{order}', [OrderController::class, 'kirimWASuplier']);
    Route::get('/order/suplier/{order}', [OrderController::class, 'showOrderSuplier']);
    Route::get('/order/gudang/{order}', [OrderController::class, 'showOrderGudang']);
    Route::post('/order', [OrderController::class, 'store']);
    Route::post('/order/kontrakstore/{harga}', [OrderController::class, 'kontrakStore']);
    Route::post('/order/fromTemplate/{templateorder}', [OrderController::class, 'fromTemplateStore']);
    Route::patch('/order/{order}', [OrderController::class, 'update']);
    Route::patch('/order/revisi/{order}', [OrderController::class, 'revisi']);

    // Pembayaran routes
    Route::get('/pembayaran', [PembayaranController::class, 'index']);
    Route::get('/pembayaran/create', [PembayaranController::class, 'create']);
    Route::get('/pembayaran/kirimWA/{pembayaran}', [PembayaranController::class, 'kirimWA']);
    Route::post('/pembayaran', [PembayaranController::class, 'store']);
    Route::post('/pembayaran/pendanaan', [PembayaranController::class, 'postPendanaan']);
    Route::delete('/pembayaran/{pembayaran}', [PembayaranController::class, 'destroy']);
    Route::get('/pembayaran/{pembayaran}', [PembayaranController::class, 'show']);
    Route::patch('/pembayaran/{pembayaran}', [PembayaranController::class, 'update']);

    // Master user routes (admin only)
    Route::middleware('ensure.admin')->group(function () {
        Route::get('/master-user', [MasterUserController::class, 'index']);
        Route::patch('/master-user/{user}/credentials', [MasterUserController::class, 'updateCredentials']);
        Route::get('/master-user/{user}', [MasterUserController::class, 'show']);
        Route::patch('/master-user/{user}', [MasterUserController::class, 'update']);
        Route::delete('/master-user/{user}', [MasterUserController::class, 'destroy']);
    });

    // Master Gudang routes
    Route::get('/gudang', [GudangController::class, 'index']);
    Route::get('/gudang/create', [GudangController::class, 'create']);
    Route::get('/gudang/{gudang}', [GudangController::class, 'show']);
    Route::post('/gudang', [GudangController::class, 'store']);
    Route::patch('/gudang/{gudang}', [GudangController::class, 'update']);

    // Master Cabang routes
    Route::get('/cabang', [CabangController::class, 'index']);
    Route::get('/cabang/create', [CabangController::class, 'create']);
    Route::get('/cabang/{cabang}', [CabangController::class, 'show']);
    Route::post('/cabang', [CabangController::class, 'store']);
    Route::patch('/cabang/{cabang}', [CabangController::class, 'update']);

    // Master suplier routes
    Route::get('/suplier', [SuplierController::class, 'index']);
    Route::get('/suplier/create', [SuplierController::class, 'create']);
    Route::get('/suplier/{suplier}', [SuplierController::class, 'show']);
    Route::post('/suplier', [SuplierController::class, 'store']);
    Route::patch('/suplier/{suplier}', [SuplierController::class, 'update']);

    // Master item routes
    Route::get('/item', [ItemController::class, 'index']);
    Route::get('/item/create', [ItemController::class, 'create']);
    Route::get('/item/{item}', [ItemController::class, 'show']);
    Route::post('/item', [ItemController::class, 'store']);
    Route::patch('/item/{item}', [ItemController::class, 'update']);

    // Master satuan routes
    Route::get('/satuan', [SatuanController::class, 'index']);
    Route::get('/satuan/create', [SatuanController::class, 'create']);
    Route::get('/satuan/{satuan}', [SatuanController::class, 'show']);
    Route::post('/satuan', [SatuanController::class, 'store']);
    Route::patch('/satuan/{satuan}', [SatuanController::class, 'update']);

    // Master jenis routes
    Route::get('/jenis', [JenisController::class, 'index']);
    Route::get('/jenis/create', [JenisController::class, 'create']);
    Route::get('/jenis/{jenis}', [JenisController::class, 'show']);
    Route::post('/jenis', [JenisController::class, 'store']);
    Route::patch('/jenis/{jenis}', [JenisController::class, 'update']);

    // Kontrak (non PO) routes
    Route::get('/nonpo', [KontrakController::class, 'index']);
    Route::get('/nonpo/{kontrak}', [KontrakController::class, 'show']);
    Route::post('/nonpo/{templateorder}', [KontrakController::class, 'store']);
    Route::patch('/nonpo/{kontrak}', [KontrakController::class, 'update']);
    Route::patch('/nonpo/update-date/{kontrak}', [KontrakController::class, 'updateDate']);
    Route::delete('/nonpo/{kontrak}', [KontrakController::class, 'destroy']);

    // Master template PO routes
    Route::get('/templateorder', [TemplateOrderController::class, 'index']);
    Route::get('/templateorder/create', [TemplateOrderController::class, 'create']);
    Route::get('/templateorder/{templateorder}', [TemplateOrderController::class, 'show']);
    Route::post('/templateorder', [TemplateOrderController::class, 'store']);
    Route::post('/templateorder/duplicate/{templateorder}', [TemplateOrderController::class, 'duplicate']);
    Route::patch('/templateorder/{templateorder}', [TemplateOrderController::class, 'update']);
    Route::patch('/templateorder/metaupdate/{templateorder}', [TemplateOrderController::class, 'metaUpdate']);
    Route::delete('/templateorder/{templateorder}', [TemplateOrderController::class, 'destroy']);

    // Harga routes
    Route::get('/harga', [HargaController::class, 'index']);
    Route::get('/harga/create', [HargaController::class, 'create']);
    Route::get('/harga/{harga}', [HargaController::class, 'show']);
    Route::post('/harga/duplicate/{harga}', [HargaController::class, 'duplicate']);
    Route::post('/harga', [HargaController::class, 'store']);
    Route::patch('/harga/{harga}', [HargaController::class, 'update']);
    Route::patch('/harga/metaupdate/{harga}', [HargaController::class, 'metaUpdate']);
    Route::delete('/harga/{harga}', [HargaController::class, 'destroy']);

    // Tipe pembayaran routes
    Route::patch('/tipe-pembayaran/{tipePembayaran}', [TipePembayaranController::class, 'update']);
    Route::get('/tipe-pembayaran', [TipePembayaranController::class, 'index']);
    Route::get('/tipe-pembayaran/create', [TipePembayaranController::class, 'create']);
    Route::get('/tipe-pembayaran/{tipePembayaran}', [TipePembayaranController::class, 'show']);
    Route::post('/tipe-pembayaran', [TipePembayaranController::class, 'store']);

    // Arsip pembayaran routes
    Route::get('/arsip', [ArsipPembayaranController::class, 'index']);
    Route::get('/arsip/{arsip}', [ArsipPembayaranController::class, 'show']);
    Route::post('/arsip/{pembayaran}', [ArsipPembayaranController::class, 'store']);

    // PDF generator routes
    Route::get('/order-pdf/{order}', [PDFController::class, 'orderPDF']);
    Route::get('/pembayaran-pdf/{pembayaran}', [PDFController::class, 'pembayaranPDF']);
    Route::get('/preview-pdf', function () {
        return view('pdf.preorder-pdf');
    });
});
