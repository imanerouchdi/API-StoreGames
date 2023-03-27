<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function updateProfile(UpdateProfileRequest $request, User $user)
    {
        $loggedInUser = Auth::user();

        if (!$loggedInUser->can('edit every profile') && $loggedInUser->id != $user->id){
            return response()->json(['message' => "You can't edit this profile"], 403);
        }

        $request['password'] = Hash::make($request->password);

        $user->update($request->all());

        return response([
            '========' => '================= Update Profile ==================',
            'Message' => 'Your profile updated successfully!',
        ], 200);
    }

    public function deleteProfile(User $user)
    {
        $logedInUser = Auth::user();

        if (!$logedInUser->can('delete every profile') && $logedInUser->id != $user->id){
            return response()->json([

                'Error' => "You can't delete this profile"
            ], 403);
        }

        $user->delete();
        return response()->json([

            'message' => 'Profile deleted successfully!',
        ], 200);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
