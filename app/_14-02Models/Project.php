<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = [
        'registration_id',
        'inquiry_no',
        'inquiry_date',
        'project_name',
        'customer_name',
        'customer_email',
        'customer_address',
        'contact_person_name',
        'contact_person_mobile',
        'contact_person_email',
        'attachment'
    ];

    /**
     * Relationship with Registration
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id', 'id');
    }
}
