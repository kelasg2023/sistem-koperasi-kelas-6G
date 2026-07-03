<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

}
