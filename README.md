# Front End Stack

## task 
1. menerjemahkan design dari ui/ux ke code 
2. membuat design responsive dan mengatur layouting sesuai standar
3. memastikan hasil respon data dari backend berhasil

## aturan front end
1. penamaan id dan name sesuai dengan kolom misal 
``
<input type="text" id="username" name="username">
``
2. jika ada penggunaan spasi gunakan underscore contoh password_confirm
``
<input type="password" id="password_confirm" name="password_confirm">
``
3. memahami struktur file jangan ubah bagian layouts.app karena standar baku untuk penggunaannya
baca bagian beranda dan auth/login diperkenankan jika ingin menambah sesuatu tanpa mengubah fungsi aslinya
4. membuat 1 dashboard yang akan dikontrol menggunakan hak akses nantinya jadi membuat dashboard admin saja lalu tinggal dibatasi oleh hak akses pengguna
5. diperbolehkan cek data menggunakan dummy javascript 
6. boleh menambahkan library baru jika diperlukan 

## larangan
1. mengubah isi folder app/




# Pembaruan Front-End: CRUD Kategori Admin

Branch ini berisi pembaruan antarmuka (UI) untuk fitur Kelola Kategori di sisi Admin. Tampilan sudah dibuat responsif dan form sudah disiapkan agar siap diintegrasikan dengan database (Backend-Ready).

### Apa saja yang sudah dikerjakan di Front-End?
1. **Membuat View CRUD:** Telah dibuat halaman `index.blade.php` (Tabel Data), `create.blade.php` (Form Tambah), dan `edit.blade.php` (Form Ubah) di dalam folder `resources/views/admin/kategori/`.
2. **Form Backend-Ready:** Semua form input sudah dilengkapi dengan atribut `name`, pelindung keamanan `@csrf`, serta penyesuaian metode HTTP (`@method('PUT')` untuk update dan `@method('DELETE')` untuk hapus).
3. **Pemisahan Sidebar:** Telah dilakukan penyesuaian pada `sidebar.blade.php`. Menu Admin dan User saat ini sudah dipisah menggunakan logika URL sementara (`request()->is('admin*')`).

### 🚀 To-Do List untuk Tim Back-End:
Untuk melanjutkan fitur ini agar berfungsi penuh, berikut adalah hal-hal yang perlu disesuaikan di sisi Back-End:

1. **Routing & Controller:** * Buat `KategoriController` untuk menangani logika CRUD.
   * Hapus *routing* statis sementara untuk UI di `routes/web.php` dan ganti dengan *routing* dinamis yang mengarah ke Controller.
2. **Koneksi Database:** * Ganti data statis/hardcode (seperti Dummy Data di tabel, dan *value* pada form edit) dengan data dinamis dari *database* menggunakan tag `{{ $kategori->nama_kategori }}` dsb.
   * Ganti angka `1` dan `2` pada *action* form Edit dan Hapus dengan ID dari *database*.
3. **Penyesuaian Sidebar (PENTING!):**
   * Di file `sidebar.blade.php`, ubah logika pengecekan tampilan. Saat ini masih menggunakan pengecekan URL (`@if(request()->is('admin*'))`). Tolong diganti menggunakan sistem Autentikasi/RBAC (misalnya: `@if(auth()->user()->role == 'admin')`) jika sistem Login sudah jadi.
   * **Hapus Tombol Simulasi:** Di dalam `sidebar.blade.php`, terdapat tombol **"Simulasi Admin"** (di menu user) dan tombol **"Ke Halaman User"** (di menu admin). Tolong hapus kedua tombol tersebut jika sistem hak akses sudah berjalan normal, karena tombol tersebut sebelumnya hanya ditambahkan untuk mempermudah tes desain UI.