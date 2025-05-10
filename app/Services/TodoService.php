<?php

namespace App\Services;

use App\Repositories\Interfaces\TodoRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class TodoService
{
    protected $todoRepository;

    public function __construct(TodoRepositoryInterface $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function getAllTodos()
    {
        return $this->todoRepository->getAll(Auth::id());
    }

    public function getTodoById($id)
    {
        $todo = $this->todoRepository->findById($id);

        if (!$todo || $todo->user_id !== Auth::id()) {
            return null;
        }

        return $todo;
    }

    public function createTodo(array $data)
    {
        return $this->todoRepository->create($data);
    }

    public function updateTodo($id, array $data)
    {
        $todo = $this->todoRepository->findById($id);

        if (!$todo || $todo->user_id !== Auth::id()) {
            return null;
        }

        return $this->todoRepository->update($id, $data);
    }

    public function deleteTodo($id)
    {
        $todo = $this->todoRepository->findById($id);

        if (!$todo || $todo->user_id !== Auth::id()) {
            return false;
        }

        return $this->todoRepository->delete($id);
    }
}
