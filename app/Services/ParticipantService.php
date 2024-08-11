<?php

namespace App\Services;

use App\Models\Participant;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class ParticipantService {

    private $participant;

    public function __construct(Participant $participant)
    {
        $this->participant = $participant;
    }

    public function index(array $filters = null) {
        $participants = $this->participant->where('user_id', auth()->user()->id)
            ->when(isset($filters['name']), function($query) use ($filters) {
                return $query->where('name', 'LIKE', "%".$filters['name']."%");
            })
            ->orderBy('name')
            ->get();
        return $participants;
    }

    public function show($id) {
        $participant = $this->participant->find($id);
        if($participant && $participant->user_id == auth()->user()->id) {
            return $participant;
        } else {
            return response()->json(['notFound' => 'Participant not found'], 404);
        }
    }

    public function create(array $input) {
        try {
            $participant = $this->participant->create([
                'user_id' => auth()->user()->id,
                'name' => $input['name']
            ]);
            return $participant;
        } catch(HttpResponseException $e) {
            throw new HttpResponseException(response()->json(['error' => $e->getMessage()], 500));
        }
    }

    public function update(Participant $participant, array $input) {
        try {
            if($participant->user_id !== auth()->user()->id) {
                return response()->json(['unauthorized' => 'You do not have access to this participant'], 200);
            }
            $participant = $participant->fill($input);
            $participant->save();
            return $participant;
        } catch(HttpResponseException $e) {
            throw new HttpResponseException(response()->json(['error' => $e->getMessage()], 500));
        }
    }

    public function delete(Participant $participant) {
        try{
            if($participant->user_id !== auth()->user()->id) {
                return response()->json(['unauthorized' => 'You do not have access to this participant'], 200);
            }
            $participant->delete();
            return response()->json(['success' => 'Participant deleted successfully'], 200);
        } catch(HttpResponseException $e) {
            throw new HttpResponseException(response()->json(['error' => $e->getMessage()], 500));
        }
    }
}
