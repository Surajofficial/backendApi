<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function show(Request $request)
    {
        return new UserResource($request->user());
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        $user->fill($data);

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        } else {
            // Password field is empty, do not change the password
            unset($user->password);
        }

        $user->save();

        return new UserResource($user);
    }

}
