<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use App\Models\Task;
use App\Jobs\DeleteCompletedTask;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_tasks()
    {
        Task::factory()->count(3)->create();
        $response = $this->getJson('/api/tasks');
        $response->assertStatus(200)->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_a_task()
    {
        $data = [
            'nome' => 'Nova tarefa',
            'descricao' => 'Descrição',
            'finalizado' => false
        ];
        $response = $this->postJson('/api/tasks', $data);
        $response->assertStatus(201)
            ->assertJsonFragment(['nome' => 'Nova tarefa']);
        $this->assertDatabaseHas('tasks', ['nome' => 'Nova tarefa']);
    }

    /** @test */
    public function it_validates_required_nome_when_creating()
    {
        $response = $this->postJson('/api/tasks', []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nome']);
    }

    /** @test */
    public function it_can_show_a_task()
    {
        $task = Task::factory()->create();
        $response = $this->getJson("/api/tasks/{$task->id}");
        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $task->id]);
    }

    /** @test */
    public function it_can_update_a_task()
    {
        $task = Task::factory()->create(['nome' => 'Antigo']);
        $data = ['nome' => 'Atualizado'];
        $response = $this->putJson("/api/tasks/{$task->id}", $data);
        $response->assertStatus(200)
            ->assertJsonFragment(['nome' => 'Atualizado']);
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'nome' => 'Atualizado']);
    }

    /** @test */
    public function it_can_delete_a_task()
    {
        $task = Task::factory()->create();
        $response = $this->deleteJson("/api/tasks/{$task->id}");
        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Tarefa excluída com sucesso.']);
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function it_can_toggle_task_completion()
    {
        Queue::fake(); // <-- Desativa processamento real dos jobs

        $task = Task::factory()->create(['finalizado' => false]);
        $response = $this->patchJson("/api/tasks/{$task->id}/toggle");
        $response->assertStatus(200);

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'finalizado' => true]);

        // (Opcional) Verifica se o job foi enfileirado
        Queue::assertPushed(DeleteCompletedTask::class, function ($job) use ($task) {
            return $job->taskId === $task->id;
        });
    }
}
