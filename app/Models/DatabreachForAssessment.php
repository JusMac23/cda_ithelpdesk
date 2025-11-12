<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatabreachForAssessment extends Model
{
    use HasFactory;

    protected $table = 'databreach_for_assessment';

    protected $primaryKey = 'dbn_id';

    public $timestamps = true;

    protected $fillable = [
        'dbn_number',
        'sender_fullname',    
        'sender_email',          
        'date_occurrence',      
        'date_discovery',       
        'date_notification',
        'pic',                  
        'brief_summary',         
    ];

    protected $casts = [
        'date_occurrence' => 'datetime',
        'date_discovery' => 'datetime',
        'date_notification' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

}