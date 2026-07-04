<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;

class BarangController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $barang = Barang::with('kategori')->get();
        return $this->successResponse($barang, 'Data barang berhasil diambil');
    }

    public function show($id)
    {
        $barang = Barang::with('kategori')->find($id);

        if (!$barang) {
            return $this->errorResponse('Barang tidak ditemukan', 404);
        }

        return $this->successResponse($barang, 'Data barang berhasil diambil');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'stok' => 'required|integer',
            'harga' => 'required|numeric',
            'diskon_persen' => 'nullable|numeric',
            'deskripsi' => 'nullable|string',
            'id_kategori' => 'required|integer|exists:kategori,id_kategori'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        $barang = Barang::create($request->all());

        return $this->successResponse($barang->load('kategori'), 'Barang berhasil ditambahkan', 201);
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return $this->errorResponse('Barang tidak ditemukan', 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|required|string|max:255',
            'stok' => 'sometimes|required|integer',
            'harga' => 'sometimes|required|numeric',
            'diskon_persen' => 'sometimes|nullable|numeric',
            'deskripsi' => 'sometimes|nullable|string',
            'id_kategori' => 'sometimes|required|integer|exists:kategori,id_kategori'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        $barang->update($request->all());

        return $this->successResponse($barang->load('kategori'), 'Barang berhasil diperbarui');
    }

    public function destroy($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return $this->errorResponse('Barang tidak ditemukan', 404);
        }

        $barang->delete();

        return $this->successResponse(null, 'Barang berhasil dihapus');
    }
}
