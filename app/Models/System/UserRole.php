<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\System\Role;

class UserRole extends Model
{
    use HasFactory;
    protected $table ="s_user_role";
    protected $guarded = ['id'];

    public function Role(){
        return $this->belongsTo(Role::class,'idrole','id');
    }
}
