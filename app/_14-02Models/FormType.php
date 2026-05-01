<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormType extends Model
{
    protected $table = 'form_type';
    protected $primaryKey = 'form_type_id';

    protected $fillable = ['form_type', 'iStatus'];
    public $timestamps = true;
}
