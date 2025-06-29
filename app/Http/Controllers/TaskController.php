<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use App\Jobs\DeleteCompletedTask;

class TaskController extends Controller
{
    // Listar todas as tarefas não deletadas, com cache (sem tags)
    public function index(): JsonResponse
    {
        $tasks = Cache::remember('tasks_list', 60, function () {
            return Task::whereNull('deleted_at')->orderByDesc('created_at')->get();
        });
        return response()->json($tasks);
    }

    // Criar uma nova tarefa, invalida cache
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nome'       => 'required|string|max:255',
            'descricao'  => 'nullable|string',
            'finalizado' => 'boolean',
            'data_limite'=> 'nullable|date',
        ]);
        $task = Task::create($data);
        Cache::forget('tasks_list');
        return response()->json($task, 201);
    }

    // Visualizar uma tarefa específica, com cache (sem tags)
    public function show(Task $task): JsonResponse
    {
        $taskCached = Cache::remember("task_{$task->id}", 60, function () use ($task) {
            return $task;
        });
        return response()->json($taskCached);
    }

    // Atualizar uma tarefa, invalida cache
    public function update(Request $request, Task $task): JsonResponse
    {
        $data = $request->validate([
            'nome'       => 'sometimes|required|string|max:255',
            'descricao'  => 'nullable|string',
            'finalizado' => 'boolean',
            'data_limite'=> 'nullable|date',
        ]);
        $task->update($data);
        Cache::forget('tasks_list');
        Cache::forget("task_{$task->id}");
        return response()->json($task);
    }

    // Soft delete, invalida cache
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();
        Cache::forget('tasks_list');
        Cache::forget("task_{$task->id}");
        return response()->json(['message' => 'Tarefa excluída com sucesso.']);
    }

    // Toggle finalizado/não finalizado, agenda job, invalida cache
    public function toggle(Task $task): JsonResponse
    {
        $task->finalizado = !$task->finalizado;
        $task->save();

        if ($task->finalizado) {
            DeleteCompletedTask::dispatch($task->id)->delay(now()->addMinutes(10));
        }

        Cache::forget('tasks_list');
        Cache::forget("task_{$task->id}");
        return response()->json($task);
    }
}
