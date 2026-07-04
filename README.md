# Sistem Koperasi 6G - Front End Stack

## 🎯 Task Utama
1. Menerjemahkan design dari UI/UX ke code.
2. Membuat design responsive dan mengatur layouting sesuai standar.
3. Memastikan hasil respon data dari backend berhasil.

## 📝 Aturan Front End
1. Penamaan `id` dan `name` harus sesuai dengan kolom database. 
   *Contoh:* `<input type="text" id="username" name="username">`
2. Jika ada penggunaan spasi, gunakan underscore (`_`). 
   *Contoh:* `<input type="password" id="password_confirm" name="password_confirm">`
3. Memahami struktur file. **Jangan ubah bagian `layouts.app`** karena itu adalah standar baku untuk penggunaannya. Baca bagian `beranda` dan `auth/login`, diperkenankan jika ingin menambah sesuatu tanpa mengubah fungsi aslinya.
4. Membuat 1 dashboard utama. Dashboard ini nantinya akan dikontrol menggunakan hak akses. Buat dashboard admin terlebih dahulu, lalu batasi dengan hak akses pengguna.
5. Diperbolehkan melakukan cek data menggunakan dummy JavaScript.
6. Boleh menambahkan library baru jika memang diperlukan.

## 🚫 Larangan
1. Dilarang mengubah isi folder `app/`.

---

## 🆕 Pembaruan Front-End: CRUD Kategori Admin

Branch ini berisi pembaruan antarmuka (UI) untuk fitur Kelola Kategori di sisi Admin. Tampilan sudah dibuat responsif dan form sudah disiapkan agar siap diintegrasikan dengan database (Backend-Ready).

### ✨ Apa saja yang sudah dikerjakan di Front-End?
1. **Membuat View CRUD:** Telah dibuat halaman `index.blade.php` (Tabel Data), `create.blade.php` (Form Tambah), dan `edit.blade.php` (Form Ubah) di dalam folder `resources/views/admin/kategori/`.
2. **Form Backend-Ready:** Semua form input sudah dilengkapi dengan atribut `name`, pelindung keamanan `@csrf`, serta penyesuaian metode HTTP (`@method('PUT')` untuk update dan `@method('DELETE')` untuk hapus).
3. **Pemisahan Sidebar:** Telah dilakukan penyesuaian pada `sidebar.blade.php`. Menu Admin dan User saat ini sudah dipisah menggunakan logika URL sementara (`request()->is('admin*')`).

### 🚀 To-Do List untuk Tim Back-End:
Untuk melanjutkan fitur ini agar berfungsi penuh, berikut adalah hal-hal yang perlu disesuaikan di sisi Back-End:

1. **Routing & Controller:** 
   * Buat `KategoriController` untuk menangani logika CRUD.
   * Hapus *routing* statis sementara untuk UI di `routes/web.php` dan ganti dengan *routing* dinamis yang mengarah ke Controller.
2. **Koneksi Database:** 
   * Ganti data statis/hardcode (seperti Dummy Data di tabel, dan *value* pada form edit) dengan data dinamis dari *database* menggunakan tag `{{ $kategori->nama_kategori }}` dsb.
   * Ganti angka `1` dan `2` pada *action* form Edit dan Hapus dengan ID dari *database*.
3. **Penyesuaian Sidebar (PENTING!):**
   * Di file `sidebar.blade.php`, ubah logika pengecekan tampilan. Saat ini masih menggunakan pengecekan URL (`@if(request()->is('admin*'))`). Tolong diganti menggunakan sistem Autentikasi/RBAC (misalnya: `@if(auth()->user()->role == 'admin')`) jika sistem Login sudah jadi.
   * **Hapus Tombol Simulasi:** Di dalam `sidebar.blade.php`, terdapat tombol **"Simulasi Admin"** (di menu user) dan tombol **"Ke Halaman User"** (di menu admin). Tolong hapus kedua tombol tersebut jika sistem hak akses sudah berjalan normal, karena tombol tersebut sebelumnya hanya ditambahkan untuk mempermudah tes desain UI.