<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    // API

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success($this->userRepository->all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->error('Not allowed. User auth/register method instead.', 403);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $user = $this->userRepository->get($id);
        if (isset($user)) {
            return $this->success($user);
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
                'email' => 'email',
                'password' => 'min:6|max:255'
            ]
        )) {
            return $this->error();
        }

        try {
            $updatedUser = $this->userRepository->update($id, $request->all());

            return $this->success($updatedUser);
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
            if (auth()->user()->id === $id) {
                $deletedUser = $this->userRepository->delete($id);

                return $this->success($deletedUser);
            } else {
                return $this->error('Not allowed. User can not delete another users.', 403);
            }
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400);
        }
    }
}
