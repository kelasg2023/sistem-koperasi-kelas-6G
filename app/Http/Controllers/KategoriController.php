<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;

class KategoriController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $kategori = Kategori::all();
        return $this->successResponse($kategori, 'Data kategori berhasil diambil');
    }

    public function show($id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return $this->errorResponse('Kategori tidak ditemukan', 404);
        }

        return $this->successResponse($kategori, 'Data kategori berhasil diambil');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:50',
            'satuan' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        $kategori = Kategori::create($request->all());

        return $this->successResponse($kategori, 'Kategori berhasil ditambahkan', 201);
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return $this->errorResponse('Kategori tidak ditemukan', 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'sometimes|required|string|max:50',
            'satuan' => 'sometimes|required|string|max:10',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        $kategori->update($request->all());

        return $this->successResponse($kategori, 'Kategori berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return $this->errorResponse('Kategori tidak ditemukan', 404);
        }

        // Cek apakah ada barang yang masih pakai kategori ini
        if ($kategori->barangs()->count() > 0) {
            return $this->errorResponse('Kategori tidak bisa dihapus karena masih digunakan oleh beberapa barang', 400);
        }

        $kategori->delete();

        return $this->successResponse(null, 'Kategori berhasil dihapus');
    }
}
