<?php

use App\Exports\LaporanExport;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomeTKBController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LaporanPembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PenjualanTokabeController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\SpbController;
use App\Http\Controllers\SpkController;
use App\Http\Controllers\SpjController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\kategoryController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TokenController;
use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\PenjualanBarang;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::group(['middleware' => ['status']], function () {

        // ====================================================================
        // ROUTE KHUSUS PEMILIK
        // ====================================================================
        Route::group(['middleware' => ['pemilik']], function () {
            Route::get('/add-user', [UserController::class, 'addUser']);
            Route::post('/post-user', [UserController::class, 'storeUser']);
            Route::get('/daftar-user', [UserController::class, 'index']);
            Route::get('/status-user/{id}', [UserController::class, 'status'])->name('user.status');
            Route::get('/edit-user/{id}', [UserController::class, 'edit'])->name('user.edit');
            Route::put('/update-user/{id}', [UserController::class, 'update'])->name('user.update');
            
            // ROUTE BUDGET
            Route::get('/tambah-budget', [BudgetController::class, 'tambah'])->name('budget.add');
            Route::get('/daftar-budget', [BudgetController::class, 'index'])->name('budget.list');
            Route::post('/store-budget', [BudgetController::class, 'store'])->name('budget.store');
            Route::get('/edit-budget/{id}', [BudgetController::class, 'edit'])->name('budget.edit');
            Route::put('/update-budget/{id}', [BudgetController::class, 'update'])->name('budget.update');
            Route::delete('/delete-budget/{id}', [BudgetController::class, 'delete'])->name('budget.delete');
            
            // ROUTE KATEGORI
            Route::get('/daftar-kategori', [kategoryController::class, 'daftarKategori'])->name('daftar.kategori');
            Route::get('/tambah-kategori', [kategoryController::class, 'tambahKategori'])->name('tambah.kategori');
            Route::post('/tambah-kategori', [kategoryController::class, 'storeKategori'])->name('store.kategori');
            Route::delete('/delete-kategori/{id}', [kategoryController::class, 'deleteKategori'])->name('destroy.kategori');
            Route::get('/edit-kategori/{id}', [kategoryController::class, 'editKategori']);
            Route::put('/edit-kategori/{id}', [kategoryController::class, 'updateKategori'])->name('put.kategori');
            
            // ROUTE PERUSAHAAN
            Route::get('/daftar-perusahaan', [PerusahaanController::class, 'index'])->name('perusahaan.list');
            Route::get('/tambah-perusahaan', [PerusahaanController::class, 'tambah'])->name('perusahaan.add');
            Route::post('/store-perusahaan', [PerusahaanController::class, 'store'])->name('perusahaan.store');
            Route::delete('/delete-perusahaan/{id}', [PerusahaanController::class, 'delete'])->name('perusahaan.delete');
            Route::get('/edit-perusahaan/{id}', [PerusahaanController::class, 'edit'])->name('perusahaan.edit');
            Route::put('/update-perusahaan/{id}', [PerusahaanController::class, 'update'])->name('perusahaan.update');
        });
        
        // ====================================================================
        // ROUTE ADMIN
        // ====================================================================
        Route::group(['middleware' => ['admin']], function () {
            
            Route::get('/', [HomeController::class, 'index']);
            
            // ROUTE INVOICE
            Route::get('/addInvoice', [PenjualanController::class, 'loadInvoice']);
            Route::post('/storeInvoice', [PenjualanController::class, 'tambahPenjualan'])->name('store.invoice');
            Route::get('/cetak/{id}', [InvoiceController::class, 'cetakInvoice']);
            Route::get('invoices/{selectedOption}', [InvoiceController::class, 'getInvoiceByOption']);
            Route::get('editInvoice/{id}/edit', [InvoiceController::class, 'editInvoice']);
            Route::put('updateInvoice/{id}', [InvoiceController::class, 'updateInvoice'])->name('update-invoice');
            Route::delete('invoice/deleteInvoice/{id}', [PenjualanController::class, 'destroy'])->name('delete.invoice');
            
            Route::get('/ubah-status-batal/{id}', [InvoiceController::class, 'statusBatal']);
            Route::post('/ubah-status-batal/{id}', [InvoiceController::class, 'storeStatusBatal']);
            Route::post('/ubah-status-lunas/{id}', [InvoiceController::class, 'statusLunas']);
            
            Route::post('/ubah-status-unlock/{id}', [InvoiceController::class, 'unlock'])->name('invoice.unlock');
            Route::get('/ubah-status-lock/{id}', [InvoiceController::class, 'lock'])->name('invoice.lock');
            
            Route::get('/invoice-approval/{id}', [InvoiceController::class, 'approvalPage'])
            ->middleware('auth', 'role:Pemilik') 
            ->name('invoice.approval.page');
            
             Route::get('/invoice-pelunasan/{id}', [InvoiceController::class, 'pelunasanPage'])
            ->middleware('auth', 'role:Pemilik') 
            ->name('invoice.pelunasan.page');
            
            Route::get('/invoice/update-log/{id}', [InvoiceController::class, 'getUpdateLog']);
            Route::get('/list-invoice', [InvoiceController::class, 'loadListInvoice'])->name('daftar_invoice');

            // TOKABE
            Route::get('/list-invoice/tokabe', [PenjualanTokabeController::class, 'listInvoiceTokabe'])->name('list_invoice_tokabe');
            Route::get('/addInvoiceTokabe', [PenjualanTokabeController::class, 'addInvoice'])->name('add.inv.tkb');
            Route::post('/addInvoiceTokabe', [PenjualanTokabeController::class, 'storeInvoiceTokabe'])->name('storeInvoice.Tokabe');
            Route::delete('/deleteInvoiceTokabe/{id}', [PenjualanTokabeController::class, 'delete'])->name('delete.invoiceTokabe');
            Route::get('editInvoiceTokabe/{id}/edit', [PenjualanTokabeController::class, 'editInvoice'])->name('edit.inv.tkb');
            Route::put('update-InvoiceTokabe/{id}', [PenjualanTokabeController::class, 'updateInvoice'])->name('update-invoiceTokabe');
            Route::get('/ubah-status/{status}/{id}', [PenjualanTokabeController::class, 'ubahStatus'])->name('ubahStatus.tkb');
            Route::get('/cetakTKB/{id}', [PenjualanTokabeController::class, 'cetakInvoiceTKB'])->name('cetak.inv.tkb');
            Route::get('/get-invoice-number', [PenjualanTokabeController::class, 'getUpdatedInvoiceNumber']);

            // TOKABE APPROVAL & PELUNASAN
            Route::post('/ubah-status-unlock-tokabe/{id}', [PenjualanTokabeController::class, 'unlock'])->name('tokabe.unlock');
            Route::get('/ubah-status-lock-tokabe/{id}', [PenjualanTokabeController::class, 'lock'])->name('tokabe.lock');
            Route::get('/tokabe-approval/{id}', [PenjualanTokabeController::class, 'approvalPage'])->middleware('role:Pemilik')->name('tokabe.approval.page');
            Route::get('/tokabe-pelunasan/{id}', [PenjualanTokabeController::class, 'pelunasanPage'])->middleware('role:Pemilik')->name('tokabe.pelunasan.page');
            Route::post('/ubah-status-batal-tokabe/{id}', [PenjualanTokabeController::class, 'storeStatusBatal'])->name('tokabe.status.batal');
            Route::post('/ubah-status-lunas-tokabe/{id}', [PenjualanTokabeController::class, 'statusLunas'])->name('tokabe.status.lunas');

            Route::get('/getInvoices/{id}', [CustomerController::class, 'getInvoices']);

            // ROUTE SPB
            Route::get('/daftar-spb', [SpbController::class, 'indexSPB'])->name('daftar-spb');
            Route::get('/cetak-spb/{id}', [SpbController::class, 'cetakSPB']);
            Route::get('/tambah-spb', [SpbController::class, 'tambahSPB']);
            Route::post('/store-spb', [SpbController::class, 'storeSPB']);
            Route::get('/edit-spb/{id}', [SpbController::class, 'editSPB']);
            Route::put('/update-spb/{id}', [SpbController::class, 'updateSPB']);
            Route::delete('/delete-spb/{id}', [SpbController::class, 'destroySPB']);
            Route::get('/ubah-status/{id}', [SpbController::class, 'ubahStatus'])->name('spb.ubah-status');

            // ROUTE SPK
            Route::get('/daftar-spk', [SpkController::class, 'indexSPK'])->name('daftar-spk');
            Route::get('/tambah-spk', [SpkController::class, 'addSPK']);
            Route::post('/store-spk', [SpkController::class, 'storeSPK']);
            Route::post('/store-spk-dari-invoice', [SpkController::class, 'storeDariInvoice'])->name('spk.store_dari_invoice');
            Route::post('/store-spk-tokabe', [PenjualanTokabeController::class, 'storeDariInvoiceTokabe'])->name('storeSpk.Tokabe');            
            Route::get('/edit-spk/{id}', [SpkController::class, 'editSPK']);
            Route::put('/update-spk/{id}', [SpkController::class, 'updateSPK']);
            Route::delete('/delete-spk/{id}', [SpkController::class, 'destroySPK']);
            Route::get('/cetak-spk/{id}', [SpkController::class, 'cetakSPK']);
            Route::get('/ubah-status-spk/{id}', [SpkController::class, 'statusSPK']);
            Route::get('/lihat-spk/{id}', [SpkController::class, 'lihatSPK']);
            Route::get('/ubah-proses-spk/{id}', [SpkController::class, 'prosesSPK']);

            // ROUTE SPJ
            Route::get('/daftar-spj', [SpjController::class, 'index'])->name('spj.index');
            Route::get('/tambah-spj', [SpjController::class, 'create']);
            Route::get('/generate-spj-number', [SpjController::class, 'generateNumber']);
            Route::post('/store-spj', [SpjController::class, 'store']);
            Route::get('/cetak-spj/{id}', [SpjController::class, 'cetak']);
            Route::get('/lihat-spj/{id}', [SpjController::class, 'lihat']);
            Route::delete('/delete-spj/{id}', [SpjController::class, 'destroy']);
            Route::put('/update-spj/{id}', [SpjController::class, 'update']);
            Route::get('/edit-spj/{id}', [SpjController::class, 'edit']);

            // ROUTE BARANG
            Route::get('listBarang', [BarangController::class, 'index'])->name('listBarang');
            Route::get('addBarang', [BarangController::class, 'create']);
            Route::post('barang', [BarangController::class, 'store'])->name('barang.store');
            Route::delete('deleteBarang/{id}', [BarangController::class, 'destroy']);
            Route::get('/edit-stok/{id}', [BarangController::class, 'edit']);
            Route::put('/update-stok/{id}', [BarangController::class, 'update']);
            
            // ROUTE MATERIAL
            Route::get('listMaterial', [MaterialController::class, 'index'])->name('listMaterial');
            Route::get('addMaterial', [MaterialController::class, 'create']);
            Route::post('material', [MaterialController::class, 'store'])->name('material.store');
            Route::delete('deleteMaterial/{id}', [MaterialController::class, 'destroy']);
            Route::get('/edit-stokMaterial/{id}', [MaterialController::class, 'edit']);
            Route::put('/update-stokMaterial/{id}', [MaterialController::class, 'update']);

             // ROUTE INVENTARIS
            Route::get('listInventaris', [InventarisController::class, 'index'])->name('listInventaris');
            Route::get('addInventaris', [InventarisController::class, 'create']);
            Route::post('inventaris', [InventarisController::class, 'store'])->name('inventaris.store');
            Route::delete('deleteInventaris/{id}', [InventarisController::class, 'destroy']);
            Route::get('/edit-stokInventaris/{id}', [InventarisController::class, 'edit']);
            Route::put('/update-stokInventaris/{id}', [InventarisController::class, 'update']);

            // ROUTE VENDOR & PEMBELIAN
            Route::get('/daftar-vendor', [VendorController::class, 'index'])->name('vendor.list');
            Route::get('/tambah-vendor', [VendorController::class, 'add'])->name('vendor.add');
            Route::post('/store-vendor', [VendorController::class, 'store'])->name('vendor.store');
            Route::get('/edit-vendor/{id}', [VendorController::class, 'edit'])->name('vendor.edit');
            Route::put('/update-vendor/{id}', [VendorController::class, 'update'])->name('vendor.update');
            Route::delete('/delete-vendor/{id}', [VendorController::class, 'destroy'])->name('vendor.delete');
            
            Route::get('/daftar-pembelian/{uid}', [PembelianController::class, 'index'])->name('pembelian.list');
            Route::get('/tambah-pembelian/{uid}', [PembelianController::class, 'addPembelian'])->name('pembelian.add');
            Route::post('/store-pembelian', [PembelianController::class, 'store'])->name('pembelian.store');
            Route::get('/edit-pembelian/{uid}', [PembelianController::class, 'edit'])->name('pembelian.edit');
            Route::put('/update-pembelian/{uid}', [PembelianController::class, 'update'])->name('pembelian.update');
            Route::delete('/delete-pembelian/{uid}', [PembelianController::class, 'destroy'])->name('pembelian.delete');
            Route::get('/lunas-pembelian/{uid}', [PembelianController::class, 'lunas'])->name('pembelian.lunas');

            // ROUTE DAFTAR CUSTOMER
            Route::get('/daftar-pelanggan', [CustomerController::class, 'index'])->name('daftar.pelanggan');
            Route::post('/pdf-pelanggan', [CustomerController::class, 'show'])->name('export.pelanggan');

            // ROUTE LAPORAN
            Route::get('/daftar-laporan', [LaporanController::class, 'index']);
            Route::post('/laporan-export', [LaporanController::class, 'exportByInvoice']);
            Route::post('/laporan-export-date', [LaporanController::class, 'exportByDate']);
            Route::post('/laporan-export-month', [LaporanController::class, 'exportByMonth']);
            Route::post('/laporan-export-year', [LaporanController::class, 'exportByYear']);
            Route::post('/laporan-penjualan-barang-export', [LaporanController::class, 'exportByBarang'])->name('export.barang');
            Route::post('/invoice-export', [LaporanController::class, 'exportInvoice'])->name('export.invoice');

            // ROUTE LAPORAN PEMBELIAN
            Route::get('/daftar-laporanPembelian', [LaporanPembelianController::class, 'index']);
            Route::post('/laporan-exportPembelian', [LaporanPembelianController::class, 'exportByVendor']);
            Route::post('/laporanPembelian-export-date', [LaporanPembelianController::class, 'exportByDate']);
            Route::post('/laporanPembelian-export-month', [LaporanPembelianController::class, 'exportByMonth']);
            Route::post('/laporanPembelian-export-year', [LaporanPembelianController::class, 'exportByYear']);
        });

        // ====================================================================
        // ROUTE PRODUKSI (route eksklusif tinta untuk role Produksi)
        // ====================================================================
        Route::group(['middleware' => ['produksi']], function () {
            Route::get('/stok-tinta', [BarangController::class, 'daftarTinta'])->name('stokTinta');
            Route::get('/tambah-tinta', [BarangController::class, 'addTinta'])->name('tambahTinta');
            Route::post('/stok-tinta/store', [BarangController::class, 'storeTinta'])->name('store.tinta');
            Route::get('/stok-tinta/edit/{id}', [BarangController::class, 'editStokTinta'])->name('edit.tinta');
            Route::delete('/tinta/delete/{id}', [BarangController::class, 'destroyTinta'])->name('delete.tinta');
            Route::put('/tinta/update/{id}', [BarangController::class, 'updateTinta'])->name('update.tinta');
        });
        
        // ====================================================================
        // ROUTE KARYAWAN
        // ====================================================================
        Route::group(['middleware' => ['karyawan']], function () {
            Route::get('/daftar-spk', [SpkController::class, 'indexSPK']);
            Route::get('/lihat-spk/{id}', [SpkController::class, 'lihatSPK']);
            Route::get('/ubah-proses-spk/{id}', [SpkController::class, 'prosesSPK']);
        });
        
        // ====================================================================
        // ROUTE UMUM (PROFILE & JSON)
        // ====================================================================
        Route::get('/profile', [UserController::class, 'profiles'])->name('auth.profile');
        Route::put('/profile/update/{id}', [UserController::class, 'updateProfile']);
        
        Route::get('invoice/get-barang-data/{kategoriId}', [BarangController::class, 'dataBarang']);
        Route::get('invoice/get-potensi-profit/{kategoriId}', [BarangController::class, 'potensiProfit']);

        // ROUTE TOKABE DASHBOARD
        Route::group(['middleware' => ['adminTKB']], function () {
            Route::get('/welcomeTKB', [HomeTKBController::class, 'index']);
        });
    
    });
    
    Route::post('/token-notif', TokenController::class)->name('token');
});

// ROUTE JSON
Route::get('/mark-as-read', [SpkController::class, 'markAsRead'])->name('baca-semua');

Route::get('/jenis-barang', function () {
    $jenis = Barang::all();
    return response()->json($jenis);
});
Route::get('/kategori-barang', function () {
    $jenis = KategoriBarang::all();
    return response()->json($jenis);
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// ROUTE PUBLIC DOWNLOAD (tanpa login)
Route::get('/view/download/{id}', [InvoiceController::class, 'viewDownloadInvoice'])->name('download.inv');
Route::get('/view/download/invoiceTKB/{id}', [PenjualanTokabeController::class, 'viewDownloadInvoiceTKB'])->name('download.inv.tkb');

Route::get('/invoice/public-download/{id}', [InvoiceController::class, 'publicDownload'])
    ->name('invoice.public_download')
    ->middleware('signed');
    
// ROUTE PUBLIC DOWNLOAD TOKABE (BERLAKU 7 HARI)
Route::get('/invoice-tkb/public-download/{id}', [PenjualanTokabeController::class, 'publicDownloadTokabe'])
    ->name('invoice_tkb.public_download')
    ->middleware('signed');
 
// TEST ROUTE
Route::get('/test', function () {
    return view('pages.laporan.pdf');
});