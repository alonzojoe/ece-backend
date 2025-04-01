<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function index(Request $request)
    {
        try {

            $name = $request->input('name');
            $email = $request->input('email');
            $gender = $request->input('gender');
            $phone = $request->input('phone');
            $position_id = $request->input('position_id');


            $query = User::with('position');


            if ($name) {
                $query->where('name', 'LIKE', "%{$name}%");
            }
            if ($email) {
                $query->where('email', 'LIKE', "%{$email}%");
            }
            if ($email) {
                $query->where('phone', 'LIKE', "%{$phone}%");
            }
            if ($gender) {
                $query->where('gender', $gender);
            }
            if ($position_id) {
                $query->where('position_id', $position_id);
            }


            $query->orderBy('id', 'desc');


            $users = $query->paginate(10);

            return response()->json([
                'status' => 'success',
                'current_page' => $users->currentPage(),
                'total_pages' => $users->lastPage(),
                'total_items' => $users->total(),
                'data' => $users->items(),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'phone' => 'nullable|string|max:11',
                'gender' => 'nullable|string|max:11',
                'position_id' => 'nullable|integer',
                'password' => 'required|string|min:6',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'position_id' => $request->position_id,
                'password' => Hash::make($request->password)
            ]);

            $token = Auth::login($user);

            return response()->json([
                'status' => 'success',
                'message' => 'User Created Successfully!',
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'Bearer'
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:11',
                'gender' => 'nullable|string|max:11',
                'position_id' => 'nullable|integer',
            ]);


            $user = User::findOrFail($id);


            $user->update($request->only(['name', 'phone', 'gender', 'position_id']));

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully!',
                'user' => $user,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string'
            ]);

            $credentials = $request->only('email', 'password');

            if (!$token = Auth::attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $user = Auth::user();

            return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'Bearer'
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status',
            'success',
            'message',
            'User logged out.'
        ], 200);
    }

    public function me()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user()
        ], 200);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'refresh token generated',
            'user' => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'Bearer'
            ]
        ], 200);
    }

    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email|exists:users,email',
                'oldpassword' => 'required|string',
                'newpassword' => 'required|string|min:6',
            ]);


            $user = User::where('email', $request->email)->first();


            if (!Hash::check($request->oldpassword, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Old password is incorrect'
                ], 400);
            }


            $user->password = Hash::make($request->newpassword);
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Password changed successfully'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
