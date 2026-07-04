<?php

namespace App\Models;

<<<<<<< HEAD
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
=======
>>>>>>> origin/develop/back-end
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

<<<<<<< HEAD
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
=======
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['username', 'password', 'role'])]
#[Hidden(['password'])]
class User extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'id_users';

    use HasFactory, Notifiable, SoftDeletes;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }


    public function profile() { return $this->hasOne(UserProfile::class, 'user_id', 'id_users'); }
    public function customer() { return $this->hasOne(Customer::class, 'user_id', 'id_users'); }
    public function transactions() { return $this->hasMany(Transaction::class, 'user_id', 'id_users'); }
    public function wallet() { return $this->hasOne(Wallet::class, 'user_id', 'id_users'); }
    public function voucherClaims() { return $this->hasMany(VoucherClaim::class, 'user_id', 'id_users'); }

>>>>>>> origin/develop/back-end
}
