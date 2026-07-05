<?php
require_once 'koneksi.php';

// Helper Function untuk mengambil data rekomendasi via POST
function get_recommendations($userId, $limit = 4) {
    $url = "http://127.0.0.1:5610/api/v1/rekomendasi/anggota/{$userId}?num_recommendations={$limit}";
    
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

// 1. Ambil daftar semua anggota untuk pilihan dropdown
try {
    $stmt = $pdo->query("SELECT id_users, username FROM users ORDER BY id_users ASC");
    $daftarAnggota = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Gagal memuat anggota: " . $e->getMessage());
}

$anggotaTerpilih = null;
$riwayatBelanja = [];
$rekomendasiBarang = [];
$aiOffline = false;

// 2. Jika user memilih anggota tertentu
if (isset($_GET['anggota_id']) && !empty($_GET['anggota_id'])) {
    $anggotaId = intval($_GET['anggota_id']);
    
    // Cari detail anggota terpilih
    foreach ($daftarAnggota as $anggota) {
        if ($anggota['id_users'] == $anggotaId) {
            $anggotaTerpilih = $anggota;
            break;
        }
    }
    
    if ($anggotaTerpilih) {
        // Ambil riwayat pembelian dari database MySQL
        try {
            $queryHistori = "
                SELECT b.nama, td.jumlah, t.created_at, (td.jumlah * td.harga_satuan) as subtotal
                FROM transaction_details td
                JOIN transactions t ON td.transaction_id = t.transaction_id
                JOIN barang b ON td.barang_id = b.barang_id
                WHERE t.user_id = :u_id AND t.status = 'berhasil'
                ORDER BY t.created_at DESC
                LIMIT 10
            ";
            $stmtHistori = $pdo->prepare($queryHistori);
            $stmtHistori->execute(['u_id' => $anggotaId]);
            $riwayatBelanja = $stmtHistori->fetchAll();
        } catch (PDOException $e) {
            die("Gagal memuat riwayat belanja: " . $e->getMessage());
        }
        
        // Panggil API FastAPI untuk mendapatkan rekomendasi
        $rekomendasiBarang = get_recommendations($anggotaId, 4);
        if ($rekomendasiBarang === null) {
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
    <title>Portal Anggota - Rekomendasi AI</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <a href="dashboard.php" class="logo"><span>Coop</span>Cerdas</a>
        <ul class="nav-links">
            <li><a href="dashboard.php">Dashboard Admin</a></li>
            <li><a href="transaksi.php">Kasir (Transaksi)</a></li>
            <li><a href="anggota.php" class="active">Portal Anggota</a></li>
            <li><a href="stok_safety.php">Simulasi Stok</a></li>
        </ul>
    </nav>

    <div class="container">
        
        <!-- HEADER -->
        <header class="page-header">
            <h1 class="page-title">Portal Pelayanan Anggota</h1>
            <p class="page-subtitle">Pilih anggota login untuk melihat riwayat belanja dan rekomendasi penawaran cerdas.</p>
        </header>

        <!-- PILIH ANGGOTA -->
        <div class="card" style="margin-bottom: 2rem;">
            <h2 class="card-title">🔑 Pilih Akun Anggota</h2>
            <form action="anggota.php" method="GET" style="display: flex; gap: 1rem; align-items: flex-end;">
                <div class="form-group" style="flex-grow: 1; margin-bottom: 0;">
                    <label for="anggota_id">Anggota Koperasi</label>
                    <select name="anggota_id" id="anggota_id" class="form-control" required>
                        <option value="">-- Pilih Anggota --</option>
                        <?php foreach ($daftarAnggota as $anggota): ?>
                            <option value="<?php echo $anggota['id_users']; ?>" <?php echo (isset($anggotaId) && $anggotaId == $anggota['id_users']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($anggota['username']); ?> (ID: <?php echo $anggota['id_users']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Masuk Portal</button>
            </form>
        </div>

        <?php if ($anggotaTerpilih): ?>
            
            <!-- STATUS KONEKSI PYTHON OFFLINE -->
            <?php if ($aiOffline): ?>
                <div class="card glow-card-danger" style="margin-bottom: 2rem; padding: 1rem;">
                    <span class="text-danger" style="font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                        ⚠️ ERROR AI: Layanan rekomendasi FastAPI offline. Pastikan server python berjalan di port 5610.
                    </span>
                </div>
            <?php endif; ?>

            <!-- DUA KOLOM (Kiri: Rekomendasi AI, Kanan: Riwayat Belanja) -->
            <div class="grid-2">
                
                <!-- FITUR 1: REKOMENDASI CERDAS PERSONAL (COLD START HANDLE) -->
                <div class="card glow-card">
                    <h2 class="card-title">
                        <span style="font-size: 1.5rem;">🤖</span> Rekomendasi Cerdas AI Untuk Anda
                    </h2>
                    <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1.5rem;">
                        Daftar produk di bawah ini ditawarkan berdasarkan kemiripan pola belanja Anda dengan kelompok anggota lainnya.
                    </p>

                    <?php if (empty($rekomendasiBarang)): ?>
                        <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                            Tidak ada rekomendasi barang untuk saat ini.
                        </div>
                    <?php else: ?>
                        <div class="grid-2" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                            <?php foreach ($rekomendasiBarang as $rek): ?>
                                <div class="card" style="background: rgba(255,255,255,0.02); padding: 1rem; border-radius: 12px; border-color: rgba(255,255,255,0.04);">
                                    <div style="font-size: 2rem; margin-bottom: 0.5rem; text-align: center;">📦</div>
                                    <h3 style="font-size: 1rem; font-weight: 600; text-align: center; margin-bottom: 0.25rem;">
                                        <?php echo htmlspecialchars($rek['nama']); ?>
                                    </h3>
                                    <p style="font-size: 0.75rem; text-align: center; color: var(--color-primary); font-weight: 500;">
                                        Kategori ID: <?php echo $rek['id_kategori']; ?>
                                    </p>
                                    <div style="margin-top: 0.75rem; font-size: 0.7rem; color: var(--text-secondary); text-align: center; line-height: 1.3; background: rgba(59, 130, 246, 0.05); padding: 0.35rem; border-radius: 6px;">
                                        <?php echo htmlspecialchars($rek['keterangan']); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- RIWAYAT BELANJA (MySQL Direct) -->
                <div class="card">
                    <h2 class="card-title">
                        <span style="font-size: 1.5rem;">📜</span> Riwayat Pembelian Terakhir
                    </h2>
                    <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1rem;">
                        Log histori transaksi belanja anggota di kasir koperasi.
                    </p>

                    <?php if (empty($riwayatBelanja)): ?>
                        <div style="text-align: center; padding: 3rem 0; color: var(--text-secondary);">
                            Anggota ini belum pernah bertransaksi.
                        </div>
                    <?php else: ?>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nama Barang</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($riwayatBelanja as $histori): ?>
                                        <tr>
                                            <td style="font-size: 0.8rem; color: var(--text-secondary);">
                                                <?php echo date('d M Y H:i', strtotime($histori['created_at'])); ?>
                                            </td>
                                            <td style="font-weight: 500;"><?php echo htmlspecialchars($histori['nama']); ?></td>
                                            <td><?php echo $histori['jumlah']; ?> pcs</td>
                                            <td style="font-weight: 600; color: var(--color-success);">
                                                Rp <?php echo number_format($histori['subtotal'], 0, ',', '.'); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

        <?php endif; ?>

    </div>

</body>
</html>
