@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-semibold mb-6">Edit Category</h2>

    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Category Name</label>
            <input type="text" name="name" value="{{ $category->name }}" class="border p-2 rounded w-full" required>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Discount Percentage (%)</label>
            <input type="number" name="discount" value="{{ $category->discount }}" min="0" max="100" class="border p-2 rounded w-full">
            <p class="text-sm text-gray-500 mt-1">Set to 0 to remove discount.</p>
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
            <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded hover:bg-orange-700 font-bold">Update Category</button>
        </div>
    </form>
</div>
@endsection
