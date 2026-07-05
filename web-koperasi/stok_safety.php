<?php
require_once 'koneksi.php';

// Helper Function untuk POST data ke API Safety Stock kustom
function calculate_safety_stock($produkId, $leadTime, $serviceLevel) {
    $url = "http://127.0.0.1:5610/api/v1/stok/safety/{$produkId}?lead_time={$leadTime}&service_level={$serviceLevel}";
    
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n",
            'content' => '{}',
            'timeout' => 3,
            'ignore_errors' => true
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    if ($response === false) {
        return null;
    }
    return json_decode($response, true);
}

// 1. Ambil daftar barang untuk dropdown
try {
    $stmtBarang = $pdo->query("SELECT barang_id, nama, stok FROM barang ORDER BY nama ASC");
    $daftarBarang = $stmtBarang->fetchAll();
} catch (PDOException $e) {
    die("Gagal memuat daftar barang: " . $e->getMessage());
}

$safetyResult = null;
$aiOffline = false;
$produkId = 0;
$leadTime = 3;
$serviceLevel = 0.95;

// 2. Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'calculate') {
    $produkId = intval($_POST['barang_id']);
    $leadTime = intval($_POST['lead_time']);
    $serviceLevel = floatval($_POST['service_level']);
    
    if ($produkId > 0 && $leadTime > 0 && $serviceLevel > 0) {
        $safetyResult = calculate_safety_stock($produkId, $leadTime, $serviceLevel);
        if ($safetyResult === null) {
            $aiOffline = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator Safety Stock & ROP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <a href="dashboard.php" class="logo"><span>Coop</span>Cerdas</a>
        <ul class="nav-links">
            <li><a href="dashboard.php">Dashboard Admin</a></li>
            <li><a href="transaksi.php">Kasir (Transaksi)</a></li>
            <li><a href="anggota.php">Portal Anggota</a></li>
            <li><a href="stok_safety.php" class="active">Simulasi Stok</a></li>
        </ul>
    </nav>

    <div class="container" style="max-width: 900px;">
        
        <!-- HEADER -->
        <header class="page-header">
            <h1 class="page-title">Simulasi & Kalkulator Stok Pengaman (Safety Stock)</h1>
            <p class="page-subtitle">Alat bantu analisis pengadaan barang untuk mensimulasikan batas reorder (ROP) secara dinamis.</p>
        </header>

        <!-- KONEKSI OFFLINE -->
        <?php if ($aiOffline): ?>
            <div class="card glow-card-danger" style="margin-bottom: 2rem; padding: 1rem;">
                <span class="text-danger" style="font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                    ⚠️ SERVER OFFLINE: Gagal terhubung ke API kalkulator stok FastAPI (port 5610).
                </span>
            </div>
        <?php endif; ?>

        <div class="grid-2" style="grid-template-columns: 1fr 1.5fr;">
            
            <!-- FORM INPUT SIMULASI -->
            <div class="card">
                <h2 class="card-title"><span style="font-size: 1.5rem;">⚙️</span> Parameter Simulasi</h2>
                
                <form action="stok_safety.php" method="POST">
                    <input type="hidden" name="action" value="calculate">
                    
                    <!-- PILIH BARANG -->
                    <div class="form-group">
                        <label for="barang_id">Pilih Barang</label>
                        <select name="barang_id" id="barang_id" class="form-control" required>
                            <option value="">-- Pilih Barang --</option>
                            <?php foreach ($daftarBarang as $barang): ?>
                                <option value="<?php echo $barang['barang_id']; ?>" <?php echo ($produkId == $barang['barang_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($barang['nama']); ?> (Stok: <?php echo $barang['stok']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- LEAD TIME -->
                    <div class="form-group">
                        <label for="lead_time">Lead Time Pemesanan (Hari)</label>
                        <input type="number" name="lead_time" id="lead_time" class="form-control" min="1" max="30" value="<?php echo htmlspecialchars($leadTime); ?>" required>
                        <small style="color: var(--text-secondary); font-size: 0.75rem;">
                            *Lama pengiriman barang dari supplier sampai tiba di toko.
                        </small>
                    </div>

                    <!-- SERVICE LEVEL -->
                    <div class="form-group">
                        <label for="service_level">Service Level Target (%)</label>
                        <select name="service_level" id="service_level" class="form-control" required>
                            <option value="0.90" <?php echo ($serviceLevel == 0.90) ? 'selected' : ''; ?>>90% (Faktor Z: 1.28) - Hemat Modal</option>
                            <option value="0.95" <?php echo ($serviceLevel == 0.95) ? 'selected' : ''; ?>>95% (Faktor Z: 1.65) - Standar Retail</option>
                            <option value="0.99" <?php echo ($serviceLevel == 0.99) ? 'selected' : ''; ?>>99% (Faktor Z: 2.33) - Sangat Aman</option>
                        </select>
                        <small style="color: var(--text-secondary); font-size: 0.75rem;">
                            *Keyakinan barang tidak boleh kosong selama masa kirim.
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1.5rem;">Hitung Safety Stock</button>
                </form>
            </div>

            <!-- HASIL ANALISIS FORMULA AI -->
            <div class="card <?php echo ($safetyResult) ? 'glow-card' : ''; ?>">
                <h2 class="card-title"><span style="font-size: 1.5rem;">📊</span> Hasil Kalkulasi & Analisis AI</h2>

                <?php if (!$safetyResult): ?>
                    <div style="text-align: center; padding: 5rem 0; color: var(--text-secondary);">
                        <span style="font-size: 3rem; display: block; margin-bottom: 1rem;">🧮</span>
                        Pilih produk dan masukkan parameter di sebelah kiri untuk menghitung rekomendasi stok aman.
                    </div>
                <?php else: ?>
                    <div style="margin-bottom: 2rem;">
                        <span style="font-size: 0.9rem; color: var(--text-secondary); display: block;">Produk yang Dianalisis</span>
                        <span style="font-size: 1.8rem; font-weight: 700; color: var(--text-primary);">
                            <?php echo htmlspecialchars($safetyResult['nama']); ?>
                        </span>
                    </div>

                    <!-- KOTAK NILAI UTAMA -->
                    <div class="grid-2" style="margin-bottom: 2rem;">
                        <div style="background: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.15); border-radius: 12px; padding: 1rem; text-align: center;">
                            <span style="font-size: 0.8rem; color: var(--color-success); font-weight: 600; display: block; margin-bottom: 0.25rem;">SAFETY STOCK</span>
                            <span style="font-size: 2rem; font-weight: 700; color: var(--color-success);">
                                <?php echo $safetyResult['safety_stock']; ?>
                            </span>
                            <span style="font-size: 0.75rem; color: var(--text-secondary); display: block; margin-top: 0.25rem;">pcs cadangan aman</span>
                        </div>

                        <div style="background: rgba(59, 130, 246, 0.05); border: 1px solid rgba(59, 130, 246, 0.15); border-radius: 12px; padding: 1rem; text-align: center;">
                            <span style="font-size: 0.8rem; color: var(--color-primary); font-weight: 600; display: block; margin-bottom: 0.25rem;">REORDER POINT (ROP)</span>
                            <span style="font-size: 2rem; font-weight: 700; color: var(--color-primary);">
                                <?php echo $safetyResult['reorder_point']; ?>
                            </span>
                            <span style="font-size: 0.75rem; color: var(--text-secondary); display: block; margin-top: 0.25rem;">pcs batas pesan kembali</span>
                        </div>
                    </div>

                    <!-- PENJABARAN RUMUS / MATEMATIKA -->
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-glass); border-radius: 12px; padding: 1.25rem;">
                        <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                            <span>📝</span> Penjelasan Rumus Matematika AI:
                        </h3>
                        
                        <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.9rem;">
                            <div class="flex-between">
                                <span style="color: var(--text-secondary);">1. Prediksi Rata-rata Penjualan Harian (μ)</span>
                                <span style="font-weight: 600;"><?php echo $safetyResult['rata_rata_permintaan']; ?> pcs/hari</span>
                            </div>
                            <div class="flex-between">
                                <span style="color: var(--text-secondary);">2. Fluktuasi Harian / Standar Deviasi (σ)</span>
                                <span style="font-weight: 600;"><?php echo $safetyResult['standar_deviasi']; ?> pcs/hari</span>
                            </div>
                            <div class="flex-between">
                                <span style="color: var(--text-secondary);">3. Faktor Pelayanan Pembeli / Z-Score (Z)</span>
                                <span style="font-weight: 600; color: var(--color-warning);"><?php echo $safetyResult['z_factor']; ?></span>
                            </div>
                            <div class="flex-between">
                                <span style="color: var(--text-secondary);">4. Waktu Kirim Barang / Lead Time (LT)</span>
                                <span style="font-weight: 600;"><?php echo $safetyResult['lead_time']; ?> hari</span>
                            </div>
                            
                            <hr style="border: 0; border-top: 1px solid var(--border-glass); margin: 0.5rem 0;">

                            <div style="font-size: 0.8rem; color: var(--text-secondary); line-height: 1.4; background: var(--bg-main); padding: 0.75rem; border-radius: 8px; border-left: 3px solid var(--color-primary);">
                                <b>Cara Kerja Kalkulasi ROP:</b><br>
                                ROP = (Rata-rata Permintaan x Lead Time) + Safety Stock<br>
                                ROP = (<?php echo $safetyResult['rata_rata_permintaan']; ?> x <?php echo $safetyResult['lead_time']; ?>) + <?php echo $safetyResult['safety_stock']; ?><br>
                                ROP = <b><?php echo $safetyResult['reorder_point']; ?> pcs</b>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>

    </div>

</body>
</html>
