<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagerDesignerUser extends Model
{
    protected $table = 'manager_designer_user';
    protected $primaryKey = 'manager_designer_user_id';

    public $timestamps = true;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'mobile_number',
        'password',
        'role_type',
        'status'
    ];
}