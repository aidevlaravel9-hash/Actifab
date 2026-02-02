<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projecr_master';
    protected $primaryKey = 'project_id';

    protected $fillable = [
        'project_name',
        'creater_email',
        'creater_contact',
        'created_by',
        'customer_name',
        'customer_email',
        'customer_address',
        'contact_person',
        'contact_person_mobile',
        'contact_person_email',
        'inquiry_date',
        'attach_files',
        'iStatus'
    ];

    public $timestamps = true;
}
