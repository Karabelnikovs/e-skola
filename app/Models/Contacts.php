<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{

    protected $fillable = [
        'addresses',
        'phone_numbers',
        'emails',
    ];
}
