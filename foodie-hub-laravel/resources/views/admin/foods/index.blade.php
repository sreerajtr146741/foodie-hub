@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Manage Food Items</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Add Food Form -->
    <form action="{{ route('admin.foods.store') }}" method="POST" enctype="multipart/form-data" class="mb-8 p-6 border rounded-lg bg-gradient-to-r from-orange-50 to-yellow-50">
        @csrf
        <h3 class="text-lg font-semibold mb-4 text-gray-700">Add New Food Item</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Food Name *</label>
                <input type="text" name="name" placeholder="e.g., Chicken Biryani" class="w-full border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-orange-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Price (₹) *</label>
                <input type="number" step="0.01" name="price" placeholder="e.g., 250.00" class="w-full border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-orange-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                <select name="category_id" class="w-full border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-orange-500" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Food Image</label>
                <input type="file" name="image" accept="image/*" class="w-full border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-orange-500">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3" placeholder="Describe your delicious food..." class="w-full border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-orange-500"></textarea>
            </div>
        </div>
        <button type="submit" class="mt-4 bg-orange-600 text-white px-6 py-2 rounded-md hover:bg-orange-700 font-semibold">
            Add Food Item
        </button>
    </form>

    <!-- Food List -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b-2 border-gray-200 bg-gray-100">
                    <th class="p-3 font-semibold">Image</th>
                    <th class="p-3 font-semibold">Name</th>
                    <th class="p-3 font-semibold">Category</th>
                    <th class="p-3 font-semibold">Price</th>
                    <th class="p-3 font-semibold">Status</th>
                    <th class="p-3 font-semibold text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($foods as $food)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="p-3">
                        @if($food->image)
                            <img src="{{ asset('storage/' . $food->image) }}" alt="{{ $food->name }}" class="w-16 h-16 object-cover rounded-lg shadow">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-gray-400 text-xs">No Image</span>
                            </div>
                        @endif
                    </td>
                    <td class="p-3">
                        <div class="font-medium text-gray-900">{{ $food->name }}</div>
                        @if($food->description)
                            <div class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $food->description }}</div>
                        @endif
                    </td>
                    <td class="p-3 text-gray-700">{{ $food->category->name }}</td>
                    <td class="p-3 font-semibold text-green-600">₹{{ number_format($food->price, 2) }}</td>
                    <td class="p-3">
                        <span class="px-2 py-1 text-xs rounded-full {{ $food->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $food->is_available ? '✓ In Stock' : '✗ Out of Stock' }}
                        </span>
                    </td>
                    <td class="p-3">
                        <div class="flex gap-2 justify-center">
                            <!-- Edit Button -->
                            <button onclick="openEditModal({{ $food }})" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">
                                ✏️ Edit
                            </button>
                            
                            <!-- Toggle Stock -->
                            <form action="{{ route('admin.foods.update', $food) }}" method="POST" class="inline">
                                @csrf @method('PUT')
                                <input type="hidden" name="name" value="{{ $food->name }}">
                                <input type="hidden" name="price" value="{{ $food->price }}">
                                <input type="hidden" name="category_id" value="{{ $food->category_id }}">
                                <input type="hidden" name="description" value="{{ $food->description }}">
                                <input type="hidden" name="is_available" value="{{ $food->is_available ? 0 : 1 }}">
                                <button class="bg-yellow-600 text-white px-3 py-1 rounded hover:bg-yellow-700 text-sm">
                                    {{ $food->is_available ? '📦 Out' : '✅ In' }}
                                </button>
                            </form>
                            
                            <!-- Delete -->
                            <form action="{{ route('admin.foods.destroy', $food) }}" method="POST" onsubmit="return confirm('Delete {{ $food->name }}?');" class="inline">
                                @csrf @method('DELETE')
                                <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-sm">
                                    🗑️ Del
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl mx-4 max-h-screen overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Edit Food Item</h3>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Food Name *</label>
                    <input type="text" name="name" id="edit_name" class="w-full border border-gray-300 p-2 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price (₹) *</label>
                    <input type="number" step="0.01" name="price" id="edit_price" class="w-full border border-gray-300 p-2 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                    <select name="category_id" id="edit_category" class="w-full border border-gray-300 p-2 rounded-md" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Change Image</label>
                    <input type="file" name="image" accept="image/*" class="w-full border border-gray-300 p-2 rounded-md">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="edit_description" rows="3" class="w-full border border-gray-300 p-2 rounded-md"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_available" id="edit_available" value="1" class="mr-2">
                        <span class="text-sm text-gray-700">Available in Stock</span>
                    </label>
                </div>
            </div>
            
            <div class="flex gap-3 mt-6">
                <button type="submit" class="flex-1 bg-orange-600 text-white px-6 py-2 rounded-md hover:bg-orange-700 font-semibold">
                    Update Food Item
                </button>
                <button type="button" onclick="closeEditModal()" class="px-6 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(food) {
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
    document.getElementById('editForm').action = `/admin/foods/${food.id}`;
    document.getElementById('edit_name').value = food.name;
    document.getElementById('edit_price').value = food.price;
    document.getElementById('edit_category').value = food.category_id;
    document.getElementById('edit_description').value = food.description || '';
    document.getElementById('edit_available').checked = food.is_available == 1;
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
}
</script>
@endsection
