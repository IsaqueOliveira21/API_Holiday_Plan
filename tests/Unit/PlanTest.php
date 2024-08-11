<?php

namespace Tests\Unit;

use App\Models\Plan;
use App\Models\PlanParticipant;
use App\Services\PlanService;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase as BaseTestCase;

class PlanTest extends BaseTestCase
{
    protected $planService;

    protected function setUp(): void
    {
        parent::setUp();

        // Configure o usuário autenticado para os testes
        $user = \App\Models\User::first(); // Certifique-se de que há um usuário existente
        Auth::login($user);

        // Inicialize o serviço com o usuário autenticado
        $this->planService = new PlanService(new Plan(), new PlanParticipant());
    }

    public function test_index_returns_plans()
    {

        $plans = $this->planService->index();
        $this->assertEquals('New Plan', $plans->first()->title);
    }

    public function test_create_creates_plan()
    {
        // Define os dados de entrada
        $input = [
            'title' => 'New Plan',
            'description' => 'Description of the new plan',
            'date' => now(),
            'location' => 'Location',
        ];

        // Chama o método create
        $plan = $this->planService->create($input);

        // Verifica se o plano foi criado
        $this->assertInstanceOf(Plan::class, $plan);
        $this->assertEquals('New Plan', $plan->title);
    }

    public function test_show_returns_plan()
    {
        $plan = Plan::where('user_id', Auth::id())->get()[0];
        $result = $this->planService->show($plan->id);

        $this->assertInstanceOf(Plan::class, $result);
        $this->assertEquals($plan->id, $result->id);
    }

    public function test_update_updates_plan()
    {
        $plan = Plan::where('user_id', Auth::id())->get()[0];

        $input = [
            'title' => 'Updated Plan',
            'description' => 'Updated description',
            'date' => now(),
            'location' => 'Updated Location',
        ];

        $result = $this->planService->update($plan, $input);

        $this->assertInstanceOf(Plan::class, $result);
        $this->assertEquals('Updated Plan', $result->title);
    }

    public function test_delete_deletes_plan()
    {
        $plan = Plan::where('user_id', Auth::id())->get()[0];
        $response = $this->planService->delete($plan);

        $this->assertDatabaseMissing('plans', ['id' => $plan->id]);
        $this->assertEquals(200, $response->status());
    }
}
