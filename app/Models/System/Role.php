<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\System\UserRole;

class Role extends Model
{
    use HasFactory;
    protected $table ="s_role";
    protected $guarded = ['id'];

    public function UserRole(){
        return $this->hasOne(UserRole::class,'idrole','id');
    }
}
