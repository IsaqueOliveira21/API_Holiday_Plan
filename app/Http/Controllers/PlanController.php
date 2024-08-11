<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Services\PdfService;
use App\Services\PlanService;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    private $service;
    private $pdfService;

    public function __construct(PlanService $planService, PdfService $pdfService)
    {
        $this->service = $planService;
        $this->pdfService = $pdfService;
    }

    public function index(Request $request) {
        $filters = $request->validate([
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'date' => 'nullable|string',
            'location' => 'nullable|string',
        ]);
        $plans = $this->service->index($filters);
        return PlanResource::collection($plans);
    }

    public function show($id) {
        $plan = $this->service->show($id);
        return new PlanResource($plan);
    }

    public function create(Request $request) {
        $input = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string'
        ]);

        $plan = $this->service->create($input);
        return new PlanResource($plan);
    }

    public function update(Plan $plan, Request $request) {
        $input = $request->validate([
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
            'location' => 'nullable|string'
        ]);
        $plan = $this->service->update($plan, $input);
        return new PlanResource($plan);
    }

    public function delete(Plan $plan) {
        return $this->service->delete($plan);
    }

    public function planParticipantAdd(Request $request, Plan $plan) {
        $input = $request->validate([
            'participants' => 'required|array'
        ]);
        $planParticipants = $this->service->planParticipantAdd($plan, $input);
        return new PlanResource($planParticipants);
    }

    public function planParticipantRemove(Request $request, Plan $plan) {
        $input = $request->validate([
            'participants' => 'required|array'
        ]);
        $planParticipants = $this->service->planParticipantRemove($plan, $input);
        return new PlanResource($planParticipants);
    }

    public function generatePlanPdf(Plan $plan) {
        $pdf = $this->pdfService->generatePlanPdf($plan);

        return response()->streamDownload(
            function () use ($pdf) {
                echo $pdf;
            },
            'plans.pdf',
        [   'Content-Type' => 'application/pdf']
        );
    }
}
