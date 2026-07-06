<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Barang;
use App\Models\Wallet;
use App\Models\Customer;
use App\Models\Supplier;
use App\Traits\ApiResponse;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    use ApiResponse;

    /**
     * Dashboard untuk role Admin
     */
    public function getAdminDashboard(Request $request)
    {
        $totalUsers = User::count();
        $totalBarang = Barang::count();
        $totalTransaksi = Transaction::count();
        
        $pendingOrders = Transaction::where('status', 'proses')->count();
        $totalRevenue = Transaction::where('status', 'berhasil')->sum('total_harga');

        return $this->successResponse([
            'total_users' => $totalUsers,
            'total_barang' => $totalBarang,
            'total_transaksi' => $totalTransaksi,
            'pending_orders' => $pendingOrders,
            'total_revenue' => (float) $totalRevenue
        ], 'Admin Dashboard Data');
    }

    /**
     * Dashboard untuk role Staff
     */
    public function getStaffDashboard(Request $request)
    {
        $today = Carbon::today();
        
        $salesToday = Transaction::where('status', 'berhasil')
            ->whereDate('created_at', $today)
            ->sum('total_harga');
            
        $ordersToday = Transaction::whereDate('created_at', $today)->count();
        $pendingOrders = Transaction::where('status', 'proses')->count();
        
        $lowStockItems = Barang::where('stok', '<=', 5)->count();

        return $this->successResponse([
            'sales_today' => (float) $salesToday,
            'orders_today' => $ordersToday,
            'pending_orders' => $pendingOrders,
            'low_stock_items' => $lowStockItems
        ], 'Staff Dashboard Data');
    }

    /**
     * Dashboard untuk role Supplier
     */
    public function getSupplierDashboard(Request $request)
    {
        $user = $request->user();
        
        // Asumsi data global supplier atau spesifik jika di-link ke user
        $totalSuppliedItems = Supplier::where('status', 1)->count();
        
        return $this->successResponse([
            'total_supplied_items' => $totalSuppliedItems,
            'pending_orders' => 0 // Placeholder until further logic is defined
        ], 'Supplier Dashboard Data');
    }

    /**
     * Dashboard untuk role Manager
     */
    public function getManagerDashboard(Request $request)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $monthlyRevenue = Transaction::where('status', 'berhasil')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total_harga');
            
        $yearlyRevenue = Transaction::where('status', 'berhasil')
            ->whereYear('created_at', $currentYear)
            ->sum('total_harga');
            
        $totalOrdersThisMonth = Transaction::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        return $this->successResponse([
            'monthly_revenue' => (float) $monthlyRevenue,
            'yearly_revenue' => (float) $yearlyRevenue,
            'orders_this_month' => $totalOrdersThisMonth
        ], 'Manager Dashboard Data');
    }

    /**
     * Dashboard dinamis (fallback atau untuk Customer)
     */
    public function getDynamicDashboard(Request $request)
    {
        $user = $request->user();
        
        $dashboardData = [];
        
        if ($user->role === 'customer') {
            $totalSpent = Transaction::where('user_id', $user->id_users)
                ->where('status', 'berhasil')
                ->sum('total_harga');
                
            $totalOrders = Transaction::where('user_id', $user->id_users)->count();
            
            $wallet = Wallet::where('user_id', $user->id_users)->first();
            $customer = Customer::where('user_id', $user->id_users)->first();
            
            $dashboardData = [
                'total_spent' => (float) $totalSpent,
                'total_orders' => $totalOrders,
                'wallet_balance' => $wallet ? (float) $wallet->balance : 0,
                'points' => $customer ? $customer->point : 0,
                'is_member' => $customer ? (bool) $customer->is_member : false
            ];
        } else {
            // Forward ke role-specific if hit generic endpoint
            if ($user->role === 'admin') return $this->getAdminDashboard($request);
            if ($user->role === 'staff') return $this->getStaffDashboard($request);
            if ($user->role === 'manager') return $this->getManagerDashboard($request);
            if ($user->role === 'supplier') return $this->getSupplierDashboard($request);
        }

        return response()->json([
            'success' => true,
            'message' => 'Selamat datang di dashboard ' . ucfirst($user->role),
            'data' => [
                'user' => $user,
                'role' => $user->role,
                'dashboard_metrics' => $dashboardData
            ]
        ]);
    }
}
