<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plans';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'date',
        'location'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    /*
    public function participants() {
        return $this->belongsToMany(PlanParticipant::class);
    }
    */

    public function participants() {
        return $this->belongsToMany(Participant::class, 'plans_participants', 'plan_id', 'participant_id');
    }
}
