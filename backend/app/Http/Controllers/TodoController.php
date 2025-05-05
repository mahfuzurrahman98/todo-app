<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\Todo\StoreTodoRequest;
use App\Http\Requests\Todo\UpdateTodoRequest;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $todos = Todo::where('user_id', $request->user()->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return Response::api('Todos fetched successfully', $todos, 200);
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
            $validated = $request->validated();
            $validated['user_id'] = $request->user()->id;
            
            $todo = Todo::create($validated);
            return Response::api('Todo created successfully', $todo, 201);
        } catch (\Exception $e) {
            return Response::api($e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        try {
            return Response::api('Todo fetched successfully', $todo, 200);
        } catch (\Exception $e) {
            return Response::api($e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTodoRequest $request, Todo $todo)
    {
        try {
            $todo->update($request->validated());
            return Response::api('Todo updated successfully', $todo, 200);
        } catch (\Exception $e) {
            return Response::api($e->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        try {
            $todo->delete();
            return Response::api('Todo deleted successfully', null, 204);
        } catch (\Exception $e) {
            return Response::api($e->getMessage(), null, 500);
        }
    }
}
