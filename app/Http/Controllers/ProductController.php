<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Menggabungkan data dari kedua branch agar tidak ada yang hilang
    private $products = [
        ['id' => 1, 'slug' => 'beras-pandan-wangi', 'nama' => 'Beras Pandan Wangi 5 Kg', 'kategori' => 'Sembako', 'icon' => '🌾', 'harga' => 68500, 'stok' => 120, 'berat' => '5 Kg', 'gambar' => 'produk/beras.jpg', 'deskripsi' => 'Beras premium dengan aroma pandan yang harum dan tekstur pulen.'],
        ['id' => 2, 'slug' => 'minyak-goreng', 'nama' => 'Minyak Goreng 2 Liter', 'kategori' => 'Minyak', 'icon' => '🫙', 'harga' => 34000, 'stok' => 90, 'berat' => '2 Liter', 'gambar' => 'produk/minyak.jpg', 'deskripsi' => 'Minyak goreng berkualitas tinggi.'],
        ['id' => 3, 'slug' => 'gula-pasir', 'nama' => 'Gula Pasir 1 Kg', 'kategori' => 'Sembako', 'icon' => '🍚', 'harga' => 17500, 'stok' => 60, 'berat' => '1 Kg', 'gambar' => 'produk/gula.jpg', 'deskripsi' => 'Gula pasir putih berkualitas premium.'],
        ['id' => 4, 'slug' => 'bayam-segar', 'nama' => 'Bayam Segar', 'kategori' => 'Sayuran', 'icon' => '🥬', 'harga' => 7000, 'stok' => 40, 'berat' => '250 gram', 'gambar' => 'produk/bayam.jpg', 'deskripsi' => 'Bayam segar dipanen setiap pagi.'],
        ['id' => 5, 'slug' => 'apel-fuji', 'nama' => 'Apel Fuji', 'kategori' => 'Buah', 'icon' => '🍎', 'harga' => 25000, 'stok' => 70, 'berat' => '1 Kg', 'gambar' => 'produk/apel.jpg', 'deskripsi' => 'Apel Fuji manis dengan kualitas premium.'],
        ['id' => 6, 'slug' => 'susu-uht', 'nama' => 'Susu UHT Ultra', 'kategori' => 'Minuman', 'icon' => '🥛', 'harga' => 8000, 'stok' => 100, 'berat' => '1 Liter', 'gambar' => 'produk/susu.jpg', 'deskripsi' => 'Susu UHT tinggi kalsium.'],
    ];

    public function index(Request $request, $kategori = null)
    {
        // Logika filter berdasarkan kategori (milikmu)
        $products = collect($this->products);
        
        if ($kategori) {
            $products = $products->where('kategori', ucfirst($kategori));
        }

        return view('templates.user.display_kategori_produk', [
            'products' => $products->values(),
            'categoryLabel' => $kategori ? ucfirst($kategori) : 'Semua Produk'
        ]);
    }

    public function show($slug)
    {
        // Logika detail produk (milik Dzaki)
        $product = collect($this->products)->firstWhere('slug', $slug);

        abort_if(!$product, 404);

        return view('produk.detail', compact('product'));
    }
}