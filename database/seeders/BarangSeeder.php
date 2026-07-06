<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // 1. Buat Kategori
            $kategoriSembako = Kategori::firstOrCreate(
                ['nama_kategori' => 'Sembako'],
                ['satuan' => 'Kg']
            );

            $kategoriMinyak = Kategori::firstOrCreate(
                ['nama_kategori' => 'Minyak & Margarin'],
                ['satuan' => 'Liter']
            );

            $kategoriSnack = Kategori::firstOrCreate(
                ['nama_kategori' => 'Snack & Minuman'],
                ['satuan' => 'Pcs']
            );

            // 2. Buat Barang (Produk)
            $barangs = [
                [
                    'nama' => 'Beras Pandan Wangi Premium 5 Kg',
                    'stok' => 50,
                    'harga' => 68500.00,
                    'diskon_persen' => 0.00,
                    'deskripsi' => 'Beras premium dengan wangi pandan alami. Cocok untuk keluarga.',
                    'id_kategori' => $kategoriSembako->id_kategori,
                ],
                [
                    'nama' => 'Gula Pasir Gulaku 1 Kg',
                    'stok' => 100,
                    'harga' => 18000.00,
                    'diskon_persen' => 0.00,
                    'deskripsi' => 'Gula pasir kristal putih premium.',
                    'id_kategori' => $kategoriSembako->id_kategori,
                ],
                [
                    'nama' => 'Minyak Goreng Bimoli 2 Liter',
                    'stok' => 40,
                    'harga' => 34000.00,
                    'diskon_persen' => 5.00, // Diskon 5%
                    'deskripsi' => 'Minyak goreng kelapa sawit jernih.',
                    'id_kategori' => $kategoriMinyak->id_kategori,
                ],
                [
                    'nama' => 'Indomie Goreng Original (Dus)',
                    'stok' => 30,
                    'harga' => 110000.00,
                    'diskon_persen' => 2.50,
                    'deskripsi' => 'Indomie goreng isi 40 pcs.',
                    'id_kategori' => $kategoriSnack->id_kategori,
                ],
                [
                    'nama' => 'Teh Botol Sosro 450ml',
                    'stok' => 200,
                    'harga' => 6500.00,
                    'diskon_persen' => 0.00,
                    'deskripsi' => 'Minuman teh melati dalam botol.',
                    'id_kategori' => $kategoriSnack->id_kategori,
                ],
            ];

            foreach ($barangs as $item) {
                Barang::firstOrCreate(
                    ['nama' => $item['nama']],
                    $item
                );
            }
        });
    }
}

