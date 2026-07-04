<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $products = [

        [
            'id' => 1,
            'slug' => 'beras-pandan-wangi',
            'nama' => 'Beras Pandan Wangi 5 Kg',
            'kategori' => 'Sembako',
            'icon' => '🌾',
            'harga' => 68500,
            'stok' => 120,
            'berat' => '5 Kg',
            'gambar' => 'produk/beras.jpg',
            'deskripsi' => 'Beras premium dengan aroma pandan yang harum dan tekstur pulen.'
        ],

        [
            'id' => 2,
            'slug' => 'minyak-goreng',
            'nama' => 'Minyak Goreng 2 Liter',
            'kategori' => 'Minyak',
            'icon' => '🫙',
            'harga' => 34000,
            'stok' => 90,
            'berat' => '2 Liter',
            'gambar' => 'produk/minyak.jpg',
            'deskripsi' => 'Minyak goreng berkualitas tinggi untuk kebutuhan memasak sehari-hari.'
        ],

        [
            'id' => 3,
            'slug' => 'gula-pasir',
            'nama' => 'Gula Pasir 1 Kg',
            'kategori' => 'Sembako',
            'icon' => '🍚',
            'harga' => 17500,
            'stok' => 60,
            'berat' => '1 Kg',
            'gambar' => 'produk/gula.jpg',
            'deskripsi' => 'Gula pasir putih berkualitas premium.'
        ],

        [
            'id' => 4,
            'slug' => 'bayam-segar',
            'nama' => 'Bayam Segar',
            'kategori' => 'Sayuran',
            'icon' => '🥬',
            'harga' => 7000,
            'stok' => 40,
            'berat' => '250 gram',
            'gambar' => 'produk/bayam.jpg',
            'deskripsi' => 'Bayam segar dipanen setiap pagi.'
        ],

        [
            'id' => 5,
            'slug' => 'apel-fuji',
            'nama' => 'Apel Fuji',
            'kategori' => 'Buah',
            'icon' => '🍎',
            'harga' => 25000,
            'stok' => 70,
            'berat' => '1 Kg',
            'gambar' => 'produk/apel.jpg',
            'deskripsi' => 'Apel Fuji manis dengan kualitas premium.'
        ],

        [
            'id' => 6,
            'slug' => 'susu-uht',
            'nama' => 'Susu UHT Ultra',
            'kategori' => 'Minuman',
            'icon' => '🥛',
            'harga' => 8000,
            'stok' => 100,
            'berat' => '1 Liter',
            'gambar' => 'produk/susu.jpg',
            'deskripsi' => 'Susu UHT tinggi kalsium.'
        ],

        [
    'id' => 7,
    'slug' => 'telur-ayam',
    'nama' => 'Telur Ayam 1 Kg',
    'kategori' => 'Sembako',
    'icon' => '🥚',
    'harga' => 28000,
    'stok' => 50,
    'berat' => '1 Kg',
    'gambar' => 'produk/telur.jpg',
    'deskripsi' => 'Telur ayam segar kualitas terbaik.'
],
[
    'id' => 8,
    'slug' => 'kentang-segar',
    'nama' => 'Kentang Segar',
    'kategori' => 'Sayuran',
    'icon' => '🥔',
    'harga' => 18000,
    'stok' => 60,
    'berat' => '1 Kg',
    'gambar' => 'produk/kentang.jpg',
    'deskripsi' => 'Kentang segar cocok untuk goreng atau rebus.'
],
[
    'id' => 9,
    'slug' => 'jeruk-manis',
    'nama' => 'Jeruk Manis',
    'kategori' => 'Buah',
    'icon' => '🍊',
    'harga' => 22000,
    'stok' => 80,
    'berat' => '1 Kg',
    'gambar' => 'produk/jeruk.jpg',
    'deskripsi' => 'Jeruk manis segar kaya vitamin C.'
],
[
    'id' => 10,
    'slug' => 'kopi-bubuk',
    'nama' => 'Kopi Bubuk 200 gr',
    'kategori' => 'Minuman',
    'icon' => '☕',
    'harga' => 35000,
    'stok' => 40,
    'berat' => '200 gr',
    'gambar' => 'produk/kopi.jpg',
    'deskripsi' => 'Kopi bubuk robusta dengan aroma khas.'
],
[
    'id' => 11,
    'slug' => 'mie-instan',
    'nama' => 'Mie Instan 1 Dus',
    'kategori' => 'Sembako',
    'icon' => '🍜',
    'harga' => 95000,
    'stok' => 30,
    'berat' => '1 Dus (40 pcs)',
    'gambar' => 'produk/mie.jpg',
    'deskripsi' => 'Mie instan favorit dengan berbagai rasa.'
],
[
    'id' => 12,
    'slug' => 'kacang-tanah',
    'nama' => 'Kacang Tanah 500 gr',
    'kategori' => 'Sembako',
    'icon' => '🥜',
    'harga' => 15000,
    'stok' => 45,
    'berat' => '500 gr',
    'gambar' => 'produk/kacang.jpg',
    'deskripsi' => 'Kacang tanah segar cocok untuk cemilan atau masakan.'
],


    ];

    public function index()
    {
        return view('produk.index', [
            'products' => $this->products
        ]);
    }

    public function show($slug)
    {
        $product = collect($this->products)->firstWhere('slug', $slug);

        abort_if(!$product,404);

        return view('produk.detail', compact('product'));
    }
}