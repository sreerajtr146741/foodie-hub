<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use App\Models\RestaurantSetting;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = RestaurantTable::orderBy('table_number')->get();
        $settings = [
            'total_tables' => RestaurantSetting::getValue('total_tables', 10),
            'online_tables_count' => RestaurantSetting::getValue('online_tables_count', 5),
            'walkin_tables_count' => RestaurantSetting::getValue('walkin_tables_count', 5),
        ];

        return view('admin.tables.index', compact('tables', 'settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'total_tables' => 'required|integer|min:1',
            'online_tables_count' => 'required|integer|min:0',
            'walkin_tables_count' => 'required|integer|min:0',
        ]);

        RestaurantSetting::setValue('total_tables', $request->total_tables);
        RestaurantSetting::setValue('online_tables_count', $request->online_tables_count);
        RestaurantSetting::setValue('walkin_tables_count', $request->walkin_tables_count);

        return back()->with('success', 'Settings updated successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
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

        return back()->with('success', 'Table status updated successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string|unique:restaurant_tables,table_number',
            'type' => 'required|in:online,walk-in',
            'capacity' => 'required|integer|min:1',
        ]);

        RestaurantTable::create([
            'table_number' => $request->table_number,
            'type' => $request->type,
            'capacity' => $request->capacity,
            'status' => 'available'
        ]);

        return back()->with('success', 'Table added successfully.');
    }

    public function destroy($id)
    {
        $table = RestaurantTable::findOrFail($id);
        $table->delete();
        return back()->with('success', 'Table removed successfully.');
    }
}
