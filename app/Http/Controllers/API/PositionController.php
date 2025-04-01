<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Position;
use Exception;

class PositionController extends Controller
{
    public function all()
    {
        try {

            $positions = Position::where('status', 1)->get();

            return response()->json([
                'status' => 'success',
                'data' => $positions,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $name = $request->input('name');

        $query =  Position::withCount('users')->where('status', 1);

        if ($name) {
            $query->where('name', 'LIKE', "%{$name}%");
        }


        $query->orderBy('id', 'asc');

        $positions = $query->paginate($request->input('per_page', 6));

        return response()->json([
            'status' => 'success',
            'current_page' => $positions->currentPage(),
            'total_pages' => $positions->lastPage(),
            'total_items' => $positions->total(),
            'data' => $positions->items(),
        ], 200);
    }



    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            Position::create([
                'name' => $request->name,
            ]);

            return response()->json([
                'status' => 'created',
                'message' => 'Position created successfully',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $position = Position::findOrFail($id);

            $position->update([
                'name' => $request->name,
            ]);

            return response()->json([
                'status' => 'updated',
                'message' => 'Position updated successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $position = Position::findOrFail($id);


            $position->status = 0;
            $position->save();

            return response()->json([
                'status' => 'updated',
                'message' => 'Position status updated successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
