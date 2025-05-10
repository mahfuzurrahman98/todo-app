<?php

namespace App\Repositories\Interfaces;

interface TodoRepositoryInterface
{
    public function getAll($userId = null);
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
