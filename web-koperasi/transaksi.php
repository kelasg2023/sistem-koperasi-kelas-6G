<?php
require_once 'koneksi.php';

$successMsg = "";
$errorMsg = "";

// 1. Ambil daftar Anggota & Barang untuk Form
try {
    $stmtUsers = $pdo->query("SELECT id_users, username FROM users ORDER BY id_users ASC");
    $daftarAnggota = $stmtUsers->fetchAll();
    
    $stmtBarang = $pdo->query("SELECT barang_id, nama, harga, stok FROM barang ORDER BY nama ASC");
    $daftarBarang = $stmtBarang->fetchAll();
} catch (PDOException $e) {
    die("Gagal memuat form data: " . $e->getMessage());
}

// 2. Simpan Transaksi Ke Database
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save') {
    $userId = intval($_POST['user_id']);
    $barangId = intval($_POST['barang_id']);
    $jumlah = intval($_POST['jumlah']);
    $paymentMethod = $_POST['payment_method'];
    $createdAt = $_POST['created_at'];

    // Validasi data
    if ($userId > 0 && $barangId > 0 && $jumlah > 0 && !empty($paymentMethod)) {
        try {
            $pdo->beginTransaction();

            // Ambil detail barang untuk mendapatkan harga satuan & stok
            $stmtGetBarang = $pdo->prepare("SELECT harga, stok FROM barang WHERE barang_id = :b_id");
            $stmtGetBarang->execute(['b_id' => $barangId]);
            $barangInfo = $stmtGetBarang->fetch();

            if (!$barangInfo) {
                throw new Exception("Barang tidak ditemukan.");
            }

            if ($barangInfo['stok'] < $jumlah) {
                throw new Exception("Stok fisik tidak mencukupi (Tersedia: {$barangInfo['stok']} pcs).");
            }

            $hargaSatuan = floatval($barangInfo['harga']);
            $totalHarga = $hargaSatuan * $jumlah;

            // Dapatkan ID Transaksi berikutnya (karena primary key int non-auto increment di db.sql)
            $stmtNextTx = $pdo->query("SELECT IFNULL(MAX(transaction_id), 0) + 1 FROM transactions");
            $nextTxId = intval($stmtNextTx->fetchColumn());

            // Dapatkan ID Detail berikutnya
            $stmtNextDet = $pdo->query("SELECT IFNULL(MAX(detail_id), 0) + 1 FROM transaction_details");
            $nextDetId = intval($stmtNextDet->fetchColumn());

            // Simpan header transaksi
            $insertTx = "
                INSERT INTO transactions (transaction_id, user_id, total_harga, status, payment_method, created_at)
                VALUES (:tx_id, :u_id, :total, 'berhasil', :pay, :created)
            ";
            $stmtTx = $pdo->prepare($insertTx);
            $stmtTx->execute([
                'tx_id' => $nextTxId,
                'u_id' => $userId,
                'total' => $totalHarga,
                'pay' => $paymentMethod,
                'created' => $createdAt
            ]);

            // Simpan detail transaksi
            $insertDet = "
                INSERT INTO transaction_details (detail_id, transaction_id, barang_id, jumlah, harga_satuan)
                VALUES (:det_id, :tx_id, :b_id, :qty, :price)
            ";
            $stmtDet = $pdo->prepare($insertDet);
            $stmtDet->execute([
                'det_id' => $nextDetId,
                'tx_id' => $nextTxId,
                'b_id' => $barangId,
                'qty' => $jumlah,
                'price' => $hargaSatuan
            ]);

            // Kurangi stok barang di MySQL
            $updateStok = "UPDATE barang SET stok = stok - :qty WHERE barang_id = :b_id";
            $stmtStok = $pdo->prepare($updateStok);
            $stmtStok->execute(['qty' => $jumlah, 'b_id' => $barangId]);

            // Jika transaksi terindikasi fraud (dan disetujui kasir untuk disimpan)
            if (isset($_POST['is_fraud']) && $_POST['is_fraud'] === '1') {
                $fraudScore = floatval($_POST['fraud_score'] ?? 0.0);
                $stmtNextAudit = $pdo->query("SELECT IFNULL(MAX(audit_id), 0) + 1 FROM audit");
                $nextAuditId = intval($stmtNextAudit->fetchColumn());
                
                $insertAudit = "
                    INSERT INTO audit (audit_id, transaction_id, status_audit, info_audit_lama, info_audit_baru, tanggal_audit)
                    VALUES (:audit_id, :tx_id, 'MENCURIGAKAN', :score, 'Belanja disetujui kasir setelah peringatan fraud', :created)
                ";
                $stmtAudit = $pdo->prepare($insertAudit);
                $stmtAudit->execute([
                    'audit_id' => $nextAuditId,
                    'tx_id' => $nextTxId,
                    'score' => 'Skor Fraud: ' . round($fraudScore * 100, 1) . '%',
                    'created' => $createdAt
                ]);
            }

            $pdo->commit();
            $successMsg = "Transaksi berhasil disimpan ke MySQL! Analisis AI rekomendasi & stok otomatis terupdate.";
            
            // Reload daftar barang untuk form
            $stmtBarang = $pdo->query("SELECT barang_id, nama, harga, stok FROM barang ORDER BY nama ASC");
            $daftarBarang = $stmtBarang->fetchAll();
        } catch (Exception $e) {
            $pdo->rollBack();
            $errorMsg = "Transaksi gagal: " . $e->getMessage();
        }
    } else {
        $errorMsg = "Formulir belanja tidak lengkap.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Koperasi & Cek Fraud AI</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <a href="dashboard.php" class="logo"><span>Coop</span>Cerdas</a>
        <ul class="nav-links">
            <li><a href="dashboard.php">Dashboard Admin</a></li>
            <li><a href="transaksi.php" class="active">Kasir (Transaksi)</a></li>
            <li><a href="anggota.php">Portal Anggota</a></li>
            <li><a href="stok_safety.php">Simulasi Stok</a></li>
        </ul>
    </nav>

    <div class="container" style="max-width: 650px;">
        
        <!-- HEADER -->
        <header class="page-header">
            <h1 class="page-title">Sistem Kasir & Deteksi Fraud</h1>
            <p class="page-subtitle">Input belanja kasir. AI akan menganalisis indikasi fraud sebelum transaksi disimpan.</p>
        </header>

        <!-- NOTIFIKASI SUCCESS / ERROR -->
        <?php if (!empty($successMsg)): ?>
            <div class="card" style="border-color: var(--color-success); background: rgba(16, 185, 129, 0.05); margin-bottom: 1.5rem; padding: 1rem; color: var(--color-success); font-weight: 500;">
                ✓ <?php echo $successMsg; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errorMsg)): ?>
            <div class="card glow-card-danger" style="margin-bottom: 1.5rem; padding: 1rem; color: var(--color-danger); font-weight: 500;">
                ⚠️ <?php echo $errorMsg; ?>
            </div>
        <?php endif; ?>

        <!-- FORM KASIR -->
        <div class="card">
            <h2 class="card-title"><span style="font-size: 1.5rem;">🛒</span> Form Belanja Baru</h2>
            
            <form id="formTransaksi" action="transaksi.php" method="POST">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="is_fraud" id="is_fraud" value="0">
                <input type="hidden" name="fraud_score" id="fraud_score" value="0">
                
                <!-- ID ANGGOTA -->
                <div class="form-group">
                    <label for="user_id">Pilih Anggota (Pembeli)</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">-- Pilih Anggota --</option>
                        <?php foreach ($daftarAnggota as $anggota): ?>
                            <option value="<?php echo $anggota['id_users']; ?>">
                                <?php echo htmlspecialchars($anggota['username']); ?> (ID: <?php echo $anggota['id_users']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- PILIH BARANG & JUMLAH -->
                <div class="grid-2" style="gap: 1rem; margin-bottom: 0;">
                    <div class="form-group">
                        <label for="barang_id">Barang</label>
                        <select name="barang_id" id="barang_id" class="form-control" required>
                            <option value="" data-harga="0" data-stok="0">-- Pilih Barang --</option>
                            <?php foreach ($daftarBarang as $barang): ?>
                                <option value="<?php echo $barang['barang_id']; ?>" data-harga="<?php echo $barang['harga']; ?>" data-stok="<?php echo $barang['stok']; ?>">
                                    <?php echo htmlspecialchars($barang['nama']); ?> (Stok: <?php echo $barang['stok']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jumlah">Jumlah Beli</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" required>
                    </div>
                </div>

                <!-- METODE BAYAR & TANGGAL -->
                <div class="grid-2" style="gap: 1rem; margin-bottom: 0;">
                    <div class="form-group">
                        <label for="payment_method">Metode Pembayaran</label>
                        <select name="payment_method" id="payment_method" class="form-control" required>
                            <option value="cash">Cash (Tunai)</option>
                            <option value="qris">QRIS Digital</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="wallet">Dompet Koperasi</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="created_at">Tanggal & Jam Transaksi</label>
                        <input type="text" name="created_at" id="created_at" class="form-control" value="<?php echo date('Y-m-d H:i:s'); ?>">
                        <small style="color: var(--text-secondary); font-size: 0.75rem; display: block; margin-top: 0.25rem;">
                            *Ubah jam ke dini hari (misal 23:30 atau 01:15) untuk simulasi indikasi fraud jam belanja malam.
                        </small>
                    </div>
                </div>

                <!-- TOTAL HARGA PREVIEW -->
                <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-glass); border-radius: 10px; padding: 1rem; margin-bottom: 1.5rem; text-align: center;">
                    <span style="font-size: 0.85rem; color: var(--text-secondary); font-weight: 500; display: block; margin-bottom: 0.25rem;">Total Tagihan Belanja</span>
                    <span id="labelTotalHarga" style="font-size: 1.7rem; font-weight: 700; color: var(--color-primary);">Rp 0</span>
                </div>

                <button type="button" id="btnProses" class="btn btn-primary btn-block">Proses Transaksi</button>
            </form>
        </div>
    </div>

    <!-- FRAUD WARNING MODAL -->
    <div id="modalFraud" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-icon">🚨</div>
            <div class="modal-title text-danger">Transaksi Dicurigai FRAUD!</div>
            <div class="modal-body">
                Model AI mendeteksi kejanggalan pada transaksi ini dengan skor kecurigaan sebesar <b id="scoreFraud" class="text-danger">0%</b>.<br><br>
                Faktor pemicu kecurigaan meliputi: nominal belanja terlampau tinggi, metode bayar tidak lazim, atau transaksi dilakukan di luar jam kerja (malam hari).
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="button" id="btnCancel" class="btn" style="background: rgba(255,255,255,0.1); color: var(--text-primary); flex-grow: 1;">Batalkan</button>
                <button type="button" id="btnForceSave" class="btn" style="background: var(--color-danger); color: white; flex-grow: 1;">Tetap Simpan</button>
            </div>
        </div>
    </div>

    <!-- SCRIPT REAL-TIME CALCULATION & API INTEGRATION -->
    <script>
        const selectBarang = document.getElementById('barang_id');
        const inputJumlah = document.getElementById('jumlah');
        const labelTotalHarga = document.getElementById('labelTotalHarga');
        const btnProses = document.getElementById('btnProses');
        const formTransaksi = document.getElementById('formTransaksi');

        const modalFraud = document.getElementById('modalFraud');
        const scoreFraud = document.getElementById('scoreFraud');
        const btnCancel = document.getElementById('btnCancel');
        const btnForceSave = document.getElementById('btnForceSave');

        // 1. Update Total Harga secara Real-time
        function updateTotal() {
            const qty = parseInt(inputJumlah.value) || 0;
            const option = selectBarang.options[selectBarang.selectedIndex];
            const harga = parseFloat(option.getAttribute('data-harga')) || 0;
            const total = qty * harga;
            
            labelTotalHarga.textContent = 'Rp ' + total.toLocaleString('id-ID');
            return total;
        }

        selectBarang.addEventListener('change', updateTotal);
        inputJumlah.addEventListener('input', updateTotal);

        // 2. Kirim ke API Python FastAPI untuk Cek Anomali (Fraud)
        btnProses.addEventListener('click', async () => {
            const userId = document.getElementById('user_id').value;
            const barangId = selectBarang.value;
            const jumlah = inputJumlah.value;
            const payMethod = document.getElementById('payment_method').value;
            const timeCreated = document.getElementById('created_at').value;

            if (!userId || !barangId || !jumlah) {
                alert("Harap isi seluruh formulir!");
                return;
            }

            const totalHarga = updateTotal();

            btnProses.textContent = "Menganalisis Fraud...";
            btnProses.disabled = true;

            try {
                // Siapkan body request untuk API FastAPI
                const payload = {
                    transaction_id: 9999, // dummy ID untuk analisis
                    user_id: parseInt(userId),
                    total_harga: totalHarga,
                    payment_method: payMethod,
                    created_at: timeCreated
                };

                const response = await fetch('http://127.0.0.1:5610/api/v1/fraud/check', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                if (response.ok) {
                    const data = await response.json();
                    
                    if (data.status === "suspicious") {
                        // Tampilkan modal peringatan fraud
                        document.getElementById('is_fraud').value = "1";
                        document.getElementById('fraud_score').value = data.fraud_score;
                        scoreFraud.textContent = (data.fraud_score * 100).toFixed(1) + "%";
                        modalFraud.style.display = 'flex';
                    } else {
                        // Jika normal, langsung simpan ke MySQL
                        document.getElementById('is_fraud').value = "0";
                        document.getElementById('fraud_score').value = "0";
                        formTransaksi.submit();
                    }
                } else {
                    // Jika API Error / offline, langsung bypass simpan ke MySQL
                    formTransaksi.submit();
                }
            } catch (err) {
                // Jika koneksi putus, langsung bypass simpan ke MySQL
                formTransaksi.submit();
            } finally {
                btnProses.textContent = "Proses Transaksi";
                btnProses.disabled = false;
            }
        });

        // Event Modal Actions
        btnCancel.addEventListener('click', () => {
            modalFraud.style.display = 'none';
        });

        btnForceSave.addEventListener('click', () => {
            modalFraud.style.display = 'none';
            formTransaksi.submit();
        });
    </script>
</body>
</html>
