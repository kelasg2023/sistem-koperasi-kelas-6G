<?php
$models = [
    'User' => [
        'table' => 'users',
        'primaryKey' => 'id_users',
        'fillable' => "['username', 'password', 'role']",
        'extra' => "    use HasFactory, Notifiable;\n\n    protected function casts(): array\n    {\n        return [\n            'password' => 'hashed',\n        ];\n    }",
        'imports' => "use Illuminate\Database\Eloquent\Attributes\Fillable;\nuse Illuminate\Database\Eloquent\Attributes\Hidden;\nuse Illuminate\Database\Eloquent\Factories\HasFactory;\nuse Illuminate\Foundation\Auth\User as Authenticatable;\nuse Illuminate\Notifications\Notifiable;",
        'extends' => 'Authenticatable',
        'attributes' => "#[Fillable(['username', 'password', 'role'])]\n#[Hidden(['password'])]"
    ],
    'UserProfile' => [
        'table' => 'users_profiles',
        'primaryKey' => 'profiles_id',
        'fillable' => "['user_id', 'name', 'address', 'profile_picture', 'phone', 'is_member']",
        'imports' => "use Illuminate\Database\Eloquent\Attributes\Fillable;\nuse Illuminate\Database\Eloquent\Model;",
        'extends' => 'Model',
        'attributes' => "#[Fillable(['user_id', 'name', 'address', 'profile_picture', 'phone', 'is_member'])]",
        'timestamps' => false // wait, we didn't remove timestamps from migration. Actually, in migration we have $table->timestamps() No, we don't for most tables!
    ],
    'Kategori' => [
        'table' => 'kategori',
        'primaryKey' => 'id_kategori',
        'fillable' => "['nama_kategori', 'satuan']",
        'imports' => "use Illuminate\Database\Eloquent\Attributes\Fillable;\nuse Illuminate\Database\Eloquent\Model;",
        'extends' => 'Model',
        'attributes' => "#[Fillable(['nama_kategori', 'satuan'])]",
        'timestamps' => false
    ],
    'Barang' => [
        'table' => 'barang',
        'primaryKey' => 'barang_id',
        'fillable' => "['nama', 'stok', 'harga', 'diskon_persen', 'deskripsi', 'id_kategori']",
        'imports' => "use Illuminate\Database\Eloquent\Attributes\Fillable;\nuse Illuminate\Database\Eloquent\Model;\nuse Illuminate\Database\Eloquent\SoftDeletes;",
        'extends' => 'Model',
        'attributes' => "#[Fillable(['nama', 'stok', 'harga', 'diskon_persen', 'deskripsi', 'id_kategori'])]",
        'extra' => "    use SoftDeletes;\n\n    public \$timestamps = false;"
    ],
    'Merk' => [
        'table' => 'merk',
        'primaryKey' => 'merk_id',
        'fillable' => "['nama_merk', 'barang_id']",
        'imports' => "use Illuminate\Database\Eloquent\Attributes\Fillable;\nuse Illuminate\Database\Eloquent\Model;",
        'extends' => 'Model',
        'attributes' => "#[Fillable(['nama_merk', 'barang_id'])]",
        'timestamps' => false
    ],
    'Supplier' => [
        'table' => 'supplier',
        'primaryKey' => 'supplier_id',
        'fillable' => "['merk_id', 'barang_id', 'harga_beli', 'jumlah', 'status']",
        'imports' => "use Illuminate\Database\Eloquent\Attributes\Fillable;\nuse Illuminate\Database\Eloquent\Model;",
        'extends' => 'Model',
        'attributes' => "#[Fillable(['merk_id', 'barang_id', 'harga_beli', 'jumlah', 'status'])]",
        'timestamps' => false
    ],
    'StokHistory' => [
        'table' => 'stok_history',
        'primaryKey' => 'stok_history_id',
        'fillable' => "['supplier_id', 'barang_id', 'jumlah', 'stok_awal', 'stok_akhir', 'keterangan', 'stok_mutasi']",
        'imports' => "use Illuminate\Database\Eloquent\Attributes\Fillable;\nuse Illuminate\Database\Eloquent\Model;",
        'extends' => 'Model',
        'attributes' => "#[Fillable(['supplier_id', 'barang_id', 'jumlah', 'stok_awal', 'stok_akhir', 'keterangan', 'stok_mutasi'])]",
        'timestamps' => false
    ],
    'Customer' => [
        'table' => 'customers',
        'primaryKey' => 'customers_id',
        'fillable' => "['user_id', 'point']",
        'imports' => "use Illuminate\Database\Eloquent\Attributes\Fillable;\nuse Illuminate\Database\Eloquent\Model;",
        'extends' => 'Model',
        'attributes' => "#[Fillable(['user_id', 'point'])]",
        'timestamps' => false
    ],
    'Voucher' => [
        'table' => 'vouchers',
        'primaryKey' => 'id_voucher',
        'fillable' => "['kode_voucher', 'potongan_persen', 'kuota', 'barang_id', 'expired_at']",
        'imports' => "use Illuminate\Database\Eloquent\Attributes\Fillable;\nuse Illuminate\Database\Eloquent\Model;",
        'extends' => 'Model',
        'attributes' => "#[Fillable(['kode_voucher', 'potongan_persen', 'kuota', 'barang_id', 'expired_at'])]",
        'timestamps' => false
    ],
    'Transaction' => [
        'table' => 'transactions',
        'primaryKey' => 'transaction_id',
        'fillable' => "['user_id', 'total_harga', 'status', 'payment_method']",
        'imports' => "use Illuminate\Database\Eloquent\Attributes\Fillable;\nuse Illuminate\Database\Eloquent\Model;",
        'extends' => 'Model',
        'attributes' => "#[Fillable(['user_id', 'total_harga', 'status', 'payment_method'])]",
        'timestamps' => false
    ],
    'TransactionDetail' => [
        'table' => 'transaction_details',
        'primaryKey' => 'detail_id',
        'fillable' => "['transaction_id', 'barang_id', 'jumlah', 'harga_satuan', 'id_voucher']",
        'imports' => "use Illuminate\Database\Eloquent\Attributes\Fillable;\nuse Illuminate\Database\Eloquent\Model;",
        'extends' => 'Model',
        'attributes' => "#[Fillable(['transaction_id', 'barang_id', 'jumlah', 'harga_satuan', 'id_voucher'])]",
        'timestamps' => false
    ],
    'Audit' => [
        'table' => 'audit',
        'primaryKey' => 'audit_id',
        'fillable' => "['transaction_id', 'status_audit', 'info_audit_lama', 'info_audit_baru']",
        'imports' => "use Illuminate\Database\Eloquent\Attributes\Fillable;\nuse Illuminate\Database\Eloquent\Model;",
        'extends' => 'Model',
        'attributes' => "#[Fillable(['transaction_id', 'status_audit', 'info_audit_lama', 'info_audit_baru'])]",
        'timestamps' => false
    ],
    'Wallet' => [
        'table' => 'wallet',
        'primaryKey' => 'id_wallet',
        'fillable' => "['user_id', 'balance']",
        'imports' => "use Illuminate\Database\Eloquent\Attributes\Fillable;\nuse Illuminate\Database\Eloquent\Model;",
        'extends' => 'Model',
        'attributes' => "#[Fillable(['user_id', 'balance'])]",
        'timestamps' => false
    ],
    'WalletHistory' => [
        'table' => 'wallet_history',
        'primaryKey' => 'id_wt_history',
        'fillable' => "['id_wallet', 'balance_transaction', 'wt_status_history']",
        'imports' => "use Illuminate\Database\Eloquent\Attributes\Fillable;\nuse Illuminate\Database\Eloquent\Model;",
        'extends' => 'Model',
        'attributes' => "#[Fillable(['id_wallet', 'balance_transaction', 'wt_status_history'])]",
        'timestamps' => false
    ]
];

foreach ($models as $name => $config) {
    $file = __DIR__ . "/app/Models/{$name}.php";
    $extends = $config['extends'];
    $imports = $config['imports'];
    $attributes = $config['attributes'];
    
    $body = "";
    if (isset($config['table'])) {
        $body .= "    protected \$table = '{$config['table']}';\n";
    }
    if (isset($config['primaryKey'])) {
        $body .= "    protected \$primaryKey = '{$config['primaryKey']}';\n";
    }
    if (isset($config['timestamps']) && $config['timestamps'] === false) {
        $body .= "    public \$timestamps = false;\n";
    }
    if (isset($config['extra'])) {
        $body .= "\n{$config['extra']}\n";
    }
    
    $content = "<?php\n\nnamespace App\Models;\n\n{$imports}\n\n{$attributes}\nclass {$name} extends {$extends}\n{\n{$body}}\n";
    file_put_contents($file, $content);
}
echo "Done\n";
