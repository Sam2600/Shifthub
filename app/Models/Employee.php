<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    protected $fillable = [

        "photo",
        "name",
        "email",
        "phone",
        "dateOfBirth",
        "nrc",
        "gender",
        "address",
        "language",
        "career",
        "level",
    ];
}
