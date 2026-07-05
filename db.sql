-- ============================================================
-- db.sql — Schema referensi untuk python-starter (ML Service)
-- Disesuaikan dengan Laravel Migrations di koperasi6G
-- Update: 2026-07-05
-- ============================================================

-- Urutan CREATE harus mengikuti dependency FK

-- ── 1. users ────────────────────────────────────────────────
CREATE TABLE `users` (
  `id_users` bigint UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` ENUM('admin', 'staff', 'supplier', 'manager', 'customer') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
);

-- ── 2. password_reset_tokens ────────────────────────────────
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) PRIMARY KEY NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
);

-- ── 3. users_profiles ───────────────────────────────────────
CREATE TABLE `users_profiles` (
  `profiles_id` bigint UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text,
  `profile_picture` varchar(255),
  `phone` varchar(14),
  `is_member` tinyint(1) DEFAULT '0',
  CONSTRAINT `users_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_users`) ON DELETE CASCADE
);

-- ── 4. kategori ─────────────────────────────────────────────
CREATE TABLE `kategori` (
  `id_kategori` bigint UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(50) NOT NULL,
  `satuan` varchar(10) NOT NULL
);

-- ── 5. barang ───────────────────────────────────────────────
CREATE TABLE `barang` (
  `barang_id` bigint UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `stok` int DEFAULT '0',
  `harga` decimal(15,2) NOT NULL,
  `diskon_persen` decimal(5,2) DEFAULT '0.00',
  `deskripsi` text,
  `id_kategori` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  INDEX `idx_id_kategori` (`id_kategori`),
  INDEX `idx_harga` (`harga`),
  INDEX `idx_stok` (`stok`),
  FULLTEXT INDEX `ft_nama_deskripsi` (`nama`, `deskripsi`),
  CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE RESTRICT
);

-- ── 6. merk ─────────────────────────────────────────────────
CREATE TABLE `merk` (
  `merk_id` bigint UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nama_merk` varchar(50) NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  CONSTRAINT `merk_ibfk_1` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`) ON DELETE CASCADE
);

-- ── 7. supplier ─────────────────────────────────────────────
-- Catatan: supplier di Laravel adalah tabel relasi barang-merk (bukan entitas pemasok berdiri sendiri)
CREATE TABLE `supplier` (
  `supplier_id` bigint UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `merk_id` bigint UNSIGNED NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `harga_beli` decimal(15,2) NOT NULL,
  `jumlah` int NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  CONSTRAINT `supplier_ibfk_1` FOREIGN KEY (`merk_id`) REFERENCES `merk` (`merk_id`) ON DELETE RESTRICT,
  CONSTRAINT `supplier_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`) ON DELETE RESTRICT
);

-- ── 8. stok_history ─────────────────────────────────────────
CREATE TABLE `stok_history` (
  `stok_history_id` bigint UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint UNSIGNED NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `stok_awal` int NOT NULL,
  `stok_akhir` int NOT NULL,
  `keterangan` varchar(255),
  `stok_mutasi` ENUM('keluar', 'lainnya', 'masuk') NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `stok_history_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`) ON DELETE SET NULL,
  CONSTRAINT `stok_history_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`) ON DELETE CASCADE
);

-- ── 9. customers ─────────────────────────────────────────────
CREATE TABLE `customers` (
  `customers_id` bigint UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `point` int DEFAULT '0',
  `is_member` tinyint(1) DEFAULT '0',
  CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_users`) ON DELETE CASCADE
);

-- ── 10. vouchers ─────────────────────────────────────────────
CREATE TABLE `vouchers` (
  `id_voucher` bigint UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `kode_voucher` varchar(50) NOT NULL UNIQUE,
  `potongan_persen` decimal(5,2) DEFAULT '0.00',
  `kuota` int DEFAULT '0',
  `barang_id` bigint UNSIGNED NOT NULL,
  `tipe_voucher` ENUM('langsung', 'claim') NOT NULL DEFAULT 'langsung',
  `expired_at` datetime NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  CONSTRAINT `vouchers_ibfk_1` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`) ON DELETE CASCADE
);

-- ── 11. voucher_claims ───────────────────────────────────────
CREATE TABLE `voucher_claims` (
  `claim_id` bigint UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `id_voucher` bigint UNSIGNED NOT NULL,
  `status` ENUM('claimed', 'used', 'expired') NOT NULL DEFAULT 'claimed',
  `claimed_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `used_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `unique_user_voucher` (`user_id`, `id_voucher`),
  CONSTRAINT `voucher_claims_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_users`) ON DELETE CASCADE,
  CONSTRAINT `voucher_claims_ibfk_2` FOREIGN KEY (`id_voucher`) REFERENCES `vouchers` (`id_voucher`) ON DELETE CASCADE
);

-- ── 12. transactions ─────────────────────────────────────────
CREATE TABLE `transactions` (
  `transaction_id` bigint UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `total_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` ENUM('berhasil', 'proses', 'gagal', 'refund') NOT NULL DEFAULT 'proses',
  `payment_method` ENUM('cash', 'qris', 'transfer', 'wallet') NOT NULL DEFAULT 'cash',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  -- Kolom pengiriman (ditambahkan via migration add_shipping_columns)
  `alamat_pengiriman` text NULL,
  `jasa_kurir` varchar(255) NULL,
  `nomor_resi` varchar(255) NULL,
  `status_pengiriman` ENUM('pending', 'dikemas', 'dikirim', 'selesai') NOT NULL DEFAULT 'pending',
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_users`) ON DELETE RESTRICT
);

-- ── 13. transaction_details ──────────────────────────────────
CREATE TABLE `transaction_details` (
  `detail_id` bigint UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `id_voucher` bigint UNSIGNED NULL,
  CONSTRAINT `transaction_details_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`) ON DELETE CASCADE,
  CONSTRAINT `transaction_details_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`) ON DELETE RESTRICT,
  CONSTRAINT `transaction_details_ibfk_3` FOREIGN KEY (`id_voucher`) REFERENCES `vouchers` (`id_voucher`) ON DELETE RESTRICT
);

-- ── 14. audit ────────────────────────────────────────────────
CREATE TABLE `audit` (
  `audit_id` bigint UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `status_audit` varchar(50) NOT NULL,
  `tanggal_audit` datetime DEFAULT CURRENT_TIMESTAMP,
  `info_audit_lama` varchar(255) NULL,
  `info_audit_baru` varchar(255) NULL,
  CONSTRAINT `audit_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`) ON DELETE CASCADE
);

-- ── 15. wallet ───────────────────────────────────────────────
CREATE TABLE `wallet` (
  `id_wallet` bigint UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `balance` decimal(15,2) DEFAULT '0.00',
  CONSTRAINT `wallet_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_users`) ON DELETE CASCADE
);

-- ── 16. wallet_history ───────────────────────────────────────
CREATE TABLE `wallet_history` (
  `id_wt_history` bigint UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `id_wallet` bigint UNSIGNED NOT NULL,
  `balance_transaction` decimal(15,2) NOT NULL,
  `wt_status_history` ENUM('penambahan', 'pengembalian', 'terpakai') NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `wallet_history_ibfk_1` FOREIGN KEY (`id_wallet`) REFERENCES `wallet` (`id_wallet`) ON DELETE CASCADE
);

-- ── 17. wallet_topups (Midtrans Top-up) ─────────────────────
CREATE TABLE `wallet_topups` (
  `id` bigint UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `order_id` varchar(255) NOT NULL UNIQUE,
  `gross_amount` decimal(15,2) NOT NULL,
  `status` ENUM('pending', 'success', 'failed', 'expired') NOT NULL DEFAULT 'pending',
  `snap_token` varchar(255) NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `wallet_topups_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_users`) ON DELETE CASCADE
);

-- ── 18. personal_access_tokens (Laravel Sanctum) ────────────
CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL UNIQUE,
  `abilities` text NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`, `tokenable_id`)
);
