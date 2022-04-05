<?php
namespace App\Repositories\Interfaces;

interface MainRepositoryInterface
{
    public function all();
    public function get(int $id);
    public function create(array $attributes);
    public function update(int $id, array $attributes);
    public function delete(int $id);
}
