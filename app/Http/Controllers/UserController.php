<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\Core\User\FullUserResource;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return (UserResource::collection(User::all()))
            ->response();
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return (new UserResource($user))
            ->response();
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(User $user, UpdateUserRequest $request)
    {
        $user->update($request->validate());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
    }
}
