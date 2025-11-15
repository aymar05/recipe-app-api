<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    public function updateProfile(ProfileUpdateRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $user->update($request->validated());
        return response()->json($user);
    }

    public function updatePassword(PasswordUpdateRequest $request): Response
    {
        /** @var User $user */
        $user = $request->user();
        $validPwd = Auth::guard('web')
            ->attempt(['email' => $user->email, 'password' => $request->input('current_password')]);
        abort_unless($validPwd, 401);
        $user->password = Hash::make($request->input('new_password'));
        $user->save();
        return response()->noContent();
    }

    public function updatePicture(ImageRequest $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        $filePath = storage_path(sprintf("app/%s", $user->picture ));
        File::delete($filePath);
        $user->picture = null;
        if ($request->hasFile('image')) {
            $user->picture = $request->file('image')->storePublicly(User::IMAGE_FOLDER);
        }

        $user->save();
        return response()->noContent();
    }
}
