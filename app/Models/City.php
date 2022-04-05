<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class City extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $hidden = ['relateUsers', 'created_at', 'updated_at'];

    protected $guard_name = 'api';


    // RELATIONS

    public function relateUsers()
    {
        return $this->belongsToMany(User::class);
    }


    // ATTRIBUTES

    public function getUsersAttribute(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->relateUsers;
    }

}
