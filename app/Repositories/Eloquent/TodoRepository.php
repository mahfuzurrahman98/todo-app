<?php

namespace App\Repositories\Eloquent;

use App\Models\Todo;
use App\Repositories\Interfaces\TodoRepositoryInterface;
use Exception;

class TodoRepository implements TodoRepositoryInterface
{
    protected $model;

    public function __construct(Todo $model)
    {
        $this->model = $model;
    }

    public function getAll($userId = null)
    {
        try {
            if ($userId) {
                return $this->model->where('user_id', $userId)->get();
            }
            return $this->model->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function findById(int $id)
    {
        try {
            return $this->model->find($id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function create(array $data)
    {
        try {
            return $this->model->create($data);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(int $id, array $data)
    {
        try {
            $todo = $this->model->find($id);
            if (!$todo) {
                throw new Exception('Todo not found');
            }
            $todo->update($data);
            return $todo->fresh();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            $todo = $this->model->find($id);
            if (!$todo) {
                throw new Exception('Todo not found');
            }
            return $todo->delete();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
