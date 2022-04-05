<?php

namespace App\Repositories;

use App\Models\City;
use App\Repositories\Interfaces\MainRepositoryInterface;

class CityRepository implements MainRepositoryInterface
{
    protected $cityModel;

    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return City::all();
    }

    public function get(int $id): \App\Models\City
    {
        return City::find($id);
    }

    public function create(array $attributes): \App\Models\City
    {
        $userIds = $this->cutUserIdsfromAttributes($attributes);
        $city = $this->cityModel->create($attributes);
        $this->syncUserIds($city, $userIds);

        return $city;
    }

    public function update(int $id, array $attributes): \App\Models\City
    {
        $city = City::find($id);

        $userIds = $this->cutUserIdsfromAttributes($attributes);
        $city->update($attributes);
        $this->syncUserIds($city, $userIds);

        return $city;
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


    // OTHER

    private function cutUserIdsfromAttributes(array &$attributes): array
    {
        $userIds = [];
        if (isset($attributes['user_ids'])) {
            $userIds = json_decode($attributes['user_ids'], true);
            unset($attributes['user_ids']);
        }

        return $userIds;
    }

    private function syncUserIds(City &$city, array $userIds)
    {
        if ($userIds) {
            $city->users()->sync($userIds);
        }
    }
}
