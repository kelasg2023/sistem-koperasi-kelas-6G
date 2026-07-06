<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Proses Registrasi User (API)
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6', // bisa ditambah |confirmed jika butuh password_confirmation
            'name'     => 'required|string|max:255',
            'address'  => 'nullable|string',
            'phone'    => 'nullable|string|max:14',
            'profile_picture' => 'nullable|string',
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
            $user->role = 'customer'; // Secara default semua pendaftar umum akan menjadi customer
            $user->save();

            // Insert ke tabel users_profiles menggunakan relasi model
            $user->profile()->create([
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'profile_picture' => $request->profile_picture,
            ]);

            \Illuminate\Support\Facades\DB::commit();

            $token = $user->createToken('auth_token')->plainTextToken;

            // Load data profile agar disertakan pada response JSON
            $user->load('profile');

            return $this->successResponse([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], 'Registrasi berhasil', 201);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return $this->errorResponse('Terjadi kesalahan saat registrasi: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Proses Login User (API)
     */
    public function login(Request $request)
    {
        // Deteksi input yang dikirimkan, bisa bernama 'login', 'email', atau 'username'
        $loginValue = $request->input('login') ?: $request->input('email') ?: $request->input('username');

        if (!$loginValue) {
            return $this->errorResponse('Username atau Email wajib diisi', 422);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
            'remember' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        // Cek apakah input tersebut adalah format email atau bukan
        $loginType = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($loginType, $loginValue)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Username/Email atau password salah', 401);
        }
        
        $remember = $request->boolean('remember', false);
        $expiresAt = $remember ? now()->addDays(7) : now()->addDay();

        $token = $user->createToken('auth_token', ['*'], $expiresAt)->plainTextToken;

        return $this->successResponse([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 'Login berhasil');
    }

    /**
     * Auto Login (Memeriksa Bearer Token)
     */
    public function autoLogin(Request $request)
    {
        $user = $request->user()->load('profile');

        return $this->successResponse([
            'user' => $user,
        ], 'Token valid, auto login berhasil');
    }

    /**
     * Proses Logout User (API)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logout berhasil');
    }

    /**
     * Ubah Password (Untuk user yang sedang login)
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|different:old_password',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        $user = $request->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return $this->errorResponse('Password lama tidak sesuai', 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        // Optional: Logout dari perangkat lain jika password diganti
        // $user->tokens()->where('id', '!=', $user->currentAccessToken()->id)->delete();

        return $this->successResponse(null, 'Password berhasil diubah');
    }

    /**
     * Reset Password (Khusus Admin untuk mereset password user lain)
     * Karena tabel users tidak memiliki kolom email, fitur "Lupa Password"
     * biasa tidak bisa menggunakan link token email. Sebagai gantinya, admin
     * bisa mereset secara manual lewat API ini.
     */
    public function adminResetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|exists:users,username',
            'new_password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        $user = User::where('username', $request->username)->first();
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Logout user dari semua sesi / token
        $user->tokens()->delete();

        return $this->successResponse(null, 'Password user ' . $user->username . ' berhasil direset');
    }

    /**
     * Kirim Link Reset Password via Email
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
                    ? $this->successResponse(null, __($status))
                    : $this->errorResponse(__($status), 400);
    }

    /**
     * Proses Reset Password dari link Email
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
                // Opsional: Logout dari semua session/token
                $user->tokens()->delete();
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? $this->successResponse(null, __($status))
                    : $this->errorResponse(__($status), 400);
    }

    /**
     * Dapatkan data profil user yang sedang login
     */
    public function getProfile(Request $request)
    {
        $user = $request->user()->load('profile');
        return $this->successResponse($user, 'Data profil berhasil diambil');
    }

    /**
     * Update data profil user (Gunakan method PATCH)
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'username' => 'prohibited', // Username tidak boleh diubah
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|nullable|string',
            'phone' => 'sometimes|nullable|string|max:14',
            'profile_picture' => 'sometimes|nullable|string',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id_users . ',id_users',
            'password' => 'sometimes|required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        // Update tabel users (email / password)
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // Update user's profile table using the relationship
        $profile = $user->profile;
        if ($profile->exists) {
            $profile->update($request->only(['name', 'address', 'phone', 'profile_picture']));
        } else {
            // Jika profil belum ada di database, pastikan kolom 'name' terisi minimal username
            $dataToCreate = $request->only(['name', 'address', 'phone', 'profile_picture']);
            if (empty($dataToCreate['name'])) {
                $dataToCreate['name'] = $user->username;
            }
            $profile->fill($dataToCreate)->save();
        }

        return $this->successResponse($user->load('profile'), 'Profil berhasil diperbarui');
    }

    /**
     * Dapatkan daftar user (Khusus Admin) - Hanya menampilkan username & role
     */
    public function getUsersAdmin(Request $request)
    {
        // Hanya ambil kolom username dan role
        $users = User::select('username', 'role')->get();
        return $this->successResponse($users, 'Daftar user berhasil diambil');
    }

    /**
     * Update role atau password user (Khusus Admin)
     */
    public function updateUserAdmin(Request $request, $username)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'sometimes|required|in:admin,staff,supplier,manager',
            'password' => 'sometimes|required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        $user = User::where('username', $username)->first();

        if (!$user) {
            return $this->errorResponse('User tidak ditemukan', 404);
        }

        if ($request->has('role')) {
            $user->role = $request->role;
        }

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
            // Logout user jika passwordnya diubah
            $user->tokens()->delete();
        }

        $user->save();

        return $this->successResponse([
            'username' => $user->username,
            'role' => $user->role
        ], 'Data user berhasil diperbarui');
    }
}
