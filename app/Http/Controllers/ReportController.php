<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class ReportController extends Controller
{
    use ApiResponse;

    /**
     * Dapatkan laporan penjualan (Transaksi berhasil)
     */
    public function getSalesReports(Request $request)
    {
        $perPage = $request->query('per_page', 15);

        // Ambil transaksi yang statusnya 'berhasil'
        $transactions = Transaction::with(['user', 'details.barang'])
            ->where('status', 'berhasil')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return $this->successResponse($transactions, 'Berhasil mengambil laporan penjualan');
    }

    /**
     * Dapatkan laporan pelanggan (Total pengeluaran dan poin)
     */
    public function getCustomerReports(Request $request)
    {
        $perPage = $request->query('per_page', 15);

        // Ambil customer beserta total belanja dan jumlah pembelian (dari subquery)
        $customers = Customer::with(['user.profile'])
            ->whereHas('user', function($q) {
                $q->where('role', 'customer');
            })
            ->select('customers.*')
            ->selectRaw('(SELECT COALESCE(SUM(total_harga), 0) FROM transactions WHERE transactions.user_id = customers.user_id AND transactions.status = "berhasil") as total_pengeluaran')
            ->selectRaw('(SELECT COUNT(transaction_id) FROM transactions WHERE transactions.user_id = customers.user_id AND transactions.status = "berhasil") as jumlah_pembelian')
            ->orderBy('point', 'desc')
            ->paginate($perPage);

        return $this->successResponse($customers, 'Berhasil mengambil laporan pelanggan');
    }
}
