<?php

namespace App\Services;

use App\Repositories\Interfaces\TodoRepositoryInterface;
use Exception;

class TodoService
{
    protected $todoRepository;

    public function __construct(TodoRepositoryInterface $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }


    public function getAllTodos($userId = null)
    {
        try {
            return $this->todoRepository->getAll($userId);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getTodoById(int $id)
    {
        try {
            return $this->todoRepository->findById($id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function createTodo(array $data)
    {
        try {
            return $this->todoRepository->create($data);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateTodo(int $id, array $data)
    {
        try {
            return $this->todoRepository->update($id, $data);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deleteTodo(int $id)
    {
        try {
            return $this->todoRepository->delete($id);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
