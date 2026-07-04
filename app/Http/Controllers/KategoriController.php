<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();

        return response()->json([
            'success' => true,
            'data' => $kategori
        ]);
    }

    public function show($id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $kategori
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $kategori = Kategori::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Kategori created',
            'data' => $kategori
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori not found'
            ], 404);
        }

        $kategori->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Kategori updated',
            'data' => $kategori
        ]);
    }

    public function destroy($id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori not found'
            ], 404);
        }

        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori deleted'
        ]);
    }
}