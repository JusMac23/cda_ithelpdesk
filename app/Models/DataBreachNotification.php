<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataBreachNotification extends Model
{
    use HasFactory;

    protected $table = 'databreach_notifications';

    protected $primaryKey = 'dbn_id';

    public $timestamps = true;

    protected $fillable = [
        'sender_fullname',
        'sender_email',
        'dbn_number',
        'pic',
        'email',
        'representative',
        'representative_email_address',
        'date_occurrence',
        'date_discovery',
        'date_notification',
        'brief_summary',
        'notification_type_description',
        'sector_name',
        'subsector_name',
        'notification_type',
        'timeliness',
        'general_cause',
        'specific_cause',
        'general_incident',
        'with_request',
        'how_breach_occured',
        'chronology',
        'num_records',
        'hundred_plus',
        'num_records_provide_details',
        'description_nature',
        'likely_consequences',
        'dpo',
        'spi',
        'other_info',
        'measures_to_address',
        'measures_to_secure',
        'actions_to_mitigate',
        'actions_to_inform',
        'actions_to_prevent',
        'record_type',
        'data_subjects',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'notification_type_description' => 'array',
        'date_occurrence' => 'datetime',
        'date_discovery' => 'datetime',
        'date_notification' => 'datetime',
        'hundred_plus' => 'boolean',
        'num_records' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'hundred_plus' => false,
    ];
}