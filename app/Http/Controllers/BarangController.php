<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    //Halo ini Safira mencari Yandere
public function index()
{
    $barang = Barang::all();

    return response()->json([
        'success' => true,
        'data' => $barang
    ]);
}

public function show($id)
{
    $barang = Barang::find($id);

    if (!$barang) {
        return response()->json([
            'success' => false,
            'message' => 'Barang not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $barang
    ]);
}

public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'stok' => 'required|integer',
        'harga' => 'required|numeric',
        'diskon_persen' => 'nullable|numeric',
        'deskripsi' => 'nullable|string',
        'id_kategori' => 'required|integer'
    ]);

    $barang = Barang::create($request->all());

    return response()->json([
        'success' => true,
        'message' => 'Barang created',
        'data' => $barang
    ], 201);
}

public function update(Request $request, $id)
{
    $barang = Barang::find($id);

    if (!$barang) {
        return response()->json([
            'success' => false,
            'message' => 'Barang not found'
        ], 404);
    }

    $barang->update($request->all());

    return response()->json([
        'success' => true,
        'message' => 'Barang updated',
        'data' => $barang
    ]);
}

public function destroy($id)
{
    $barang = Barang::find($id);

    if (!$barang) {
        return response()->json([
            'success' => false,
            'message' => 'Barang not found'
        ], 404);
    }

    $barang->delete();

    return response()->json([
        'success' => true,
        'message' => 'Barang deleted'
    ]);
}
}
