<?php

namespace App\Repositories;

use Illuminate\Http\Request;

use App\Models\City;
use App\Repositories\Interfaces\MainRepositoryInterface;
use App\Traits\Relatable;
use App\Traits\Findable;

class CityRepository implements MainRepositoryInterface
{
    use Relatable, Findable;

    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return City::all()->append('users');
    }

    public function get(int $id): \App\Models\City
    {
        return City::find($id)->append('users');
    }

    public function find(Request $request): \Illuminate\Database\Eloquent\Collection
    {
        return $this->findAllEntries($request, City::class);
    }

    public function create(array $attributes): \App\Models\City
    {
        $userIds = $this->cutIdsfromAttributes($attributes, 'user_ids');
        $city = City::create($attributes);
        $this->syncIds($city->relateUsers(), $userIds);

        return $city->append('users');
    }

    public function update(int $id, array $attributes): \App\Models\City
    {
        $city = City::find($id);

        $userIds = $this->cutIdsfromAttributes($attributes, 'user_ids');
        $city->update($attributes);
        $this->syncIds($city->relateUsers(), $userIds);

        return $city->append('users');
    }

    public function delete(int $id): \App\Models\City
    {
        $city = City::find($id);
        $affected = $city->delete();

        if ($affected) {
            $city->relateUsers()->sync([]);
        } else {
            throw new \Exception('Resource was not deleted', 500);
        }

        return $city;
    }
}
