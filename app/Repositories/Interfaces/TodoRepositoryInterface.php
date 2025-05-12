<?php

namespace App\Repositories\Interfaces;

interface TodoRepositoryInterface
{
    public function getAll($userId = null);
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
