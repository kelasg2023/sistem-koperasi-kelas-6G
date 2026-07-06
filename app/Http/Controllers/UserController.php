<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiResponse;

    // Menampilkan semua user (Admin)
    public function index()
    {
        $users = User::with('profile', 'customer')->get();
        return $this->successResponse($users, 'Data semua user berhasil diambil');
    }

    // Menampilkan detail user (Admin)
    public function show($id)
    {
        $user = User::with('profile', 'customer')->find($id);
        
        if (!$user) {
            return $this->errorResponse('User tidak ditemukan', 404);
        }

        return $this->successResponse($user, 'Data detail user berhasil diambil');
    }

    // Membuat user baru secara manual oleh Admin
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:admin,staff,supplier,manager,customer',
            'name'     => 'required|string|max:255',
            'address'  => 'nullable|string',
            'phone'    => 'nullable|string|max:14',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $user = new User();
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = $request->role;
            $user->save();

            // Insert ke profil
            $user->profile()->create([
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
            ]);
            
            if ($request->role === 'customer') {
                $user->customer()->create([
                    'point' => 0,
                    'is_member' => false
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            return $this->successResponse($user->load('profile', 'customer'), 'User baru berhasil dibuat', 201);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return $this->errorResponse('Terjadi kesalahan: ' . $e->getMessage(), 500);
        }
    }

    // Mengedit data user
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return $this->errorResponse('User tidak ditemukan', 404);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $id . ',id_users',
            'email'    => 'sometimes|required|string|email|max:255|unique:users,email,' . $id . ',id_users',
            'password' => 'sometimes|nullable|string|min:6',
            'role'     => 'sometimes|required|in:admin,staff,supplier,manager,customer',
            'name'     => 'sometimes|required|string|max:255',
            'address'  => 'nullable|string',
            'phone'    => 'nullable|string|max:14',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            if ($request->has('username')) $user->username = $request->username;
            if ($request->has('email')) $user->email = $request->email;
            if ($request->has('password') && $request->password) $user->password = Hash::make($request->password);
            if ($request->has('role')) $user->role = $request->role;
            
            $user->save();

            // Update profil
            if ($user->profile) {
                $profileData = $request->only(['name', 'address', 'phone']);
                if (!empty($profileData)) {
                    $user->profile->update($profileData);
                }
            }

            \Illuminate\Support\Facades\DB::commit();

            return $this->successResponse($user->load('profile', 'customer'), 'Data user berhasil diupdate');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return $this->errorResponse('Terjadi kesalahan: ' . $e->getMessage(), 500);
        }
    }

    // Menghapus user
    public function destroy($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return $this->errorResponse('User tidak ditemukan', 404);
        }

        $user->delete();
        
        return $this->successResponse(null, 'User berhasil dihapus');
    }
}
