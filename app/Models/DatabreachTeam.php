<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatabreachTeam extends Model
{
    use HasFactory;

    protected $table = 'databreach_dbrt_team'; 

    protected $primaryKey = 'dbrt_id';

    protected $fillable = [
        'firstname',
        'middle_initial',
        'lastname',
        'email',
        'region',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}