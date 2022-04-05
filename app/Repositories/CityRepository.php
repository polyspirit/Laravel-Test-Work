<?php

namespace App\Repositories;

use App\Models\City;
use App\Repositories\Interfaces\MainRepositoryInterface;
use App\Traits\Relatable;

class CityRepository implements MainRepositoryInterface
{
    use Relatable;

    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return City::all()->append('users');
    }

    public function get(int $id): \App\Models\City
    {
        return City::find($id)->append('users');
    }

    public function create(array $attributes): \App\Models\City
    {
        $userIds = $this->cutIdsfromAttributes($attributes, 'user_ids');
        $city = City::create($attributes);
        $this->syncIds($city, $userIds);

        return $city->append('users');
    }

    public function update(int $id, array $attributes): \App\Models\City
    {
        $city = City::find($id);

        $userIds = $this->cutIdsfromAttributes($attributes, 'user_ids');
        $city->update($attributes);
        $this->syncIds($city, $userIds);

        return $city->append('users');
    }

    public function delete(int $id): \App\Models\City
    {
        $city = City::find($id);
        $affected = $city->delete();

        if ($affected) {
            $city->users()->sync([]);
        } else {
            throw new \Exception('Resource was not deleted', 500);
        }

        return $city;
    }
}
