<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Get the authenticated user's details.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Return the authenticated user's details
        return response()->json($request->user());
    }

    /**
     * Update the authenticated user's profile.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        // Update user information
        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Return success response
        return response()->json([
            'message' => 'User profile updated successfully.',
            'data' => $user,
        ]);
    }

    /**
     * Delete the authenticated user's account.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Delete the user's account
        $user->delete();

        // Return success response
        return response()->json([
            'message' => 'User account deleted successfully.',
        ]);
    }
}
