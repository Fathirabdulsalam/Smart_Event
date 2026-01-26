@extends('layouts.admin')

@section('content')
    <div class="space-y-6">

        <!-- Notifikasi -->
        @if(session('success') && !str_contains(session('success'), 'dihapus'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <strong class="font-bold">Berhasil!</strong> <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <strong class="font-bold">Error!</strong>
                <ul class="list-disc pl-5 text-xs mt-1">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <!-- Toolbar -->
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form action="{{ route('discounts.index') }}" method="GET" class="flex items-center gap-4 flex-1">
                <div class="relative flex-1 max-w-xs">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Discount" class="w-full bg-gray-100 border-none text-gray-700 text-sm rounded-lg py-2.5 pl-10 pr-4 focus:ring-2 focus:ring-[#6C5DD3] transition">
                </div>
            </form>

            <div class="flex flex-wrap items-center gap-3">
                <button class="flex items-center gap-2 text-sm font-medium text-gray-600 bg-gray-100 px-4 py-2.5 rounded-lg hover:bg-gray-200 transition">Filter</button>
                <button onclick="openCreateModal()" class="flex items-center gap-2 bg-[#6C5DD3] hover:bg-[#5b4ec2] text-white text-sm font-medium px-5 py-2.5 rounded-lg transition shadow-md shadow-indigo-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Discount
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-xs border-b border-gray-100">
                            <th class="py-4 pl-6 pr-4 w-10">#</th>
                            <th class="py-4 px-4 font-normal">Discount Name</th>
                            <th class="py-4 px-4 font-normal">Category</th>
                            <th class="py-4 px-4 font-normal">Discount</th>
                            <th class="py-4 px-4 font-normal">Due Date</th>
                            <th class="py-4 px-4 font-normal">Status</th>
                            <th class="py-4 px-6 text-right font-normal">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($discounts as $discount)
                        <tr class="hover:bg-gray-50 group transition-colors">
                            <td class="py-4 pl-6 pr-4 text-sm text-gray-500">
                                {{ $loop->iteration + ($discounts->currentPage() - 1) * $discounts->perPage() }}
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-lg bg-red-100 flex items-center justify-center text-red-500 font-bold overflow-hidden flex-shrink-0">
                                        %
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-700 text-sm">{{ $discount->name }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5 uppercase tracking-wide">SMART EVENT .ID</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-sm font-semibold text-gray-700">{{ $discount->category->name ?? '-' }}</td>
                            <td class="py-4 px-4 text-sm font-bold text-gray-800">{{ $discount->percentage }}%</td>
                            <td class="py-4 px-4 text-sm font-semibold text-gray-800">{{ $discount->end_date->format('d-m-Y') }}</td>
                            <td class="py-4 px-4">
                                <span class="flex items-center gap-2 px-3 py-1.5 rounded-lg border border-gray-200 bg-white text-xs font-medium text-gray-600 w-fit">
                                    <span class="w-2 h-2 rounded-full {{ $discount->status == 'active' ? 'bg-green-500' : ($discount->status == 'pending' ? 'bg-yellow-500' : 'bg-red-500') }}"></span>
                                    {{ ucfirst($discount->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <!-- Edit -->
                                    <button onclick="openEditModal(
                                        '{{ $discount->id }}',
                                        '{{ addslashes($discount->name) }}',
                                        '{{ addslashes($discount->description) }}',
                                        '{{ $discount->category_id }}',
                                        '{{ $discount->percentage }}',
                                        '{{ $discount->start_date->format('Y-m-d') }}',
                                        '{{ $discount->end_date->format('Y-m-d') }}',
                                        '{{ $discount->status }}'
                                    )" class="flex items-center gap-1 px-4 py-1.5 bg-[#6C5DD3] hover:bg-[#5b4ec2] text-white text-xs font-medium rounded-md transition shadow-sm">
                                        Edit
                                    </button>
                                    <!-- Delete -->
                                    <button onclick="openDeleteModal('{{ $discount->id }}')" class="text-gray-400 hover:text-red-500 transition p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-8 text-gray-500">No discounts found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $discounts->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- 1. CREATE MODAL -->
    <div id="createModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform rounded-xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-lg border border-gray-200">
                    <form action="{{ route('discounts.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-6 py-6 space-y-6">
                            <h3 class="text-xl font-bold text-[#6C5DD3] border-b pb-4">Add Discount</h3>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Discount Name</label>
                                <input type="text" name="name" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] outline-none" required>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Category</label>
                                    <select name="category_id" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] outline-none" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Percentage (%)</label>
                                    <input type="number" name="percentage" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] outline-none" min="1" max="100" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Start Date</label>
                                    <input type="date" name="start_date" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] text-gray-600" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Due Date</label>
                                    <input type="date" name="end_date" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] text-gray-600" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Status</label>
                                <select name="status" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] outline-none">
                                    <option value="active">Active</option>
                                    <option value="pending">Pending</option>
                                    <option value="expired">Expired</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Description</label>
                                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] resize-none outline-none"></textarea>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button" onclick="closeModal('createModal')" class="bg-gray-100 text-gray-600 font-medium py-2 px-6 rounded-md hover:bg-gray-200 transition">Cancel</button>
                                <button type="submit" class="bg-[#6C5DD3] text-white font-medium py-2 px-8 rounded-md hover:bg-[#5b4ec2] transition shadow-md shadow-indigo-200">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. EDIT MODAL -->
    <div id="editModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform rounded-xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-lg border border-gray-200">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-6 py-6 space-y-6">
                            <h3 class="text-xl font-bold text-[#6C5DD3] border-b pb-4">Edit Discount</h3>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Discount Name</label>
                                <input type="text" name="name" id="edit_name" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] outline-none" required>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Category</label>
                                    <select name="category_id" id="edit_category_id" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] outline-none" required>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Percentage (%)</label>
                                    <input type="number" name="percentage" id="edit_percentage" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] outline-none" min="1" max="100" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Start Date</label>
                                    <input type="date" name="start_date" id="edit_start_date" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] text-gray-600" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Due Date</label>
                                    <input type="date" name="end_date" id="edit_end_date" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] text-gray-600" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Status</label>
                                <select name="status" id="edit_status" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] outline-none">
                                    <option value="active">Active</option>
                                    <option value="pending">Pending</option>
                                    <option value="expired">Expired</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Description</label>
                                <textarea name="description" id="edit_description" rows="3" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6C5DD3] resize-none outline-none"></textarea>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button" onclick="closeModal('editModal')" class="bg-gray-100 text-gray-600 font-medium py-2 px-6 rounded-md hover:bg-gray-200 transition">Cancel</button>
                                <button type="submit" class="bg-[#6C5DD3] text-white font-medium py-2 px-8 rounded-md hover:bg-[#5b4ec2] transition shadow-md shadow-indigo-200">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. DELETE MODAL -->
    <div id="deleteModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md p-6">
                    <div class="text-center mt-2">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-[#FFCE50] mb-5">
                            <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#6C5DD3] leading-snug px-4">Delete This Discount?</h3>
                        <p class="text-sm text-[#6C5DD3] opacity-80 mt-2 mb-6">This action cannot be undone.</p>
                        <div class="flex items-center justify-center gap-4 mt-6">
                            <button onclick="closeModal('deleteModal')" class="w-32 inline-flex justify-center rounded-lg border border-transparent px-4 py-2 bg-[#6C5DD3] text-base font-medium text-white hover:bg-[#5b4ec2]">Cancel</button>
                            <form id="deleteForm" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-32 inline-flex justify-center rounded-lg border border-transparent px-4 py-2 bg-[#6C5DD3] text-base font-medium text-white hover:bg-[#5b4ec2]">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. SUCCESS MODAL -->
    <div id="successModal" class="relative z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 backdrop-brightness-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-sm p-8">
                    <div class="text-center">
                        <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-[#00C851] mb-6 shadow-md">
                             <div class="h-20 w-20 rounded-full border-2 border-white flex items-center justify-center">
                                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                             </div>
                        </div>
                        <h3 class="text-2xl font-bold text-[#6C5DD3] mb-2">Success!</h3>
                    </div>
                    <div class="mt-8">
                        <button onclick="closeModal('successModal')" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2.5 bg-[#6C5DD3] text-base font-medium text-white hover:bg-[#5b4ec2]">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openCreateModal() { document.getElementById('createModal').classList.remove('hidden'); }
        function closeModal(modalId) { document.getElementById(modalId).classList.add('hidden'); }

        function openEditModal(id, name, desc, catId, percent, start, end, status) {
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = desc;
            document.getElementById('edit_category_id').value = catId;
            document.getElementById('edit_percentage').value = percent;
            document.getElementById('edit_start_date').value = start;
            document.getElementById('edit_end_date').value = end;
            document.getElementById('edit_status').value = status;
            
            document.getElementById('editForm').action = '/admin/dashboard/discounts/' + id;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = '/admin/dashboard/discounts/' + id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                document.querySelectorAll('[role="dialog"]').forEach(modal => modal.classList.add('hidden'));
            }
        });

        @if($errors->any() && !request()->isMethod('put'))
            openCreateModal();
        @endif

        @if(session('success') && str_contains(session('success'), 'deleted'))
            document.getElementById('successModal').classList.remove('hidden');
        @endif
    </script>
    @endpush
@endsection