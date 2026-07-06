<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    //Halo ini Safira mencari Yandere
public function index(Request $request)
{
    $query = Barang::query();

    // 1. Text Search
    if ($request->filled('q')) {
        $searchTerm = $request->q;
        $query->where(function($q) use ($searchTerm) {
            $q->where('nama', 'like', '%' . $searchTerm . '%')
              ->orWhere('deskripsi', 'like', '%' . $searchTerm . '%');
        });
    }

    // 2. Filter by Harga (Price)
    if ($request->filled('harga_min')) {
        $query->where('harga', '>=', $request->harga_min);
    }
    if ($request->filled('harga_max')) {
        $query->where('harga', '<=', $request->harga_max);
    }
    
    // 3. Filter by Stok (Stock availability)
    if ($request->has('in_stock') && $request->in_stock == 'true') {
        $query->where('stok', '>', 0);
    }

    // Clone base query for category facets (so we get counts of categories based on other filters)
    $kategoriFacetQuery = clone $query;

    // 4. Filter by Kategori
    if ($request->filled('kategori')) {
        $kategori = is_array($request->kategori) ? $request->kategori : explode(',', $request->kategori);
        $query->whereIn('id_kategori', $kategori);
    }
    
    // Clone query to calculate price range in current selection
    $hargaFacetQuery = clone $query;

    // Execute query with pagination
    $perPage = $request->input('per_page', 15);
    $barang = $query->with('kategori')->paginate($perPage);

    // --- Calculate Facets ---
    
    // a. Kategori counts
    $kategoriFacets = $kategoriFacetQuery
        ->selectRaw('id_kategori, count(*) as count')
        ->groupBy('id_kategori')
        ->with('kategori:id_kategori,nama_kategori')
        ->get();

    $formattedKategoriFacets = $kategoriFacets->map(function ($item) {
        return [
            'id_kategori' => $item->id_kategori,
            'nama_kategori' => $item->kategori ? $item->kategori->nama_kategori : 'Unknown',
            'count' => $item->count
        ];
    });

    // b. Harga range (Min and Max available in the current filtered list)
    $hargaRange = $hargaFacetQuery
        ->selectRaw('MIN(harga) as min_harga, MAX(harga) as max_harga')
        ->first();

    return response()->json([
        'success' => true,
        'data' => $barang->items(),
        'meta' => [
            'current_page' => $barang->currentPage(),
            'last_page' => $barang->lastPage(),
            'per_page' => $barang->perPage(),
            'total' => $barang->total()
        ],
        'facets' => [
            'kategori' => $formattedKategoriFacets,
            'harga' => [
                'min' => $hargaRange->min_harga ?? 0,
                'max' => $hargaRange->max_harga ?? 0
            ]
        ]
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
