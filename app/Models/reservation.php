<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reservation extends Model
{
    use HasFactory;
    protected $table = 'reservates';
    protected $fillable = [
        'invitation',
        'email',
        'reserve_date_at',
        'checkin',
        'checkin_at',
    ];
}
