<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class City extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $hidden = ['created_at', 'updated_at'];
    // protected $appends = ['users'];

    protected $guard_name = 'api';


    // RELATIONS

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}
