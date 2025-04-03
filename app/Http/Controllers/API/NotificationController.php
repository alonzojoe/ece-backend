<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Exception;

class
NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::with('sensorData')->where(['active' => 1])->orderBy('id', 'desc')->paginate(10);
        return response()->json([
            'status' => 'success',
            'current_page' => $notifications->currentPage(),
            'total_pages' => $notifications->lastPage(),
            'total_items' => $notifications->total(),
            'data' => $notifications->items(),
        ], 200);
    }

    public function all()
    {
        $notifications = Notification::with('sensorData')->where(['active' => 1])->orderBy('id', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $notifications], 200);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'sensor_data_id' => 'nullable|integer',
                'state' => 'nullable|string',
            ]);

            Notification::create([
                'sensor_data_id' => $request->sensor_data_id,
                'state' => $request->notification,
                'status' => 1,
                'active' => 1,
            ]);

            return response()->json(['status' => 'created', 'message' => 'Notification created successfully'], 201);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function update($id)
    {
        try {
            $notification = Notification::findOrFail($id);
            $notification->update(['active' => 0]);

            return response()->json(['status' => 'updated', 'message' => 'Notification marked as seen'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }



    public function seen()
    {
        try {
            Notification::where('status', 1)->update(['status' => 0]);

            return response()->json(['status' => 'updated', 'message' => 'All notifications marked as seen'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
