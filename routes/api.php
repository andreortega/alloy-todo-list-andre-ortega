<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// Rotas de API para tarefas (CRUD)
Route::apiResource('tasks', TaskController::class);

// Rota extra para toggle
Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggle']);
