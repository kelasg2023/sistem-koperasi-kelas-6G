<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;

class BarangController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $query = Barang::query();

        // 1. Text Search (Optimized with Full-Text Search)
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->whereRaw('MATCH(nama, deskripsi) AGAINST(? IN BOOLEAN MODE)', [$searchTerm]);
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

        $responseData = [
            'items' => $barang->items(),
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
        ];

        return $this->successResponse($responseData, 'Data barang berhasil diambil');
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

        event(new \App\Events\DataUpdated(['type' => 'barang_created', 'data' => $barang]));

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

        event(new \App\Events\DataUpdated(['type' => 'barang_updated', 'data' => $barang]));

        return $this->successResponse($barang->load('kategori'), 'Barang berhasil diperbarui');
    }

    public function destroy($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return $this->errorResponse('Barang tidak ditemukan', 404);
        }

        $barang->delete();

        event(new \App\Events\DataUpdated(['type' => 'barang_deleted', 'data' => ['barang_id' => $id]]));

        return $this->successResponse(null, 'Barang berhasil dihapus');
    }
}
