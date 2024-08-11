<?php

namespace App\Http\Controllers;

use App\Http\Resources\ParticipantResource;
use App\Models\Participant;
use App\Services\ParticipantService;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    private $service;

    public function __construct(ParticipantService $participantService)
    {
        $this->service = $participantService;
    }

    public function index(Request $request) {
        $filters = $request->validate([
            'name' => 'nullable|string'
        ]);
        $participants = $this->service->index($filters);
        return ParticipantResource::collection($participants);
    }

    public function show($id) {
        $participant = $this->service->show($id);
        return new ParticipantResource($participant);
    }

    public function create(Request $request) {
        $input = $request->validate([
            'name' => 'required|string'
        ]);
        $participant = $this->service->create($input);
        return new ParticipantResource($participant);
    }

    public function update(Participant $participant, Request $request) {
        $input = $request->validate([
            'name' => 'required|string'
        ]);
        $participant = $this->service->update($participant, $input);
        return new ParticipantResource($participant);
    }

    public function delete(Participant $participant) {
        return $this->service->delete($participant);
    }
}
