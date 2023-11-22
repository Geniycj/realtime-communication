<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;
use App\Http\Resources\Core\Notification\FullNotificationResource;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\Message;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        return $user->notifications()->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(User $user,StoreNotificationRequest $request)
    {
        $data = $request->validated()['data'];
        $user->notify(new Message($data));

    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        return (new NotificationResource($notification))
            ->response();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNotificationRequest $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();
    }
}
