<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\System\Role;
use App\Models\System\Menu;

class MenuRole extends Model
{
    use HasFactory;
    protected $table ="s_menu_role";
    protected $guarded = ['id'];

    public function Menu(){
        return $this->hasOne(Menu::class,'idmenu','id');
    }
    public function Role(){
        return $this->hasOne(Role::class,'id','idrole');
    }
}
