<?php
require_once 'koneksi.php';

// Helper Function untuk mengambil data dari API Python FastAPI
function fetch_ai_api($url) {
    $context = stream_context_create([
        'http' => [
            'timeout' => 3, // Timeout 3 detik
            'ignore_errors' => true
        ]
    ]);
    $response = @file_get_contents($url, false, $context);
    if ($response === false) {
        return null;
    }
    return json_decode($response, true);
}

// 1. Ambil Alert Stok dari API
$stokAlert = fetch_ai_api("http://127.0.0.1:5610/api/v1/stok/alert");

// 2. Ambil Produk Laris dari API
$produkLaris = fetch_ai_api("http://127.0.0.1:5610/api/v1/produk/laris?periode=30d&limit=5");

// 3. Ambil Semua Produk langsung dari Database MySQL
try {
    $stmt = $pdo->query("SELECT b.barang_id, b.nama, b.stok, b.harga, k.nama_kategori 
                         FROM barang b 
                         JOIN kategori k ON b.id_kategori = k.id_kategori 
                         ORDER BY b.barang_id ASC");
    $semuaProduk = $stmt->fetchAll();

    // 4. Ambil Log Audit Transaksi Dicurigai (Fraud Alerts)
    $stmtAudit = $pdo->query("SELECT a.audit_id, u.username, t.total_harga, a.tanggal_audit, a.info_audit_lama, a.info_audit_baru, t.transaction_id
                              FROM audit a
                              JOIN transactions t ON a.transaction_id = t.transaction_id
                              JOIN users u ON t.user_id = u.id_users
                              WHERE a.status_audit = 'MENCURIGAKAN'
                              ORDER BY a.tanggal_audit DESC
                              LIMIT 10");
    $daftarAudit = $stmtAudit->fetchAll();
} catch (PDOException $e) {
    die("Gagal memuat produk dan audit log: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Koperasi - Cerdas AI</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <a href="dashboard.php" class="logo"><span>Coop</span>Cerdas</a>
        <ul class="nav-links">
            <li><a href="dashboard.php" class="active">Dashboard Admin</a></li>
            <li><a href="transaksi.php">Kasir (Transaksi)</a></li>
            <li><a href="anggota.php">Portal Anggota</a></li>
            <li><a href="stok_safety.php">Simulasi Stok</a></li>
        </ul>
    </nav>

    <div class="container">
        
        <!-- HEADER -->
        <header class="page-header">
            <h1 class="page-title">Dashboard Analisis Inventori</h1>
            <p class="page-subtitle">Pemantauan stok cerdas & analisis penjualan koperasi digital secara real-time.</p>
        </header>

        <!-- STATUS KONEKSI PYTHON -->
        <?php if ($stokAlert === null || $produkLaris === null): ?>
            <div class="card glow-card-danger" style="margin-bottom: 2rem; padding: 1rem;">
                <span class="text-danger" style="font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                    ⚠️ SERVER AI OFFLINE: Server Python FastAPI di http://localhost:5610 tidak merespon. Pastikan server python berjalan dengan perintah 'python main.py' untuk mengaktifkan fitur AI.
                </span>
            </div>
        <?php endif; ?>

        <!-- GRID ATAS (Stok Alert & Produk Terlaris) -->
        <div class="grid-2">
            
            <!-- FITUR 2: NOTIFIKASI STOK KRITIS / REORDER POINT -->
            <div class="card <?php echo (!empty($stokAlert)) ? 'glow-card-danger' : ''; ?>">
                <h2 class="card-title">
                    <span style="font-size: 1.5rem;">🚨</span> Notifikasi Pengadaan Barang (ROP)
                </h2>
                <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1rem;">
                    Daftar barang di bawah ini disarankan untuk segera dipesan ke supplier berdasarkan perhitungan <b>Lead Time 3 Hari</b> & <b>Service Level 95%</b>.
                </p>

                <?php if (empty($stokAlert)): ?>
                    <div style="text-align: center; padding: 2rem 0; color: var(--color-success);">
                        <span style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem;">✓</span>
                        <span style="font-weight: 500;">Seluruh stok aman. Belum ada barang yang perlu dipesan.</span>
                    </div>
                <?php else: ?>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Stok Fisik</th>
                                    <th>Batas ROP</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stokAlert as $alert): ?>
                                    <tr>
                                        <td style="font-weight: 500;"><?php echo htmlspecialchars($alert['nama']); ?></td>
                                        <td><?php echo $alert['stok_sekarang']; ?> pcs</td>
                                        <td><?php echo $alert['reorder_point']; ?> pcs</td>
                                        <td>
                                            <span class="badge <?php echo ($alert['stok_sekarang'] == 0) ? 'badge-danger' : 'badge-warning'; ?>">
                                                <?php echo htmlspecialchars($alert['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- FITUR 1: ESTIMASI 5 PRODUK TERLARIS MINGGU/BULAN INI -->
            <div class="card">
                <h2 class="card-title">
                    <span style="font-size: 1.5rem;">📈</span> 5 Produk Paling Laris (Trend 30d)
                </h2>
                <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1rem;">
                    Berdasarkan visualisasi jumlah penjualan retail dari database koperasi.
                </p>

                <?php if (empty($produkLaris)): ?>
                    <div style="text-align: center; padding: 2.5rem 0; color: var(--text-secondary);">
                        Belum ada data transaksi penjualan yang tercatat.
                    </div>
                <?php else: ?>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Kategori</th>
                                    <th>Total Terjual</th>
                                    <th>Popularitas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($produkLaris as $laris): ?>
                                    <tr>
                                        <td style="font-weight: 500;"><?php echo htmlspecialchars($laris['nama']); ?></td>
                                        <td style="color: var(--text-secondary);"><?php echo htmlspecialchars($laris['nama_kategori']); ?></td>
                                        <td style="font-weight: 600; text-align: center; color: var(--color-primary);">
                                            <?php echo $laris['total_terjual']; ?>
                                        </td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.5rem; width: 100px;">
                                                <div style="flex-grow: 1; height: 6px; background: rgba(255,255,255,0.05); border-radius: 3px; overflow: hidden;">
                                                    <div style="width: <?php echo $laris['skor_popularitas']; ?>%; height: 100%; background: var(--color-primary); box-shadow: 0 0 8px var(--color-primary);"></div>
                                                </div>
                                                <span style="font-size: 0.75rem; font-weight: 600; min-width: 25px; text-align: right;">
                                                    <?php echo $laris['skor_popularitas']; ?>%
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- FITUR 3: RIWAYAT DETEKSI FRAUD (AUDIT LOG ALERTS) -->
        <div class="card <?php echo (!empty($daftarAudit)) ? 'glow-card-danger' : ''; ?>" style="margin-top: 2rem;">
            <h2 class="card-title">
                <span style="font-size: 1.5rem;">🕵️‍♂️</span> Log Peringatan Fraud AI (Transaksi Dicurigai)
            </h2>
            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1rem;">
                Berikut adalah daftar transaksi yang tetap disimpan setelah memicu peringatan Fraud di sistem kasir.
            </p>

            <?php if (empty($daftarAudit)): ?>
                <div style="text-align: center; padding: 2rem 0; color: var(--color-success);">
                    <span style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem;">✓</span>
                    <span style="font-weight: 500;">Aman. Belum ada aktivitas transaksi mencurigakan yang dilaporkan.</span>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID Tx</th>
                                <th>Anggota</th>
                                <th>Total Belanja</th>
                                <th>Skor Fraud</th>
                                <th>Keterangan Audit</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($daftarAudit as $audit): ?>
                                <tr>
                                    <td style="color: var(--text-secondary);">#<?php echo $audit['transaction_id']; ?></td>
                                    <td style="font-weight: 500;"><?php echo htmlspecialchars($audit['username']); ?></td>
                                    <td style="font-weight: 600; color: var(--color-primary);">
                                        Rp <?php echo number_format($audit['total_harga'], 0, ',', '.'); ?>
                                    </td>
                                    <td class="text-danger" style="font-weight: 600;">
                                        <?php echo htmlspecialchars($audit['info_audit_lama']); ?>
                                    </td>
                                    <td style="font-size: 0.85rem; color: var(--text-secondary);">
                                        <?php echo htmlspecialchars($audit['info_audit_baru']); ?>
                                    </td>
                                    <td style="font-size: 0.8rem; color: var(--text-secondary);">
                                        <?php echo date('d M Y H:i', strtotime($audit['tanggal_audit'])); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- TABEL DATA FISIK STOK GUDANG (MySQL Direct) -->
        <div class="card" style="margin-top: 2rem;">
            <h2 class="card-title">
                <span style="font-size: 1.5rem;">📦</span> Data Stok Fisik Seluruh Barang (Gudang Koperasi)
            </h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok Fisik</th>
                            <th>Harga Jual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($semuaProduk as $produk): ?>
                            <tr>
                                <td style="color: var(--text-secondary);"><?php echo $produk['barang_id']; ?></td>
                                <td style="font-weight: 500;"><?php echo htmlspecialchars($produk['nama']); ?></td>
                                <td><?php echo htmlspecialchars($produk['nama_kategori']); ?></td>
                                <td style="font-weight: 600;"><?php echo $produk['stok']; ?> pcs</td>
                                <td style="font-weight: 500; color: var(--color-success);">
                                    Rp <?php echo number_format($produk['harga'], 0, ',', '.'); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>
