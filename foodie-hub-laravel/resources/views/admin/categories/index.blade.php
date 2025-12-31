@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Manage Categories</h2>

    <form action="{{ route('admin.categories.store') }}" method="POST" class="mb-6 flex gap-4">
        @csrf
        <input type="text" name="name" placeholder="Category Name (e.g. Starters)" class="border p-2 rounded w-full" required>
        <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">Add</button>
    </form>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b">
                <th class="py-2">ID</th>
                <th class="py-2">Name</th>
                <th class="py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr class="border-b">
                <td class="py-2">{{ $category->id }}</td>
                <td class="py-2">{{ $category->name }}</td>
                <td class="py-2">
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?');">
                        @csrf 
                        @method('DELETE')
                        <button class="text-red-500 hover:text-red-700">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
