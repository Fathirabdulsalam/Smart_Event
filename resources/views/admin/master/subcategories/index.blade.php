@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded relative">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Master Subkategori</h2>
            <button onclick="openModal('createModal')" class="flex items-center gap-2 bg-[#6C5DD3] hover:bg-[#5b4ec2] text-white text-sm font-medium px-5 py-2.5 rounded-lg transition shadow-md">Add Subcategory</button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="py-4 px-6">Parent Category</th>
                        <th class="py-4 px-4">Subcategory Name</th>
                        <th class="py-4 px-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($subcategories as $sub)
                    <tr class="hover:bg-gray-50">
                        <td class="py-4 px-6 font-bold text-[#6C5DD3]">{{ $sub->category->name }}</td>
                        <td class="py-4 px-4 font-bold text-gray-800">{{ $sub->name }}</td>
                        <td class="py-4 px-6 text-right">
                            <button onclick="openEditModal('{{ $sub->id }}', '{{ $sub->name }}', '{{ $sub->category_id }}')" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm mr-3">Edit</button>
                            <button onclick="openDeleteModal('{{ $sub->id }}')" class="text-red-500 hover:text-red-700 font-medium text-sm">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-8 text-gray-500">No subcategories found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4">{{ $subcategories->links() }}</div>
        </div>
    </div>

    <!-- CREATE MODAL -->
    <div id="createModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-bold text-[#6C5DD3] mb-4">Add Subcategory</h3>
                <form action="{{ route('master.subcategories.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Parent Category</label>
                        <select name="category_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-[#6C5DD3] outline-none" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-[#6C5DD3] outline-none" required>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeModal('createModal')" class="px-4 py-2 bg-gray-100 rounded-lg">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-[#6C5DD3] text-white rounded-lg">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div id="editModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-bold text-[#6C5DD3] mb-4">Edit Subcategory</h3>
                <form id="editForm" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Parent Category</label>
                        <select name="category_id" id="edit_category_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-[#6C5DD3] outline-none" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" id="edit_name" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-[#6C5DD3] outline-none" required>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 bg-gray-100 rounded-lg">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-[#6C5DD3] text-white rounded-lg">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openModal(id){ document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id){ document.getElementById(id).classList.add('hidden'); }
        
        function openEditModal(id, name, catId) {
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_category_id').value = catId;
            document.getElementById('editForm').action = '/admin/master/subcategories/' + id;
            document.getElementById('editModal').classList.remove('hidden');
        }
    </script>
    @endpush
    @include('admin.partials.delete_modal_script', ['routePrefix' => 'master.subcategories'])
@endsection