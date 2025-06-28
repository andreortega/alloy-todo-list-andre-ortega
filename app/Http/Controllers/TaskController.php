<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    // Listar todas as tarefas não deletadas
    public function index(): JsonResponse
    {
        $tasks = Task::whereNull('deleted_at')->orderByDesc('created_at')->get();
        return response()->json($tasks);
    }

    // Criar uma nova tarefa
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nome'       => 'required|string|max:255',
            'descricao'  => 'nullable|string',
            'finalizado' => 'boolean',
            'data_limite'=> 'nullable|date',
        ]);
        $task = Task::create($data);
        return response()->json($task, 201);
    }

    // Visualizar uma tarefa específica
    public function show(Task $task): JsonResponse
    {
        return response()->json($task);
    }

    // Atualizar uma tarefa
    public function update(Request $request, Task $task): JsonResponse
    {
        $data = $request->validate([
            'nome'       => 'sometimes|required|string|max:255',
            'descricao'  => 'nullable|string',
            'finalizado' => 'boolean',
            'data_limite'=> 'nullable|date',
        ]);
        $task->update($data);
        return response()->json($task);
    }

    // Soft delete
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();
        return response()->json(['message' => 'Tarefa excluída com sucesso.']);
    }

    // Toggle finalizado/não finalizado
    public function toggle(Task $task): JsonResponse
    {
        $task->finalizado = !$task->finalizado;
        $task->save();
        return response()->json($task);
    }
}