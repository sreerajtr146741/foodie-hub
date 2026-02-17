@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Table Management</h2>
            <p class="text-gray-600 mt-1">Configure restaurant tables and allocation between walk-in and online customers</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Settings Card -->
        <div class="bg-white shadow rounded-lg p-6 md:col-span-1">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Allocation Settings</h3>
            <form action="{{ route('admin.tables.updateSettings') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Tables</label>
                        <input type="number" name="total_tables" value="{{ $settings['total_tables'] }}" class="mt-1 block w-full border rounded-md p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Online Tables (50%)</label>
                        <input type="number" name="online_tables_count" value="{{ $settings['online_tables_count'] }}" class="mt-1 block w-full border rounded-md p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Walk-in Tables (50%)</label>
                        <input type="number" name="walkin_tables_count" value="{{ $settings['walkin_tables_count'] }}" class="mt-1 block w-full border rounded-md p-2">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                        Update Settings
                    </button>
                </div>
            </form>
            <p class="text-xs text-gray-500 mt-4">Note: These settings control the maximum simultaneous bookings allowed online.</p>
        </div>

        <!-- Add Table Card -->
        <div class="bg-white shadow rounded-lg p-6 md:col-span-1">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Add New Table</h3>
            <form action="{{ route('admin.tables.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Table Number</label>
                        <input type="text" name="table_number" placeholder="e.g. T-10" required class="mt-1 block w-full border rounded-md p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Allocation Type</label>
                        <select name="type" class="mt-1 block w-full border rounded-md p-2">
                            <option value="online">Online</option>
                            <option value="walk-in">Walk-in</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Capacity (Persons)</label>
                        <input type="number" name="capacity" value="4" class="mt-1 block w-full border rounded-md p-2">
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
                        Add Table
                    </button>
                </div>
            </form>
        </div>

        <!-- Quick Summary Card -->
        <div class="bg-white shadow rounded-lg p-6 md:col-span-1">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Live Summary</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center bg-blue-50 p-3 rounded">
                    <span class="text-blue-800 font-medium">Online Available</span>
                    <span class="text-2xl font-bold text-blue-900">{{ $tables->where('type', 'online')->where('status', 'available')->count() }}</span>
                </div>
                <div class="flex justify-between items-center bg-orange-50 p-3 rounded">
                    <span class="text-orange-800 font-medium">Walk-in Available</span>
                    <span class="text-2xl font-bold text-orange-900">{{ $tables->where('type', 'walk-in')->where('status', 'available')->count() }}</span>
                </div>
                <div class="flex justify-between items-center bg-red-50 p-3 rounded">
                    <span class="text-red-800 font-medium">Currently Occupied</span>
                    <span class="text-2xl font-bold text-red-900">{{ $tables->where('status', 'occupied')->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables List -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Table No.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Capacity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($tables as $table)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">
                            {{ $table->table_number }}
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.tables.updateStatus', $table->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="{{ $table->status }}">
                                <select name="type" onchange="this.form.submit()" class="text-xs rounded px-2 py-1 border {{ $table->type === 'online' ? 'bg-purple-100 text-purple-800 border-purple-300' : 'bg-indigo-100 text-indigo-800 border-indigo-300' }}">
                                    <option value="online" {{ $table->type === 'online' ? 'selected' : '' }}>Online</option>
                                    <option value="walk-in" {{ $table->type === 'walk-in' ? 'selected' : '' }}>Walk-in</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $table->capacity }} Persons
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.tables.updateStatus', $table->id) }}" method="POST">
                                @csrf
                                <select name="status" onchange="this.form.submit()" class="text-sm rounded px-3 py-1 border font-medium
                                    @if($table->status === 'available') bg-green-100 text-green-800 border-green-300
                                    @elseif($table->status === 'booked') bg-yellow-100 text-yellow-800 border-yellow-300
                                    @else bg-red-100 text-red-800 border-red-300 @endif">
                                    <option value="available" @if($table->status === 'available') selected @endif>Available</option>
                                    <option value="booked" @if($table->status === 'booked') selected @endif>Booked</option>
                                    <option value="occupied" @if($table->status === 'occupied') selected @endif>Occupied</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.tables.destroy', $table->id) }}" method="POST" onsubmit="return confirm('Delete this table?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
