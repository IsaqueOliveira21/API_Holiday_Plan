<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanParticipant extends Model
{
    use HasFactory;

    protected $table = 'plans_participants';

    protected $fillable = [
        'plan_id',
        'participant_id'
    ];
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}
