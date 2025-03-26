<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\SensorData;

class SensorController extends Controller
{

    public function index(Request $request)
    {
        $buildingName = $request->input('building_name');
        $maximumLoad = $request->input('maximum_load');
        $currentLoad = $request->input('current_load');
        $deflection = $request->input('deflection');
        $status = $request->input('status');


        $query = SensorData::query();
        if ($buildingName) {
            $query->where('building_name', 'LIKE', "%{$buildingName}%");
        }
        if ($maximumLoad) {
            $query->where('maximum_load', 'LIKE', "%{$maximumLoad}%");
        }
        if ($currentLoad) {
            $query->where('current_load', 'LIKE', "%{$currentLoad}%");
        }
        if ($deflection) {
            $query->where('deflection', 'LIKE', "%{$deflection}%");
        }
        if (!is_null($status)) {
            $query->where('status', $status);
        }

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
                'maximum_load' => 'nullable|string',
                'current_load' => 'nullable|string',
                'deflection' => 'nullable|string',
                'status' => 'nullable|boolean',
                'user_id' => 'nullable|integer',
            ]);

            SensorData::create([
                'building_name' => $request->building_name,
                'maximum_load' => $request->maximum_load,
                'current_load' => $request->current_load,
                'deflection' => $request->deflection,
                'status' => 1,
                'user_id' => $request->user_id,
            ]);

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

            $sensorData = SensorData::findOrFail($id);

            $sensorData->update([
                'building_name' => $request->building_name,
                'maximum_load' => $request->maximum_load,
                'current_load' => $request->current_load,
                'deflection' => $request->deflection,
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

        $request->validate([
            'building_name' => 'nullable|string',
            'maximum_load' => 'nullable|string',
            'current_load' => 'nullable|string',
            'deflection' => 'nullable|string',
            'status' => 'nullable|boolean',
            'user_id' => 'nullable|integer',
        ]);
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
