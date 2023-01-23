<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ConfirmablePasswordController extends Controller
{
    /**
     * Confirm the user's password.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->getMessageBag(), 422);
        }

        if (!Hash::check($validator->safe()['password'], $request->user()->password)) {
            return response()->json([
                'password' => __('auth.password'),
            ], 422);
        }

        return response()->json(['message' => 'Success']);
    }
}
