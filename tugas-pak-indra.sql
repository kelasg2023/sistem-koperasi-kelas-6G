CREATE TABLE `audit` (
  `audit_id` int PRIMARY KEY NOT NULL,
  `transaction_id` int NOT NULL,
  `status_audit` varchar(50) NOT NULL,
  `tanggal_audit` datetime DEFAULT (CURRENT_TIMESTAMP),
  `info_audit_lama` varchar(255),
  `info_audit_baru` varchar(255)
);

CREATE TABLE `barang` (
  `barang_id` int PRIMARY KEY NOT NULL,
  `nama` varchar(255) NOT NULL,
  `stok` int DEFAULT '0',
  `harga` decimal(15,2) NOT NULL,
  `diskon_persen` decimal(5,2) DEFAULT '0.00',
  `deskripsi` text,
  `id_kategori` int NOT NULL,
  `deleted_at` timestamp
);

CREATE TABLE `customers` (
  `customers_id` int PRIMARY KEY NOT NULL,
  `user_id` int NOT NULL,
  `point` int DEFAULT '0'
);

CREATE TABLE `kategori` (
  `id_kategori` int PRIMARY KEY NOT NULL,
  `nama_kategori` varchar(50) NOT NULL,
  `satuan` varchar(10) NOT NULL
);

CREATE TABLE `merk` (
  `merk_id` int PRIMARY KEY NOT NULL,
  `nama_merk` varchar(50) NOT NULL,
  `barang_id` int NOT NULL
);

CREATE TABLE `stok_history` (
  `stok_history_id` int PRIMARY KEY NOT NULL,
  `supplier_id` int,
  `barang_id` int NOT NULL,
  `jumlah` int NOT NULL,
  `stok_awal` int NOT NULL,
  `stok_akhir` int NOT NULL,
  `keterangan` varchar(255),
  `stok_mutasi` ENUM ('keluar', 'lainnya', 'masuk') NOT NULL,
  `created_at` timestamp DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `supplier` (
  `supplier_id` int PRIMARY KEY NOT NULL,
  `merk_id` int NOT NULL,
  `barang_id` int NOT NULL,
  `harga_beli` decimal(15,2) NOT NULL,
  `jumlah` int NOT NULL,
  `status` tinyint(1) DEFAULT '1'
);

CREATE TABLE `transactions` (
  `transaction_id` int PRIMARY KEY NOT NULL,
  `user_id` int NOT NULL,
  `total_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` ENUM ('berhasil', 'proses', 'gagal', 'refund') NOT NULL DEFAULT 'proses',
  `payment_method` ENUM ('cash', 'qris', 'transfer', 'wallet') NOT NULL DEFAULT 'cash',
  `created_at` timestamp DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `transaction_details` (
  `detail_id` int PRIMARY KEY NOT NULL,
  `transaction_id` int NOT NULL,
  `barang_id` int NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `id_voucher` int
);

CREATE TABLE `users` (
  `id_users` int PRIMARY KEY NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` ENUM ('admin', 'staff', 'supplier', 'manager') NOT NULL,
  `created_at` timestamp DEFAULT (CURRENT_TIMESTAMP),
  `deleted_at` timestamp
);

CREATE TABLE `users_profiles` (
  `profiles_id` int PRIMARY KEY NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text,
  `profile_picture` varchar(255),
  `phone` varchar(14),
  `is_member` tinyint(1) DEFAULT '0'
);

CREATE TABLE `vouchers` (
  `id_voucher` int PRIMARY KEY NOT NULL,
  `kode_voucher` varchar(50) NOT NULL,
  `potongan_persen` decimal(5,2) DEFAULT '0.00',
  `kuota` int DEFAULT '0',
  `barang_id` int NOT NULL,
  `expired_at` datetime NOT NULL,
  `created_at` timestamp DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `wallet` (
  `id_wallet` int PRIMARY KEY NOT NULL,
  `user_id` int NOT NULL,
  `balance` decimal(15,2) DEFAULT '0.00'
);

CREATE TABLE `wallet_history` (
  `id_wt_history` int PRIMARY KEY NOT NULL,
  `id_wallet` int NOT NULL,
  `balance_transaction` decimal(15,2) NOT NULL,
  `wt_status_history` ENUM ('penambahan', 'pengembalian', 'terpakai') NOT NULL,
  `created_at` timestamp DEFAULT (CURRENT_TIMESTAMP)
);

ALTER TABLE `audit` ADD CONSTRAINT `audit_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`) ON DELETE CASCADE;

ALTER TABLE `barang` ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE RESTRICT;

ALTER TABLE `customers` ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_users`) ON DELETE CASCADE;

ALTER TABLE `merk` ADD CONSTRAINT `merk_ibfk_1` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`) ON DELETE CASCADE;

ALTER TABLE `stok_history` ADD CONSTRAINT `stok_history_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`) ON DELETE SET NULL;

ALTER TABLE `stok_history` ADD CONSTRAINT `stok_history_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`) ON DELETE CASCADE;

ALTER TABLE `supplier` ADD CONSTRAINT `supplier_ibfk_1` FOREIGN KEY (`merk_id`) REFERENCES `merk` (`merk_id`) ON DELETE RESTRICT;

ALTER TABLE `supplier` ADD CONSTRAINT `supplier_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`) ON DELETE RESTRICT;

ALTER TABLE `transactions` ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_users`) ON DELETE RESTRICT;

ALTER TABLE `transaction_details` ADD CONSTRAINT `transaction_details_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`) ON DELETE CASCADE;

ALTER TABLE `transaction_details` ADD CONSTRAINT `transaction_details_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`) ON DELETE RESTRICT;

ALTER TABLE `transaction_details` ADD CONSTRAINT `transaction_details_ibfk_3` FOREIGN KEY (`id_voucher`) REFERENCES `vouchers` (`id_voucher`) ON DELETE RESTRICT;

ALTER TABLE `users_profiles` ADD CONSTRAINT `users_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_users`) ON DELETE CASCADE;

ALTER TABLE `vouchers` ADD CONSTRAINT `vouchers_ibfk_1` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`) ON DELETE CASCADE;

ALTER TABLE `wallet` ADD CONSTRAINT `wallet_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_users`) ON DELETE CASCADE;

ALTER TABLE `wallet_history` ADD CONSTRAINT `wallet_history_ibfk_1` FOREIGN KEY (`id_wallet`) REFERENCES `wallet` (`id_wallet`) ON DELETE CASCADE;
