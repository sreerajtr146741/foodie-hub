@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Manage Categories</h2>

    <form action="{{ route('admin.categories.store') }}" method="POST" class="mb-6 flex gap-4">
        @csrf
        <div class="flex-1 flex gap-4">
            <input type="text" name="name" placeholder="Category Name (e.g. Starters)" class="border p-2 rounded w-full" required>
            <input type="number" name="discount" placeholder="Discount % (Optional)" min="0" max="100" class="border p-2 rounded w-48">
        </div>
        <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">Add</button>
    </form>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b">
                <th class="py-2">ID</th>
                <th class="py-2">Name</th>
                <th class="py-2">Discount</th>
                <th class="py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr class="border-b">
                <td class="py-2">{{ $category->id }}</td>
                <td class="py-2">{{ $category->name }}</td>
                <td class="py-2">
                    @if($category->discount > 0)
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">{{ $category->discount }}% OFF</span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="py-2">
                    <div class="flex gap-4">
                        <!-- Edit Icon -->
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-500 hover:text-blue-700" title="Edit Category">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        
                        <!-- Discount Icon -->
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-green-500 hover:text-green-700" title="Manage Discount">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </a>

                        <!-- Delete Icon -->
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?');">
                            @csrf 
                            @method('DELETE')
                            <button class="text-red-500 hover:text-red-700" title="Delete Category">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
