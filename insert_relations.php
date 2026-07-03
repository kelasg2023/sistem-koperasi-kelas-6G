<?php

$relations = [
    'User' => "
    public function profile() { return \$this->hasOne(UserProfile::class, 'user_id', 'id_users'); }
    public function customer() { return \$this->hasOne(Customer::class, 'user_id', 'id_users'); }
    public function transactions() { return \$this->hasMany(Transaction::class, 'user_id', 'id_users'); }
    public function wallet() { return \$this->hasOne(Wallet::class, 'user_id', 'id_users'); }
",
    'UserProfile' => "
    public function user() { return \$this->belongsTo(User::class, 'user_id', 'id_users'); }
",
    'Kategori' => "
    public function barangs() { return \$this->hasMany(Barang::class, 'id_kategori', 'id_kategori'); }
",
    'Barang' => "
    public function kategori() { return \$this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori'); }
    public function merks() { return \$this->hasMany(Merk::class, 'barang_id', 'barang_id'); }
    public function suppliers() { return \$this->hasMany(Supplier::class, 'barang_id', 'barang_id'); }
    public function stokHistories() { return \$this->hasMany(StokHistory::class, 'barang_id', 'barang_id'); }
    public function vouchers() { return \$this->hasMany(Voucher::class, 'barang_id', 'barang_id'); }
    public function transactionDetails() { return \$this->hasMany(TransactionDetail::class, 'barang_id', 'barang_id'); }
",
    'Merk' => "
    public function barang() { return \$this->belongsTo(Barang::class, 'barang_id', 'barang_id'); }
    public function suppliers() { return \$this->hasMany(Supplier::class, 'merk_id', 'merk_id'); }
",
    'Supplier' => "
    public function merk() { return \$this->belongsTo(Merk::class, 'merk_id', 'merk_id'); }
    public function barang() { return \$this->belongsTo(Barang::class, 'barang_id', 'barang_id'); }
    public function stokHistories() { return \$this->hasMany(StokHistory::class, 'supplier_id', 'supplier_id'); }
",
    'StokHistory' => "
    public function supplier() { return \$this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id'); }
    public function barang() { return \$this->belongsTo(Barang::class, 'barang_id', 'barang_id'); }
",
    'Customer' => "
    public function user() { return \$this->belongsTo(User::class, 'user_id', 'id_users'); }
",
    'Voucher' => "
    public function barang() { return \$this->belongsTo(Barang::class, 'barang_id', 'barang_id'); }
    public function transactionDetails() { return \$this->hasMany(TransactionDetail::class, 'id_voucher', 'id_voucher'); }
",
    'Transaction' => "
    public function user() { return \$this->belongsTo(User::class, 'user_id', 'id_users'); }
    public function transactionDetails() { return \$this->hasMany(TransactionDetail::class, 'transaction_id', 'transaction_id'); }
    public function audit() { return \$this->hasOne(Audit::class, 'transaction_id', 'transaction_id'); }
",
    'TransactionDetail' => "
    public function transaction() { return \$this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id'); }
    public function barang() { return \$this->belongsTo(Barang::class, 'barang_id', 'barang_id'); }
    public function voucher() { return \$this->belongsTo(Voucher::class, 'id_voucher', 'id_voucher'); }
",
    'Audit' => "
    public function transaction() { return \$this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id'); }
",
    'Wallet' => "
    public function user() { return \$this->belongsTo(User::class, 'user_id', 'id_users'); }
    public function walletHistories() { return \$this->hasMany(WalletHistory::class, 'id_wallet', 'id_wallet'); }
",
    'WalletHistory' => "
    public function wallet() { return \$this->belongsTo(Wallet::class, 'id_wallet', 'id_wallet'); }
"
];

foreach ($relations as $model => $methods) {
    $file = __DIR__ . "/app/Models/{$model}.php";
    if (file_exists($file)) {
        $content = file_get_contents($file);
        // Find the last closing brace and insert methods before it
        $pos = strrpos($content, '}');
        if ($pos !== false) {
            $newContent = substr_replace($content, "\n" . $methods . "\n}", $pos, 1);
            file_put_contents($file, $newContent);
        }
    }
}
echo "Done\n";
