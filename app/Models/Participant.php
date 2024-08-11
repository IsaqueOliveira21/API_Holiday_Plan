<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $table = 'participants';

    protected $fillable = [
        'user_id',
        'name'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    /*
    public function plans() {
        return $this->belongsToMany(PlanParticipant::class);
    }
    */

    public function plans() {
        return $this->belongsToMany(Plan::class, 'plans_participants', 'participant_id', 'plan_id');
    }
}
