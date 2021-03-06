<?php

namespace App\Http\Controllers;

use App\Repositories\CityRepository;
use Illuminate\Http\Request;

class CityController extends Controller
{
    private $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }


    // API

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success($this->cityRepository->all());
    }

    /**
     * Display a filtering and sorting listing of the resource.
     */
    public function find(Request $request)
    {
        return $this->success($this->cityRepository->find($request));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$this->validateRequest(
            $request,
            [
                'name' => 'required|min:2|max:255',
                'founded' => 'required|date_format:Y-m-d',
                'population' => 'required|integer|max:9999999'
            ]
        )) {
            return $this->error();
        }


        $city = $this->cityRepository->create($request->all());

        return $this->success(['id' => $city->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $city = $this->cityRepository->get($id);
        if (isset($city)) {
            return $this->success($city);
        }

        return $this->error('Resource not found', 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        if (!$this->validateRequest(
            $request,
            [
                'name' => 'min:2|max:255',
                'founded' => 'date_format:Y-m-d',
                'population' => 'integer|max:9999999'
            ]
        )) {
            return $this->error();
        }

        try {
            $updatedCity = $this->cityRepository->update($id, $request->all());

            return $this->success($updatedCity);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $deletedCity = $this->cityRepository->delete($id);

            return $this->success($deletedCity);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400);
        }
    }
}
