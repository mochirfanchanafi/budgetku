<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\System\Menu;
use App\Models\User;
class Log extends Model
{
    use HasFactory;
    protected $table ="s_log";
    protected $guarded = ['id'];

    public function Menu()
    {
        return $this->belongsTo(Menu::class, 'menu', 'kode');
    }
    public function User(){
        return $this->belongsTo(User::class,'created_by','id');
    }
}
