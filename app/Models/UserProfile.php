<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'name', 'address', 'profile_picture', 'phone', 'is_member'])]
class UserProfile extends Model
{
    protected $table = 'users_profiles';
    protected $primaryKey = 'profiles_id';
    public $timestamps = false;


    public function user() { return $this->belongsTo(User::class, 'user_id', 'id_users'); }

}
