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