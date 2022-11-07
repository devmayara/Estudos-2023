<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRekation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'userrelations';
}
