<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientFeedback extends Model
{
    use HasFactory;

    protected $table = 'client_feedbacks';

    protected $fillable = [
        'ticket_id',
        'client_type',
        'feedback_date',
        'sex',
        'age',
        'region',
        'services',
        'service_other',
        'cc1',
        'cc2',
        'cc3',
        'sqd1',
        'sqd2',
        'sqd3',
        'sqd4',
        'sqd5',
        'sqd6',
        'sqd7',
        'sqd8',
        'suggestions',
        'email',
    ];

    protected $casts = [
        'services' => 'array',
    ];

    public function ticket()
    {
        return $this->belongsTo(Tickets::class, 'ticket_id', 'ticket_id');
    }
}
