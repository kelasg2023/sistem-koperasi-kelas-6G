<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return response('Halaman Login Monolith (Akan diganti dengan view blade)', 200);
})->name('login');

// Route khusus untuk testing (akan membuat user dummy dan langsung login)
Route::get('/test-login/{role}', function ($role) {
    if (!in_array($role, ['admin', 'staff', 'supplier', 'manager'])) {
        return 'Role tidak valid!';
    }

    $user = \App\Models\User::firstOrCreate(
        ['username' => 'dummy_' . $role],
        [
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => $role
        ]
    );

    auth()->login($user);

    return redirect('/dashboard');
});

Route::middleware('auth')->group(function () {
    
    // Redirect /dashboard based on role
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        return redirect('/' . $role . '/dashboard');
    })->name('dashboard');

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return response('Admin Dashboard', 200);
        });
    });

    // Staff routes
    Route::middleware('role:staff')->prefix('staff')->group(function () {
        Route::get('/dashboard', function () {
            return response('Staff Dashboard', 200);
        });
    });

    // Supplier routes
    Route::middleware('role:supplier')->prefix('supplier')->group(function () {
        Route::get('/dashboard', function () {
            return response('Supplier Dashboard', 200);
        });
    });

    // Manager routes
    Route::middleware('role:manager')->prefix('manager')->group(function () {
        Route::get('/dashboard', function () {
            return response('Manager Dashboard', 200);
        });
    });

});
