# Implementation Plan: Tokabe Approval & Pelunasan

## Overview

Implementasi mekanisme approval dan pelunasan pada Invoice Tokabe mengikuti pola yang sudah berjalan di Invoice Ibekami. Perubahan mencakup migrasi database, modifikasi model dan controller, penambahan routes, serta pembuatan dan modifikasi views.

## Tasks

- [x] 1. Buat migration untuk menambah kolom approval pada tabel penjualan_tokabe
  - Buat file migration baru: `database/migrations/YYYY_MM_DD_HHMMSS_add_approval_to_penjualan_tokabe.php`
  - Tambah kolom `approval` string nullable default `'Lock'` setelah kolom `sisa_pembayaran`
  - Tambah kolom `approved_at` timestamp nullable setelah kolom `approval`
  - Tambah kolom `alasan_batal` text nullable setelah `approved_at` jika belum ada di tabel
  - Implementasikan method `down()` untuk rollback dengan `dropColumn`
  - _Requirements: 1.1, 1.2, 1.4_

- [x] 2. Update Model PenjualanTokabe
  - [x] 2.1 Tambah kolom baru ke `$fillable` di `app/Models/PenjualanTokabe.php`
    - Tambahkan `'approval'`, `'approved_at'`, dan `'alasan_batal'` ke array `$fillable`
    - _Requirements: 1.3_

  - [x] 2.2 Tulis property test untuk model PenjualanTokabe
    - **Property 2: Unlock mengisi kedua field secara atomik**
    - **Validates: Requirements 4.2**
    - **Property 3: Lock mengosongkan approved_at**
    - **Validates: Requirements 5.2**

- [x] 3. Tambah method baru di PenjualanTokabeController
  - [x] 3.1 Implementasi method `unlock($id)`
    - Cari record dengan `PenjualanTokabe::find($id)`, return error jika tidak ditemukan
    - Set `approval = 'Unlock'` dan `approved_at = now()`, lalu simpan
    - Tampilkan `Alert::success` dan redirect ke `route('list_invoice_tokabe')`
    - _Requirements: 4.1, 4.2, 4.3, 4.4_

  - [x] 3.2 Tulis property test untuk method unlock
    - **Property 2: Unlock mengisi kedua field secara atomik**
    - **Validates: Requirements 4.2**

  - [x] 3.3 Implementasi method `lock($id)`
    - Cari record dengan `PenjualanTokabe::find($id)`, return error jika tidak ditemukan
    - Set `approval = 'Lock'` dan `approved_at = null`, lalu simpan
    - Tampilkan `Alert::success` dan redirect ke `redirect()->back()`
    - _Requirements: 5.1, 5.2, 5.3, 5.4_

  - [x] 3.4 Tulis property test untuk method lock
    - **Property 3: Lock mengosongkan approved_at**
    - **Validates: Requirements 5.2**

  - [x] 3.5 Implementasi method `approvalPage($id)`
    - Ambil data invoice dengan `PenjualanTokabe::findOrFail($id)`
    - Ambil `$penjualan_barang` dari `PenjualanJasaTokabe::where('penjualan_id', $id)->get()`
    - Format `formatted_total_pembayaran` dengan `number_format`
    - Loop `$penjualan_barang` untuk mengambil data `$barang` dari model `Barang`
    - Return view `pages.invoices.tokabe.approval` dengan compact semua variabel
    - _Requirements: 6.1, 6.2, 6.3_

  - [x] 3.6 Implementasi method `pelunasanPage($id)`
    - Ambil data invoice dengan `PenjualanTokabe::findOrFail($id)`
    - Ambil `$penjualan_barang` dan loop untuk data `$barang`
    - Format `formatted_total_pembayaran` dan `formatted_sisa_pembayaran`
    - Return view `pages.invoices.tokabe.pelunasan` dengan compact semua variabel
    - _Requirements: 7.1, 7.2, 7.3, 7.4_

  - [x] 3.7 Implementasi method `statusLunas($id)`
    - Cari record dengan `PenjualanTokabe::find($id)`, return error jika tidak ditemukan
    - Set `status = 'Lunas'` dan `sisa_pembayaran = 0`, lalu simpan
    - Tampilkan `Alert::success` dan redirect ke `route('list_invoice_tokabe')`
    - _Requirements: 8.1, 8.2, 8.3, 8.4_

  - [x] 3.8 Tulis property test untuk method statusLunas
    - **Property 6: statusLunas mengubah status dan menolkan sisa pembayaran**
    - **Validates: Requirements 8.2**

  - [x] 3.9 Implementasi method `storeStatusBatal(Request $request, $id)`
    - Ambil record dengan `PenjualanTokabe::findOrFail($id)` (auto 404 jika tidak ada)
    - Set `status = 'Batal'` dan `alasan_batal = $request->alasan_batal`, lalu simpan
    - Redirect back dengan `->with('batal', '...')`
    - _Requirements: 9.1, 9.2, 9.3, 9.4_

  - [x] 3.10 Tulis property test untuk method storeStatusBatal
    - **Property 7: storeStatusBatal menyimpan alasan pembatalan**
    - **Validates: Requirements 9.2**

- [x] 4. Checkpoint — Pastikan semua tests pass
  - Pastikan semua tests pass, tanyakan kepada user jika ada pertanyaan.

- [x] 5. Modifikasi method yang sudah ada di PenjualanTokabeController
  - [x] 5.1 Modifikasi `listInvoiceTokabe()` — tambah auto-lock logic
    - Tambahkan blok `DB::table('penjualan_tokabe')->where(...)->update(...)` di awal method, sebelum query data
    - Kondisi: `approval = 'Unlock'`, `approved_at` tidak null, dan `approved_at < now()->subHours(6)`
    - Update: set `approval = 'Lock'` dan `approved_at = null`
    - Pastikan `use Illuminate\Support\Facades\DB;` sudah di-import
    - _Requirements: 2.1, 2.2, 2.3_

  - [x] 5.2 Tulis property test untuk auto-lock logic
    - **Property 1: Auto-lock hanya berlaku untuk record yang melewati batas waktu**
    - **Validates: Requirements 2.1, 2.2**

  - [x] 5.3 Modifikasi `updateInvoice()` — tambah auto-lock setelah update berhasil
    - Setelah `$penjualan->save()` yang terakhir (setelah semua item diproses), tambahkan:
      `$penjualan->approval = 'Lock'; $penjualan->approved_at = null; $penjualan->save();`
    - _Requirements: 12.4_

- [x] 6. Daftarkan 6 route baru di routes/web.php
  - Tambahkan semua route di dalam `Route::group(['middleware' => ['admin']])` yang sudah ada, di bagian TOKABE
  - Route POST `tokabe.unlock` → `PenjualanTokabeController@unlock`
  - Route GET `tokabe.lock` → `PenjualanTokabeController@lock`
  - Route GET `tokabe.approval.page` → `PenjualanTokabeController@approvalPage` dengan middleware `role:Pemilik`
  - Route GET `tokabe.pelunasan.page` → `PenjualanTokabeController@pelunasanPage` dengan middleware `role:Pemilik`
  - Route POST `tokabe.status.batal` → `PenjualanTokabeController@storeStatusBatal`
  - Route POST `tokabe.status.lunas` → `PenjualanTokabeController@statusLunas`
  - _Requirements: 10.1, 10.2, 10.3, 10.4, 10.5, 10.6_

  - [x] 6.1 Tulis property test untuk role-based access control
    - **Property 4: Akses halaman approval dan unlock hanya untuk Pemilik**
    - **Validates: Requirements 4.5, 6.4, 6.5**
    - **Property 5: Akses halaman pelunasan hanya untuk Pemilik**
    - **Validates: Requirements 7.5, 7.6**

- [x] 7. Buat view approval.blade.php untuk Invoice Tokabe
  - Buat file `resources/views/pages/invoices/tokabe/approval.blade.php`
  - Ikuti struktur `resources/views/pages/invoices/approval.blade.php` (Ibekami) sebagai template
  - Ubah judul menjadi "Konfirmasi Approval Invoice Tokabe"
  - Ubah form action ke `route('tokabe.unlock', $invoice->id)` (POST)
  - Ubah link kembali ke `route('list_invoice_tokabe')`
  - Tampilkan: nomor invoice, tanggal, nama customer/perusahaan, daftar item (nama barang, deskripsi, qty), total pembayaran
  - Gunakan `$jual->harga` (bukan `$jual->hargaBarang`) untuk kolom harga karena nama kolom di `penjualan_jasa_tokabe` adalah `harga`
  - _Requirements: 6.1, 6.2, 6.3, 13.1_

- [x] 8. Buat view pelunasan.blade.php untuk Invoice Tokabe
  - Buat file `resources/views/pages/invoices/tokabe/pelunasan.blade.php`
  - Ikuti struktur `resources/views/pages/invoices/pelunasan.blade.php` (Ibekami) sebagai template
  - Ubah judul menjadi "Konfirmasi Pelunasan Invoice Tokabe"
  - Tampilkan sisa pembayaran: `$invoice->formatted_sisa_pembayaran`
  - Form Lunas: action ke `route('tokabe.status.lunas', $invoice->id)` (POST)
  - Form Batal: action ke `route('tokabe.status.batal', $invoice->id)` (POST) dengan input `alasan_batal`
  - Ubah link kembali ke `route('list_invoice_tokabe')`
  - _Requirements: 7.1, 7.2, 7.3, 7.4, 13.2_

- [x] 9. Modifikasi view daftar-invoiceTokabe.blade.php
  - [x] 9.1 Tambah kolom "Approval" di header tabel
    - Tambahkan `<th>` baru untuk kolom approval di antara kolom Status dan Deskripsi
    - _Requirements: 11.1_

  - [x] 9.2 Tambah badge Lock/Unlock di baris tabel
    - Di dalam `@foreach ($penjualan as $inv)`, tambahkan `<td>` untuk badge approval
    - Jika `$inv->approval == 'Unlock'`: tampilkan `<span class="badge badge-soft-success p-2">Unlock</span>`
    - Jika selain itu (Lock): tampilkan `<span class="badge badge-soft-warning p-2">Lock</span>`
    - _Requirements: 11.1, 13.3_

  - [x] 9.3 Modifikasi dropdown aksi — tombol kondisional berdasarkan status approval
    - Ganti tombol "Edit" yang selalu tampil dengan logika kondisional:
      - Jika `$inv->approval == 'Unlock'`: tampilkan tombol "Edit" aktif dan tombol "Lock"
      - Jika `$inv->approval == 'Lock'` (atau null): tampilkan tombol "Minta Unlock" menuju `route('tokabe.approval.page', $inv->id)`
    - Hapus atau sembunyikan tombol "Lunas" dan "Invoice Batal" lama (digantikan oleh halaman pelunasan)
    - Tambahkan tombol "Pelunasan" yang hanya tampil jika `Auth::user()->role === 'Pemilik'` dan `$inv->status == 'Belum Lunas'`
    - _Requirements: 11.2, 11.3, 11.4, 11.5, 11.6_

  - [x] 9.4 Tulis property test untuk rendering view daftar invoice
    - **Property 8: Rendering daftar invoice — tombol aksi sesuai status approval**
    - **Validates: Requirements 3.2, 3.3, 3.5, 11.2, 11.3, 11.4, 11.5**

- [x] 10. Modifikasi view edit-invoicesTokabe.blade.php
  - Tambahkan guard peringatan di awal section `@section('content')`, sebelum form edit
  - Jika `$inv->approval == 'Lock'`: tampilkan `<div class="alert alert-warning">` dengan pesan bahwa invoice terkunci dan link "Minta Unlock" menuju `route('tokabe.approval.page', $inv->id)`
  - _Requirements: 12.1, 12.2, 12.3_

- [x] 11. Checkpoint akhir — Pastikan semua tests pass
  - Pastikan semua tests pass, tanyakan kepada user jika ada pertanyaan.
  - Verifikasi: kolom `approval` dan `approved_at` ada di tabel setelah migrasi
  - Verifikasi: semua 6 route terdaftar dengan nama yang benar
  - Verifikasi: method baru ada di `PenjualanTokabeController`
  - Verifikasi: `approval` dan `approved_at` ada di `$fillable` model

## Notes

- Tasks bertanda `*` bersifat opsional dan dapat dilewati untuk implementasi lebih cepat
- Setiap task mereferensikan requirements spesifik untuk traceability
- Gunakan `RealRashid\SweetAlert\Facades\Alert` untuk semua notifikasi (konsisten dengan Ibekami)
- Kolom `alasan_batal` perlu dicek apakah sudah ada di tabel sebelum ditambahkan di migration
- Referensi implementasi Ibekami ada di `InvoiceController` dan `resources/views/pages/invoices/`
