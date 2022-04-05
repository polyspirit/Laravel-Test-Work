<?php

namespace App\Repositories;

use Illuminate\Http\Request;

use App\Models\User;
use App\Repositories\Interfaces\MainRepositoryInterface;
use App\Traits\Relatable;
use App\Traits\Findable;

class UserRepository implements MainRepositoryInterface
{
    use Relatable, Findable;

    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return User::all()->append('cities');
    }

    public function get(int $id): \App\Models\User
    {
        return User::find($id)->append('cities');
    }

    public function find(Request $request): \Illuminate\Database\Eloquent\Collection
    {
        return $this->findAllEntries($request, User::class)->append('cities');
    }

    public function create(array $attributes): \App\Models\User
    {
        $userIds = $this->cutIdsfromAttributes($attributes, 'city_ids');
        $user = User::create($attributes);
        $this->syncIds($user->relateCities(), $userIds);

        return $user->append('cities');
    }

    public function update(int $id, array $attributes): \App\Models\User
    {
        $user = User::find($id);

        $userIds = $this->cutIdsfromAttributes($attributes, 'city_ids');
        $user->update($attributes);
        $this->syncIds($user->relateCities(), $userIds);

        return $user->append('cities');
    }

    public function delete(int $id): \App\Models\User
    {
        $user = User::find($id);
        $affected = $user->delete();

        if ($affected) {
            $user->relateCities()->sync([]);
        } else {
            throw new \Exception('Resource was not deleted', 500);
        }

        return $user;
    }
}
