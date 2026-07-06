<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponse;

class SupplierFeatureController extends Controller
{
    use ApiResponse;

    /**
     * Dapatkan daftar barang untuk opsi supplier
     */
    public function getBarangList()
    {
        $barangs = Barang::with('kategori')->orderBy('barang_id', 'desc')->get();
        return $this->successResponse($barangs, 'Berhasil mengambil daftar barang');
    }

    /**
     * Tambah pasokan barang (beserta nama merk dan harga)
     */
    public function addPasokan(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,barang_id',
            'jumlah' => 'required|integer|min:1',
            'nama_merk' => 'required|string|max:50',
            'harga_beli' => 'required|numeric|min:1',
        ]);

        try {
            DB::beginTransaction();

            $barang = Barang::lockForUpdate()->find($request->barang_id);
            $stok_awal = $barang->stok;
            $barang->stok += $request->jumlah;
            $barang->save();

            // Cari atau buat merk untuk barang ini
            $merk = \App\Models\Merk::firstOrCreate(
                ['nama_merk' => $request->nama_merk, 'barang_id' => $barang->barang_id]
            );

            // Simpan ke tabel supplier (sebagai data pasokan) dengan mode update or create
            $supplier = \App\Models\Supplier::where('merk_id', $merk->merk_id)->where('barang_id', $barang->barang_id)->first();
            if ($supplier) {
                $supplier->jumlah += $request->jumlah;
                $supplier->harga_beli = $request->harga_beli;
                $supplier->save();
            } else {
                \App\Models\Supplier::create([
                    'merk_id' => $merk->merk_id,
                    'barang_id' => $barang->barang_id,
                    'harga_beli' => $request->harga_beli,
                    'jumlah' => $request->jumlah,
                    'status' => 1
                ]);
            }

            // Catat mutasi stok
            DB::table('stok_history')->insert([
                'barang_id' => $barang->barang_id,
                'jumlah' => $request->jumlah,
                'stok_awal' => $stok_awal,
                'stok_akhir' => $barang->stok,
                'keterangan' => 'Pasokan dari supplier (Merk: ' . $request->nama_merk . ')',
                'stok_mutasi' => 'masuk',
                'created_at' => now(),
            ]);

            DB::commit();

            return $this->successResponse($barang, 'Berhasil mengirim pasokan stok beserta datanya!');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Gagal mengirim pasokan: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Daftarkan barang baru ke sistem (oleh Supplier)
     */
    public function storeBarang(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'id_kategori' => 'required|exists:kategori,id_kategori',
        ]);

        try {
            $barang = Barang::create([
                'nama' => $request->nama,
                'harga' => $request->harga,
                'deskripsi' => $request->deskripsi,
                'id_kategori' => $request->id_kategori,
                'stok' => 0, // Stok awal selalu 0 karena belum ada pasokan yang dikirim
                'diskon_persen' => 0
            ]);

            return $this->successResponse($barang, 'Barang baru berhasil ditambahkan! Anda kini bisa mengirimkan pasokannya.', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Gagal mendaftarkan barang: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Daftarkan kategori baru ke sistem (oleh Supplier)
     */
    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:50',
            'satuan' => 'required|string|max:10',
        ]);

        try {
            $kategori = \App\Models\Kategori::create([
                'nama_kategori' => $request->nama_kategori,
                'satuan' => $request->satuan
            ]);

            return $this->successResponse($kategori, 'Kategori baru berhasil ditambahkan!', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Gagal mendaftarkan kategori: ' . $e->getMessage(), 500);
        }
    }
}
