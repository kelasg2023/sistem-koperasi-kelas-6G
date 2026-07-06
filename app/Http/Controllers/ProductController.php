<?php
// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function index(Request $request, $kategori = null)
    {
        $token   = Cookie::get('api_token');
        $baseUrl = env('API_BASE_URL', 'http://localhost:8000/api');

        // Prepare query parameters
        $queryParams = $request->all();
        if ($kategori) {
            $queryParams['kategori'] = $kategori;
        }

        try {
            $client = Http::withHeaders(['Accept' => 'application/json']);
            if ($token) {
                $client = $client->withToken($token);
            }

            // GET /api/barang?q=...&harga_min=...
            $res = $client->get($baseUrl . '/barang', $queryParams);
            
            if ($res->successful()) {
                $data = $res->json()['data'] ?? [];
            } else {
                $data = [];
            }
        } catch (\Exception $e) {
            $data = [];
        }

        $products = $data['items'] ?? [];
        $meta     = $data['meta'] ?? [];
        $facets   = $data['facets'] ?? [];

        // Restore brands mock data that the UI uses
        $brands = [
            ['id' => 1, 'name' => 'Rose Brand', 'checked' => true],
            ['id' => 2, 'name' => 'Bimoli', 'checked' => false],
            ['id' => 3, 'name' => 'Anak Raja', 'checked' => false],
            ['id' => 4, 'name' => 'Gulaku', 'checked' => false],
            ['id' => 5, 'name' => 'Topi Koki', 'checked' => false],
        ];

        return view('templates.user.display_kategori_produk', compact(
            'products',
            'meta',
            'facets',
            'brands'
        ));
    }

  

}