<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use App\Models\RestaurantSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TableController extends Controller
{
    public function index()
    {
        try {
            $tables = RestaurantTable::orderBy('table_number')->get();
            $settings = [
                'total_tables' => RestaurantSetting::getValue('total_tables', 10),
                'online_tables_count' => RestaurantSetting::getValue('online_tables_count', 5),
                'walkin_tables_count' => RestaurantSetting::getValue('walkin_tables_count', 5),
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'tables' => $tables,
                    'settings' => $settings
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('API Admin Tables Index Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to load tables.'], 500);
        }
    }

    public function updateSettings(Request $request)
    {
        try {
            $request->validate([
                'total_tables' => 'required|integer|min:1',
                'online_tables_count' => 'required|integer|min:0',
                'walkin_tables_count' => 'required|integer|min:0',
            ]);

            RestaurantSetting::setValue('total_tables', $request->total_tables);
            RestaurantSetting::setValue('online_tables_count', $request->online_tables_count);
            RestaurantSetting::setValue('walkin_tables_count', $request->walkin_tables_count);

            return response()->json(['success' => true, 'message' => 'Settings updated successfully.']);
        } catch (\Exception $e) {
            Log::error('API Admin Update Table Settings Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to update settings.'], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $table = RestaurantTable::findOrFail($id);
            $request->validate([
                'status' => 'required|in:available,booked,occupied',
                'type' => 'nullable|in:online,walk-in'
            ]);

            $updateData = ['status' => $request->status];
            if ($request->has('type')) {
                $updateData['type'] = $request->type;
            }

            $table->update($updateData);

            return response()->json(['success' => true, 'message' => 'Table status updated successfully.', 'data' => $table]);
        } catch (\Exception $e) {
            Log::error('API Admin Update Table Status Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to update table status.'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'table_number' => 'required|string|unique:restaurant_tables,table_number',
                'type' => 'required|in:online,walk-in',
                'capacity' => 'required|integer|min:1',
            ]);

            $table = RestaurantTable::create([
                'table_number' => $request->table_number,
                'type' => $request->type,
                'capacity' => $request->capacity,
                'status' => 'available'
            ]);

            return response()->json(['success' => true, 'message' => 'Table added successfully.', 'data' => $table]);
        } catch (\Exception $e) {
            Log::error('API Admin Create Table Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to add table.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $table = RestaurantTable::findOrFail($id);
            $table->delete();
            return response()->json(['success' => true, 'message' => 'Table removed successfully.']);
        } catch (\Exception $e) {
            Log::error('API Admin Delete Table Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to remove table.'], 500);
        }
    }
}
