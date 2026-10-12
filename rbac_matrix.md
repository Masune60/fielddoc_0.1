# Matrix Hak Akses RBAC - FieldDoc

Sistem manajemen dokumentasi foto lapangan vendor PLN menggunakan kontrol akses berbasis peran (RBAC) dengan 3 peran utama. Berikut adalah pemetaan role, permission, dan aturan akses:

## Daftar Permission

| Permission     | Deskripsi                                               |
| -------------- | ------------------------------------------------------- |
| `manage users` | Akses CRUD penuh pada Manajemen User dan Role           |
| `manage khs`   | Akses CRUD penuh pada Master KHS                        |
| `manage kr`    | Akses CRUD penuh pada Master Kontrak Rinci (KR)         |
| `manage tahap` | Membuat, mengedit, dan mengunci status Tahap Penagihan  |
| `create pk`    | Membuat data PK/SPK                                     |
| `edit pk`      | Mengedit data PK/SPK                                    |
| `view pk`      | Melihat data PK/SPK                                     |
| `upload foto`  | Mengunggah foto dokumentasi lapangan dengan geotag/EXIF |
| `delete foto`  | Menghapus foto sebelum dikunci                          |
| `export zip`   | Mengunduh paket berkas dokumentasi dalam format .ZIP    |

## Matriks Akses Berdasarkan Role

### 1. Admin Penagihan (Admin / Architect)

- **Akses Halaman:** Dashboard, Management User, Master KHS, Kontrak Rinci, Tahap Penagihan, Daftar PK/SPK, Export.
- **Hak Akses:** Penuh (Otomatis melalui `Gate::before`).
- **Permission Terkait (Secara Fungsional):** Semua permission.

### 2. Supervisor (Pengawas / Verifikator)

- **Akses Halaman:** Dashboard, Master KHS/KR (Read-Only), Tahap Penagihan, Daftar PK/SPK, Export.
- **Fitur Khusus:** Verifikasi/approval berkas penagihan.
- **Pembatasan Wajib:** DILARANG akses `manage users`, DILARANG mengubah/menghapus `manage khs`.
- **Permission yang Dimiliki:**
    - `manage kr` (Hanya view)
    - `manage tahap` (Hanya view / approve)
    - `create pk`
    - `edit pk`
    - `view pk`
    - `export zip`

### 3. Worker (Petugas Lapangan / Vendor)

- **Akses Halaman:** Dashboard, Daftar PK/SPK (hanya PK milik worker).
- **Fitur Khusus:** Unggah foto dan hapus foto sebelum dikunci.
- **Pembatasan Wajib:** DILARANG akses `manage users`, `manage khs`, `manage kr`, `manage tahap`, `export zip`.
- **Permission yang Dimiliki:**
    - `view pk`
    - `upload foto`
    - `delete foto`

## Lapisan Keamanan

1. **Middleware Keamanan Route:** Proteksi endpoint dengan `middleware(['role:...', 'permission:...'])` di `routes/web.php`.
2. **Blade Directive:** Elemen antarmuka seperti tombol dan menu navigasi disembunyikan menggunakan `@can`, `@hasrole`, atau `@hasanyrole`.
