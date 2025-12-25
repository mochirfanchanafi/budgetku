<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\System\MenuRole;
use App\Models\User;
class Menu extends Model
{
    use HasFactory;
    protected $table ="s_menu";
    protected $guarded = ['id'];

    public function MenuRole(){
        return $this->hasMany(MenuRole::class,'idmenu','id');
    }
    public function Parent()
    {
        return $this->belongsTo(Menu::class, 'idmenu');
    }
    public function Children()
    {
        return $this->hasMany(Menu::class, 'idmenu');
    }
    public function Creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
