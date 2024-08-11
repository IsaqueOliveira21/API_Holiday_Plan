<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\PlanParticipant;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;

class PlanService {

    private $plan;
    private $planParticipant;

    public function __construct(Plan $plan, PlanParticipant $planParticipant)
    {
        $this->plan = $plan;
        $this->planParticipant = $planParticipant;
    }

    public function index(array $filters = null) {
        try {
            $plans = $this->plan->where('user_id', auth()->user()->id)
                ->when(isset($filters['title']), function($query) use ($filters) {
                    return $query->where('title', 'LIKE', "%".$filters['title']."%");
                })
                ->when(isset($filters['description']), function($query) use ($filters) {
                    return $query->where('description', 'LIKE', "%".$filters['description']."%");
                })
                ->when(isset($filters['date']), function($query) use ($filters) {
                    return $query->where('date', $filters['date']);
                })
                ->when(isset($filters['location']), function($query) use ($filters) {
                    return $query->where('location', 'LIKE', "%".$filters['location']."%");
                })
                ->with('participants')
                ->orderBy('date')
                ->get();
                return $plans;
        } catch(HttpResponseException $e) {
            throw new HttpResponseException(response()->json(['error' => $e->getMessage()], 500));
        }
    }

    public function show($id) {
        $plan = $this->plan->with('participants')->find($id);
        if($plan && $plan->user_id == auth()->user()->id) {
            return $plan;
        } else {
            return response()->json(['notFound' => 'Plan not found'], 404);
        }

    }

    public function create(array $input) {
        try {
            $plan = $this->plan->create([
                'user_id' => auth()->user()->id,
                'title' => $input['title'],
                'description' => $input['description'],
                'date' => $input['date'],
                'location' => $input['location']
            ]);
            return $plan;
        } catch(HttpResponseException $e) {
            throw new HttpResponseException(response()->json(['error' => $e->getMessage()], 500));
        }
    }

    public function update(Plan $plan, array $input) {
        try {
            if($plan->user_id !== auth()->user()->id) {
                return response()->json(['unauthorized' => 'You do not have access to this plan'], 200);
            }
            $plan->fill($input);
            $plan->save();
            return $plan;
        } catch(HttpResponseException $e) {
            throw new HttpResponseException(response()->json(['error' => $e->getMessage()], 500));
        }
    }

    public function delete(Plan $plan) {
        try{
            if($plan->user_id !== auth()->user()->id) {
                return response()->json(['unauthorized' => 'You do not have access to this plan'], 200);
            }
            $plan->delete();
            return response()->json(['success' => 'Plan deleted successfully'], 200);
        } catch(HttpResponseException $e) {
            throw new HttpResponseException(response()->json(['error' => $e->getMessage()], 500));
        }
    }

    public function planParticipantAdd(Plan $plan, array $input) {
        try {
            $participantsPlan = $plan->participants->pluck('id')->toArray();
            $participants = array_map(function($participantId) use ($plan, $participantsPlan) {
                if(!in_array($participantId, $participantsPlan)) {
                    return [
                        'plan_id' => $plan->id,
                        'participant_id' => $participantId,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }
            }, $input['participants']);
            $participants = array_filter($participants);
            $this->planParticipant->insert($participants);
            $plan = $plan->load('participants');
            return $plan;
        } catch(HttpResponseException $e) {
            throw new HttpResponseException(response()->json(['error' => $e->getMessage()], 500));
        }
    }

    public function planParticipantRemove(Plan $plan, array $input) {
        try {
            $this->planParticipant->where('plan_id', $plan->id)
                ->whereIn('participant_id', $input['participants'])
                ->delete();
            $plan = $plan->load('participants');
            return $plan;
        } catch(HttpResponseException $e) {
            throw new HttpResponseException(response()->json(['error' => $e->getMessage()], 500));
        }
    }
}
