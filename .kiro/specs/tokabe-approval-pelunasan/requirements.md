# Requirements Document

## Introduction

Fitur ini menerapkan mekanisme **Approval & Pelunasan** pada Invoice Tokabe, mengikuti pola yang sudah berjalan di Invoice Ibekami. Saat ini, Admin dapat langsung mengubah status invoice Tokabe tanpa izin dari Pemilik, dan tidak ada halaman khusus pelunasan. Fitur ini akan menambahkan alur unlock/lock berbasis persetujuan Pemilik, halaman approval, dan halaman pelunasan — sehingga kedua sistem invoice konsisten dalam hal kontrol akses dan alur kerja.

---

## Glossary

- **Admin**: Pengguna dengan role `Admin` atau `AdminTKB` yang mengelola invoice sehari-hari.
- **Pemilik**: Pengguna dengan role `Pemilik` yang memiliki wewenang memberikan izin unlock dan memproses pelunasan.
- **Invoice Tokabe**: Record penjualan pada tabel `penjualan_tokabe`, dikelola oleh `PenjualanTokabeController`.
- **Invoice Ibekami**: Record penjualan pada tabel `penjualan`, dikelola oleh `InvoiceController` — digunakan sebagai referensi implementasi.
- **Approval**: Kolom pada tabel `penjualan_tokabe` yang menyimpan status kunci invoice (`Lock` atau `Unlock`).
- **Approved_At**: Kolom timestamp pada tabel `penjualan_tokabe` yang mencatat waktu invoice di-unlock.
- **Status Lock**: Kondisi default invoice di mana tombol edit tidak tersedia bagi Admin.
- **Status Unlock**: Kondisi sementara (maksimal 6 jam) di mana Admin diizinkan mengedit invoice.
- **Timeout Unlock**: Durasi 6 jam sejak `approved_at`; setelah lewat, sistem otomatis mengembalikan status ke `Lock`.
- **Halaman Approval**: Halaman yang hanya dapat diakses Pemilik untuk melihat detail invoice dan memberikan izin unlock.
- **Halaman Pelunasan**: Halaman yang hanya dapat diakses Pemilik untuk memproses perubahan status invoice menjadi Lunas atau Batal.
- **PenjualanTokabeController**: Controller Laravel yang menangani semua operasi Invoice Tokabe.
- **PenjualanTokabe**: Model Eloquent yang merepresentasikan tabel `penjualan_tokabe`.

---

## Requirements

### Requirement 1: Migrasi Database — Penambahan Kolom Approval

**User Story:** Sebagai Developer, saya ingin menambahkan kolom `approval` dan `approved_at` pada tabel `penjualan_tokabe`, agar mekanisme lock/unlock dapat berjalan di Invoice Tokabe.

#### Acceptance Criteria

1. THE `penjualan_tokabe` table SHALL memiliki kolom `approval` bertipe string nullable dengan nilai default `Lock`.
2. THE `penjualan_tokabe` table SHALL memiliki kolom `approved_at` bertipe timestamp nullable.
3. THE `PenjualanTokabe` model SHALL menyertakan `approval` dan `approved_at` dalam array `$fillable`.
4. WHEN migrasi dijalankan pada database yang sudah memiliki data, THE Migration SHALL menambahkan kolom tanpa menghapus data yang ada.

---

### Requirement 2: Mekanisme Auto-Lock Saat Halaman Daftar Dimuat

**User Story:** Sebagai Pemilik, saya ingin invoice yang sudah melewati batas waktu unlock otomatis terkunci kembali, agar tidak ada invoice yang terbuka tanpa batas waktu.

#### Acceptance Criteria

1. WHEN `PenjualanTokabeController::listInvoiceTokabe()` dipanggil, THE System SHALL memeriksa semua record `penjualan_tokabe` dengan `approval = 'Unlock'` dan `approved_at` lebih dari 6 jam yang lalu.
2. WHEN record memenuhi kondisi timeout tersebut, THE System SHALL mengubah nilai `approval` menjadi `Lock` dan mengosongkan `approved_at` menjadi `null`.
3. THE System SHALL memproses auto-lock sebelum mengambil data penjualan untuk ditampilkan ke view.

---

### Requirement 3: Alur Permintaan Unlock oleh Admin

**User Story:** Sebagai Admin, saya ingin dapat meminta izin unlock kepada Pemilik melalui tombol di halaman daftar invoice, agar saya bisa mengedit invoice yang sudah terkunci.

#### Acceptance Criteria

1. WHEN Admin mengakses halaman daftar Invoice Tokabe, THE System SHALL menampilkan status lock/unlock pada setiap baris invoice.
2. WHEN status invoice adalah `Lock`, THE System SHALL menampilkan tombol "Minta Unlock" yang mengarahkan ke halaman approval Pemilik.
3. WHEN status invoice adalah `Unlock`, THE System SHALL menampilkan tombol "Edit" yang aktif dan tombol "Lock" untuk mengunci kembali.
4. WHEN Admin mengklik tombol "Lock", THE System SHALL memanggil endpoint lock dan mengubah `approval` menjadi `Lock`.
5. THE System SHALL menyembunyikan tombol "Edit" langsung pada baris invoice yang berstatus `Lock`.

---

### Requirement 4: Endpoint Unlock — Pemberian Izin oleh Pemilik

**User Story:** Sebagai Pemilik, saya ingin dapat memberikan izin unlock pada invoice Tokabe melalui halaman approval, agar Admin dapat melakukan perubahan yang diperlukan.

#### Acceptance Criteria

1. THE `PenjualanTokabeController` SHALL memiliki method `unlock($id)` yang dapat dipanggil via HTTP POST.
2. WHEN method `unlock($id)` dipanggil dengan ID invoice yang valid, THE System SHALL mengubah `approval` menjadi `Unlock` dan mengisi `approved_at` dengan waktu saat ini.
3. WHEN method `unlock($id)` dipanggil dengan ID invoice yang tidak ditemukan, THE System SHALL mengembalikan pesan error dan melakukan redirect kembali.
4. WHEN unlock berhasil, THE System SHALL menampilkan notifikasi sukses dan melakukan redirect ke halaman daftar Invoice Tokabe.
5. THE route untuk `unlock` SHALL hanya dapat diakses oleh pengguna dengan role `Pemilik`.

---

### Requirement 5: Endpoint Lock — Penguncian Kembali Invoice

**User Story:** Sebagai Admin atau Pemilik, saya ingin dapat mengunci kembali invoice Tokabe setelah selesai diedit, agar invoice terlindungi dari perubahan yang tidak disengaja.

#### Acceptance Criteria

1. THE `PenjualanTokabeController` SHALL memiliki method `lock($id)` yang dapat dipanggil via HTTP GET.
2. WHEN method `lock($id)` dipanggil dengan ID invoice yang valid, THE System SHALL mengubah `approval` menjadi `Lock`.
3. WHEN lock berhasil, THE System SHALL menampilkan notifikasi sukses dan melakukan redirect kembali ke halaman sebelumnya.
4. WHEN method `lock($id)` dipanggil dengan ID invoice yang tidak ditemukan, THE System SHALL mengembalikan pesan error dan melakukan redirect kembali.

---

### Requirement 6: Halaman Approval untuk Pemilik

**User Story:** Sebagai Pemilik, saya ingin memiliki halaman khusus yang menampilkan detail invoice Tokabe sebelum saya memberikan izin unlock, agar saya dapat memverifikasi isi invoice terlebih dahulu.

#### Acceptance Criteria

1. THE `PenjualanTokabeController` SHALL memiliki method `approvalPage($id)` yang mengembalikan view halaman approval.
2. WHEN Pemilik mengakses halaman approval, THE System SHALL menampilkan detail lengkap invoice Tokabe termasuk: nomor invoice, nama customer, daftar item/jasa, total pembayaran, dan status saat ini.
3. WHEN Pemilik mengakses halaman approval, THE System SHALL menampilkan tombol "Berikan Izin Unlock" yang mengirimkan POST request ke endpoint `unlock`.
4. THE route untuk halaman approval SHALL dilindungi dengan middleware `auth` dan `role:Pemilik`.
5. IF pengguna yang bukan Pemilik mencoba mengakses halaman approval, THEN THE System SHALL menolak akses dan mengembalikan response 403 atau redirect ke halaman yang sesuai.

---

### Requirement 7: Halaman Pelunasan untuk Pemilik

**User Story:** Sebagai Pemilik, saya ingin memiliki halaman khusus untuk memproses pelunasan invoice Tokabe, agar saya dapat mengubah status invoice menjadi Lunas atau Batal dengan kontrol penuh.

#### Acceptance Criteria

1. THE `PenjualanTokabeController` SHALL memiliki method `pelunasanPage($id)` yang mengembalikan view halaman pelunasan.
2. WHEN Pemilik mengakses halaman pelunasan, THE System SHALL menampilkan detail lengkap invoice Tokabe termasuk: nomor invoice, nama customer, daftar item/jasa, total pembayaran, sisa pembayaran, dan status saat ini.
3. WHEN Pemilik mengakses halaman pelunasan, THE System SHALL menampilkan tombol "Tandai Lunas" yang memanggil endpoint `statusLunas`.
4. WHEN Pemilik mengakses halaman pelunasan, THE System SHALL menampilkan tombol "Batalkan Invoice" yang memanggil endpoint `storeStatusBatal`.
5. THE route untuk halaman pelunasan SHALL dilindungi dengan middleware `auth` dan `role:Pemilik`.
6. IF pengguna yang bukan Pemilik mencoba mengakses halaman pelunasan, THEN THE System SHALL menolak akses dan mengembalikan response 403 atau redirect ke halaman yang sesuai.

---

### Requirement 8: Endpoint Status Lunas untuk Invoice Tokabe

**User Story:** Sebagai Pemilik, saya ingin dapat menandai invoice Tokabe sebagai Lunas dari halaman pelunasan, agar status pembayaran tercatat dengan benar.

#### Acceptance Criteria

1. THE `PenjualanTokabeController` SHALL memiliki method `statusLunas($id)` yang dapat dipanggil via HTTP POST.
2. WHEN method `statusLunas($id)` dipanggil dengan ID invoice yang valid, THE System SHALL mengubah `status` menjadi `Lunas` dan `sisa_pembayaran` menjadi `0`.
3. WHEN pelunasan berhasil, THE System SHALL menampilkan notifikasi sukses dan melakukan redirect ke halaman daftar Invoice Tokabe.
4. WHEN method `statusLunas($id)` dipanggil dengan ID invoice yang tidak ditemukan, THE System SHALL mengembalikan pesan error dan melakukan redirect kembali.

---

### Requirement 9: Endpoint Status Batal untuk Invoice Tokabe

**User Story:** Sebagai Pemilik, saya ingin dapat membatalkan invoice Tokabe dari halaman pelunasan dengan menyertakan alasan pembatalan, agar ada catatan yang jelas mengapa invoice dibatalkan.

#### Acceptance Criteria

1. THE `PenjualanTokabeController` SHALL memiliki method `storeStatusBatal(Request $request, $id)` yang dapat dipanggil via HTTP POST.
2. WHEN method `storeStatusBatal` dipanggil, THE System SHALL mengubah `status` invoice menjadi `Batal` dan menyimpan nilai `alasan_batal` dari request.
3. WHEN pembatalan berhasil, THE System SHALL melakukan redirect kembali dengan pesan sukses.
4. WHEN method `storeStatusBatal` dipanggil dengan ID invoice yang tidak ditemukan, THE System SHALL mengembalikan response 404.

---

### Requirement 10: Pendaftaran Routes Baru untuk Tokabe

**User Story:** Sebagai Developer, saya ingin semua endpoint baru terdaftar di `routes/web.php` dengan nama route yang konsisten, agar fitur dapat diakses dengan benar dari view.

#### Acceptance Criteria

1. THE `routes/web.php` SHALL mendaftarkan route POST `/ubah-status-unlock-tokabe/{id}` yang mengarah ke `PenjualanTokabeController@unlock` dengan nama `tokabe.unlock`.
2. THE `routes/web.php` SHALL mendaftarkan route GET `/ubah-status-lock-tokabe/{id}` yang mengarah ke `PenjualanTokabeController@lock` dengan nama `tokabe.lock`.
3. THE `routes/web.php` SHALL mendaftarkan route GET `/tokabe-approval/{id}` yang mengarah ke `PenjualanTokabeController@approvalPage` dengan nama `tokabe.approval.page`, dilindungi middleware `role:Pemilik`.
4. THE `routes/web.php` SHALL mendaftarkan route GET `/tokabe-pelunasan/{id}` yang mengarah ke `PenjualanTokabeController@pelunasanPage` dengan nama `tokabe.pelunasan.page`, dilindungi middleware `role:Pemilik`.
5. THE `routes/web.php` SHALL mendaftarkan route POST `/ubah-status-batal-tokabe/{id}` yang mengarah ke `PenjualanTokabeController@storeStatusBatal` dengan nama `tokabe.status.batal`.
6. THE `routes/web.php` SHALL mendaftarkan route POST `/ubah-status-lunas-tokabe/{id}` yang mengarah ke `PenjualanTokabeController@statusLunas` dengan nama `tokabe.status.lunas`.

---

### Requirement 11: Pembaruan UI — Halaman Daftar Invoice Tokabe

**User Story:** Sebagai Admin, saya ingin tampilan daftar Invoice Tokabe menampilkan status lock/unlock dan tombol aksi yang sesuai, agar saya tahu invoice mana yang bisa diedit dan mana yang perlu diminta unlock.

#### Acceptance Criteria

1. WHEN halaman `daftar-invoiceTokabe.blade.php` dirender, THE System SHALL menampilkan indikator visual (badge atau ikon) yang membedakan invoice berstatus `Lock` dan `Unlock`.
2. WHEN invoice berstatus `Lock`, THE System SHALL menampilkan tombol "Minta Unlock" di kolom aksi yang mengarahkan ke `route('tokabe.approval.page', $inv->id)`.
3. WHEN invoice berstatus `Unlock`, THE System SHALL menampilkan tombol "Edit" aktif yang mengarahkan ke `route('edit.inv.tkb', $inv->id)`.
4. WHEN invoice berstatus `Unlock`, THE System SHALL menampilkan tombol "Lock" yang mengarahkan ke `route('tokabe.lock', $inv->id)`.
5. WHEN invoice berstatus `Lock`, THE System SHALL menonaktifkan atau menyembunyikan tombol "Edit" langsung.
6. WHERE pengguna adalah Pemilik, THE System SHALL menampilkan tombol "Pelunasan" yang mengarahkan ke `route('tokabe.pelunasan.page', $inv->id)` untuk invoice yang berstatus `Belum Lunas`.

---

### Requirement 12: Pembaruan UI — Halaman Edit Invoice Tokabe

**User Story:** Sebagai Admin, saya ingin halaman edit Invoice Tokabe hanya dapat diakses ketika invoice berstatus Unlock, agar tidak ada perubahan yang dilakukan tanpa izin Pemilik.

#### Acceptance Criteria

1. WHEN Admin mengakses halaman edit Invoice Tokabe (`edit-invoicesTokabe.blade.php`), THE System SHALL memeriksa nilai `approval` pada invoice yang bersangkutan.
2. WHEN nilai `approval` adalah `Lock`, THE System SHALL menampilkan pesan peringatan bahwa invoice terkunci dan mengarahkan Admin untuk meminta unlock terlebih dahulu.
3. WHEN nilai `approval` adalah `Unlock`, THE System SHALL menampilkan form edit secara normal.
4. WHEN Admin berhasil menyimpan perubahan pada invoice yang berstatus `Unlock`, THE System SHALL secara otomatis mengubah `approval` kembali menjadi `Lock` setelah update berhasil.

---

### Requirement 13: Konsistensi Visual dengan Invoice Ibekami

**User Story:** Sebagai Pemilik, saya ingin tampilan halaman approval dan pelunasan Invoice Tokabe konsisten dengan tampilan Invoice Ibekami, agar pengalaman penggunaan seragam di seluruh sistem.

#### Acceptance Criteria

1. THE halaman approval Invoice Tokabe SHALL menggunakan layout dan komponen UI yang sama dengan `pages/invoices/approval.blade.php` milik Ibekami.
2. THE halaman pelunasan Invoice Tokabe SHALL menggunakan layout dan komponen UI yang sama dengan `pages/invoices/pelunasan.blade.php` milik Ibekami.
3. THE indikator status Lock/Unlock pada daftar Invoice Tokabe SHALL menggunakan badge dengan warna dan gaya yang sama dengan indikator pada daftar Invoice Ibekami.
