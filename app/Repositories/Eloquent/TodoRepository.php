<?php

namespace App\Repositories\Eloquent;

use App\Models\Todo;
use App\Repositories\Interfaces\TodoRepositoryInterface;

class TodoRepository implements TodoRepositoryInterface
{
    protected $model;

    public function __construct(Todo $model)
    {
        $this->model = $model;
    }

    public function getAll($userId = null)
    {
        if ($userId) {
            return $this->model->where('user_id', $userId)->get();
        }
        return $this->model->all();
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $todo = $this->model->find($id);
        if ($todo) {
            $todo->update($data);
            return $todo;
        }
        return null;
    }

    public function delete($id)
    {
        $todo = $this->model->find($id);
        if ($todo) {
            return $todo->delete();
        }
        return false;
    }
}
