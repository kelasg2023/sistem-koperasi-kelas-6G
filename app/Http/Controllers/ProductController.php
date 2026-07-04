<?php
// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request, $kategori = null)
    {
        // ==================== DAFTAR KATEGORI ====================
        $categoryList = [
            'minyak-lemak'     => 'Minyak & Lemak',
            'beras-tepung'     => 'Beras & Tepung',
            'makanan-kaleng'   => 'Makanan Kaleng',
            'sabun-kebersihan' => 'Sabun & Kebersihan',
            'minuman'          => 'Minuman',
            'bumbu-dapur'      => 'Bumbu Dapur',
            'mie-pasta'        => 'Mie & Pasta',
            'sembako-lainnya'  => 'Sembako Lainnya',
        ];

        $categories = collect($categoryList)->map(function ($name, $slug) use ($kategori) {
            return [
                'slug'    => $slug,
                'name'    => $name,
                'checked' => $slug === $kategori,
            ];
        })->values()->prepend([
            'slug'    => null,
            'name'    => 'Semua Produk',
            'checked' => is_null($kategori),
        ]);

        // ==================== DAFTAR MEREK ====================
        $brands = [
            ['id' => 1, 'name' => 'Rose Brand', 'checked' => false],
            ['id' => 2, 'name' => 'Bimoli', 'checked' => false],
            ['id' => 3, 'name' => 'Anak Raja', 'checked' => false],
            ['id' => 4, 'name' => 'Gulaku', 'checked' => false],
            ['id' => 5, 'name' => 'Topi Koki', 'checked' => false],
        ];

        // ==================== FILTER PRODUK ====================
        // TODO: ganti dengan query Eloquent, contoh:
        // $products = Product::with('brand')
        //     ->when($kategori, fn($q) => $q->whereHas('category', fn($c) => $c->where('slug', $kategori)))
        //     ->when($request->min_price, fn($q) => $q->where('member_price', '>=', $request->min_price))
        //     ->when($request->max_price, fn($q) => $q->where('member_price', '<=', $request->max_price))
        //     ->get();

        $products = collect($this->mockProducts())
            ->when($kategori, fn($q) => $q->where('category_slug', $kategori))
            ->values();

        $categoryLabel = $kategori ? ($categoryList[$kategori] ?? 'Produk') : 'Produk';

        return view('templates.user.display_kategori_produk', compact(
            'categories',
            'brands',
            'products',
            'categoryLabel'
        ));
    }

    /**
     * Data dummy produk. Hapus method ini setelah terhubung ke database.
     */
    private function mockProducts()
    {
        return [
            // ---------- Minyak & Lemak ----------
            [
                'id' => 1, 'name' => 'Minyak Goreng 2L', 'category' => 'Minyak & Lemak', 'category_slug' => 'minyak-lemak',
                'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Minyak+Goreng',
                'original_price' => 38000, 'member_price' => 34200, 'promo' => null,
            ],
            [
                'id' => 2, 'name' => 'Margarin Serbaguna 200g', 'category' => 'Minyak & Lemak', 'category_slug' => 'minyak-lemak',
                'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Margarin',
                'original_price' => 9500, 'member_price' => 8700, 'promo' => null,
            ],

            // ---------- Beras & Tepung ----------
            [
                'id' => 3, 'name' => 'Beras Premium 5kg', 'category' => 'Beras & Tepung', 'category_slug' => 'beras-tepung',
                'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Beras+Premium',
                'original_price' => 85000, 'member_price' => 79500, 'promo' => 'PROMO',
            ],
            [
                'id' => 4, 'name' => 'Tepung Terigu 1kg', 'category' => 'Beras & Tepung', 'category_slug' => 'beras-tepung',
                'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Tepung+Terigu',
                'original_price' => 14000, 'member_price' => 12900, 'promo' => null,
            ],

            // ---------- Makanan Kaleng ----------
            [
                'id' => 5, 'name' => 'Sarden Tomat Kaleng', 'category' => 'Makanan Kaleng', 'category_slug' => 'makanan-kaleng',
                'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Sarden',
                'original_price' => 9500, 'member_price' => 8200, 'promo' => 'PROMO',
            ],
            [
                'id' => 6, 'name' => 'Kornet Sapi Kaleng', 'category' => 'Makanan Kaleng', 'category_slug' => 'makanan-kaleng',
                'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Kornet',
                'original_price' => 32000, 'member_price' => 29500, 'promo' => null,
            ],

            // ---------- Sabun & Kebersihan ----------
            [
                'id' => 7, 'name' => 'Sabun Mandi Batang 3pcs', 'category' => 'Sabun & Kebersihan', 'category_slug' => 'sabun-kebersihan',
                'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Sabun+Mandi',
                'original_price' => 12000, 'member_price' => 10500, 'promo' => null,
            ],
            [
                'id' => 8, 'name' => 'Deterjen Bubuk 1.8kg', 'category' => 'Sabun & Kebersihan', 'category_slug' => 'sabun-kebersihan',
                'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Deterjen',
                'original_price' => 28000, 'member_price' => 25500, 'promo' => 'PROMO',
            ],

            // ---------- Minuman ----------
            [
                'id' => 9, 'name' => 'Teh Celup Kotak Isi 25', 'category' => 'Minuman', 'category_slug' => 'minuman',
                'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Teh+Celup',
                'original_price' => 8500, 'member_price' => 7800, 'promo' => null,
            ],
            [
                'id' => 10, 'name' => 'Kopi Sachet Isi 10', 'category' => 'Minuman', 'category_slug' => 'minuman',
                'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Kopi+Sachet',
                'original_price' => 11000, 'member_price' => 9900, 'promo' => null,
            ],

            // ---------- Bumbu Dapur ----------
            [
                'id' => 11, 'name' => 'Garam Meja 500g', 'category' => 'Bumbu Dapur', 'category_slug' => 'bumbu-dapur',
                'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Garam+Meja',
                'original_price' => 5500, 'member_price' => 4800, 'promo' => null,
            ],
            [
                'id' => 12, 'name' => 'Gula Pasir Lokal 1kg', 'category' => 'Bumbu Dapur', 'category_slug' => 'bumbu-dapur',
                'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Gula+Pasir',
                'original_price' => 16500, 'member_price' => 15000, 'promo' => null,
            ],

            // ---------- Mie & Pasta ----------
            [
                'id' => 13, 'name' => 'Mie Instan Goreng 5pcs', 'category' => 'Mie & Pasta', 'category_slug' => 'mie-pasta',
                'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Mie+Instan',
                'original_price' => 13500, 'member_price' => 12200, 'promo' => 'PROMO',
            ],
            [
                'id' => 14, 'name' => 'Spaghetti 500g', 'category' => 'Mie & Pasta', 'category_slug' => 'mie-pasta',
                'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Spaghetti',
                'original_price' => 21000, 'member_price' => 19500, 'promo' => null,
            ],

            // ---------- Sembako Lainnya ----------
            [
                'id' => 15, 'name' => 'Telur Ayam Negeri 1kg', 'category' => 'Sembako Lainnya', 'category_slug' => 'sembako-lainnya',
                'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Telur+Ayam',
                'original_price' => 28000, 'member_price' => 26500, 'promo' => null,
            ],
            [
                'id' => 16, 'name' => 'Susu Kental Manis 370g', 'category' => 'Sembako Lainnya', 'category_slug' => 'sembako-lainnya',
                'image' => 'https://placehold.co/300x300/e2e8f0/475569?text=Susu+Kental',
                'original_price' => 12500, 'member_price' => 11400, 'promo' => null,
            ],
        ];
    }
}