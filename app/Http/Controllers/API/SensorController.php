<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\SensorData;
use App\Events\SensorStored;
use App\Models\Notification;
use App\Http\Controllers\API\NotificationController;

class SensorController extends Controller
{
    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        $this->notificationController = $notificationController;
    }


    public function index(Request $request)
    {
        $buildingName = $request->input('building_name');
        $load = $request->input('load');
        $deflection = $request->input('deflection');
        $angle_of_deflection = $request->input('angle_of_deflection');
        $status = $request->input('status');

        $query = SensorData::with('notification');

        if ($buildingName) {
            $query->where('building_name', 'LIKE', "%{$buildingName}%");
        }
        if ($load) {
            $query->where('load', 'LIKE', "%{$load}%");
        }
        if ($deflection) {
            $query->where('deflection', 'LIKE', "%{$deflection}%");
        }
        if ($angle_of_deflection) {
            $query->where('angle_of_deflection', 'LIKE', "%{$angle_of_deflection}%");
        }
        if (!is_null($status)) {
            $query->where('status', $status);
        }

        $query->orderBy('id', 'desc');

        $sensorData = $query->paginate($request->input('per_page', 10));

        return response()->json([
            'status' => 'success',
            'current_page' => $sensorData->currentPage(),
            'total_pages' => $sensorData->lastPage(),
            'total_items' => $sensorData->total(),
            'data' => $sensorData->items(),
        ], 200);
    }


    public function store(Request $request)
    {

        try {

            $request->validate([
                'building_name' => 'nullable|string',
                'load' => ['nullable', 'regex:/^([-+]?[0-9]*\.?[0-9]+|[a-zA-Z\s]+)$/'],
                'deflection' => ['nullable', 'regex:/^([-+]?[0-9]*\.?[0-9]+|[a-zA-Z\s]+)$/'],
                'angle_of_deflection' => ['nullable', 'regex:/^([-+]?[0-9]*\.?[0-9]+|[a-zA-Z\s]+)$/'],
                'status' => 'nullable|boolean',
                'user_id' => 'nullable|integer',
                'notification' => 'nullable|string'
            ]);



            $sensorData = SensorData::create([
                'building_name' => $request->building_name,
                'load' => $request->load,
                'deflection' => $request->deflection,
                'angle_of_deflection' => $request->angle_of_deflection,
                'status' => 1,
                'user_id' => $request->user_id,
            ]);

            if ($request->has('notification') && !empty($request->notification)) {
                Notification::create([
                    'sensor_data_id' => $sensorData->id,
                    'state' => $request->notification,
                ]);
            }

            // broadcast(new SensorStored($sensorData));

            return response()->json(['status' => 'created', 'message' => 'Sensor data saved successfully'], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {

        try {

            $request->validate([
                'building_name' => 'nullable|string',
                'load' => ['nullable', 'regex:/^([-+]?[0-9]*\.?[0-9]+|[a-zA-Z\s]+)$/'],
                'deflection' => ['nullable', 'regex:/^([-+]?[0-9]*\.?[0-9]+|[a-zA-Z\s]+)$/'],
                'angle_of_deflection' => ['nullable', 'regex:/^([-+]?[0-9]*\.?[0-9]+|[a-zA-Z\s]+)$/'],
                'status' => 'nullable|boolean',
                'user_id' => 'nullable|integer',
            ]);

            $sensorData = SensorData::findOrFail($id);

            $sensorData->update([
                'building_name' => $request->building_name,
                'load' => $request->load,
                'deflection' => $request->deflection,
                'angle_of_deflection' => $request->angle_of_deflection,
                'status' => $request->status,
                'user_id' => $request->user_id,
            ]);


            return response()->json(['status' => 'updated', 'message' => 'Sensor data updated successfully'], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function inactive($id)
    {

        try {

            $sensorData = SensorData::findOrFail($id);
            $sensorData->update([
                'status' => 0
            ]);

            return response()->json(['status' => 'updated', 'message' => 'Sensor data has been inactive'], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }

        $request->validate([
            'building_name' => 'nullable|string',
            'maximum_load' => 'nullable|string',
            'current_load' => 'nullable|string',
            'deflection' => 'nullable|string',
            'status' => 'nullable|boolean',
            'user_id' => 'nullable|integer',
        ]);
    }
}
