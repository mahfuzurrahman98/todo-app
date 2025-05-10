<?php

namespace App\Http\Controllers;

use App\Services\TodoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\Todo\StoreTodoRequest;
use App\Http\Requests\Todo\UpdateTodoRequest;
use App\Http\Resources\TodoResource;

class TodoController extends Controller
{
    protected $todoService;

    public function __construct(TodoService $todoService)
    {
        $this->todoService = $todoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $todos = $this->todoService->getAllTodos();

            return Response::api('Todos fetched successfully', [
                'todos' => TodoResource::collection($todos)
            ], 200);
        } catch (\Exception $e) {
            return Response::api($e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTodoRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = $request->user()->id;
            $todo = $this->todoService->createTodo($data);
            return Response::api('Todo created successfully', ['todo' => TodoResource::make($todo)], 201);
        } catch (\Exception $e) {
            return Response::api($e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $todo = $this->todoService->getTodoById($id);

            if (!$todo) {
                return Response::api('Todo not found', null, 404);
            }

            return Response::api('Todo fetched successfully', ['todo' => TodoResource::make($todo)], 200);
        } catch (\Exception $e) {
            return Response::api($e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTodoRequest $request, $id)
    {
        try {
            $todo = $this->todoService->updateTodo($id, $request->validated());

            if (!$todo) {
                return Response::api('Todo not found or unauthorized', null, 404);
            }

            return Response::api('Todo updated successfully', ['todo' => TodoResource::make($todo)], 200);
        } catch (\Exception $e) {
            return Response::api($e->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $deleted = $this->todoService->deleteTodo($id);

            if (!$deleted) {
                return Response::api('Todo not found or unauthorized', null, 404);
            }

            return Response::api('Todo deleted successfully', null, 204);
        } catch (\Exception $e) {
            return Response::api($e->getMessage(), null, 500);
        }
    }
}
