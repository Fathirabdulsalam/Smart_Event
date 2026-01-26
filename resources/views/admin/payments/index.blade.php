@extends('layouts.admin')

@section('content')
    <div class="space-y-6">

        <!-- SECTION 1: Top Toolbar -->
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Left: View Mode & Search -->
            <div class="flex items-center gap-4 flex-1">
                <!-- View Icons -->
                <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-lg">
                    <button class="p-1.5 rounded-md hover:bg-white hover:shadow-sm text-gray-500 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </button>
                    <button class="p-1.5 rounded-md bg-white shadow-sm text-gray-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>

                <!-- Search Bar -->
                <div class="relative flex-1 max-w-xs">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" placeholder="Search Payment" class="w-full bg-gray-100 border-none text-gray-700 text-sm rounded-lg py-2.5 pl-10 pr-4 focus:ring-2 focus:ring-[#6C5DD3] focus:bg-white transition placeholder-gray-400">
                </div>
            </div>

            <!-- Right: Sort & Filter Only (No Add Button) -->
            <div class="flex flex-wrap items-center gap-3">
                
                <div class="flex items-center gap-2 text-sm text-gray-600 bg-gray-100 px-3 py-2.5 rounded-lg">
                    <span class="text-gray-400">Show:</span>
                    <span class="font-medium">All Payment</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>

                <div class="flex items-center gap-2 text-sm text-gray-600 bg-gray-100 px-3 py-2.5 rounded-lg">
                    <span class="text-gray-400">Sort by:</span>
                    <span class="font-medium">Default</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>

                <button class="flex items-center gap-2 text-sm font-medium text-gray-600 bg-gray-100 px-4 py-2.5 rounded-lg hover:bg-gray-200 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter
                </button>
            </div>
        </div>

        <!-- SECTION 2: Filter Dropdowns -->
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Category -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Category</label>
                    <div class="relative">
                        <select class="w-full bg-gray-100 border-none text-gray-700 text-sm rounded-lg py-2.5 px-4 pr-8 appearance-none focus:ring-2 focus:ring-[#6C5DD3]">
                            <option>Technologies</option>
                            <option>Design</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Method Payment -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Method Payment</label>
                    <div class="relative">
                        <select class="w-full bg-gray-100 border-none text-gray-700 text-sm rounded-lg py-2.5 px-4 pr-8 appearance-none focus:ring-2 focus:ring-[#6C5DD3]">
                            <option>All Method</option>
                            <option>QRIS</option>
                            <option>Bank Transfer</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Date -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Date</label>
                    <div class="relative">
                        <select class="w-full bg-gray-100 border-none text-gray-700 text-sm rounded-lg py-2.5 px-4 pr-8 appearance-none focus:ring-2 focus:ring-[#6C5DD3]">
                            <option>Latest</option>
                            <option>Oldest</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: Data Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-xs border-b border-gray-100">
                            <th class="py-4 pl-6 pr-4 w-10">
                                <input type="checkbox" class="rounded border-gray-300 text-[#6C5DD3] focus:ring-[#6C5DD3] h-4 w-4">
                            </th>
                            <th class="py-4 px-4 font-normal">Payment</th>
                            <th class="py-4 px-4 font-normal">
                                <div class="flex items-center gap-1 cursor-pointer">Date <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg></div>
                            </th>
                            <th class="py-4 px-4 font-normal">
                                <div class="flex items-center gap-1 cursor-pointer">Price <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg></div>
                            </th>
                            <th class="py-4 px-4 font-normal">
                                <div class="flex items-center gap-1 cursor-pointer">Category <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg></div>
                            </th>
                            <th class="py-4 px-4 font-normal">
                                <div class="flex items-center gap-1 cursor-pointer">Method <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg></div>
                            </th>
                            <th class="py-4 px-6 text-right font-normal">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <!-- Loop Data (Example 6 items) -->
                        @for($i = 0; $i < 6; $i++)
                        <tr class="hover:bg-gray-50 group transition-colors">
                            <td class="py-4 pl-6 pr-4">
                                <input type="checkbox" class="rounded border-gray-300 text-[#6C5DD3] focus:ring-[#6C5DD3] h-4 w-4">
                            </td>
                            <!-- Column 1: Payment/User Info -->
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-lg bg-red-900 overflow-hidden flex-shrink-0">
                                        <img src="https://via.placeholder.com/150/500000/FFFFFF?text=IMG" class="h-full w-full object-cover opacity-70">
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-700 text-sm">Belajar Coding - Batch 1</div>
                                        <div class="text-xs text-gray-400 mt-0.5">Fatir</div>
                                    </div>
                                </div>
                            </td>
                            <!-- Column 2: Date -->
                            <td class="py-4 px-4 text-sm font-semibold text-gray-700">28-09-2025</td>
                            <!-- Column 3: Price -->
                            <td class="py-4 px-4 text-sm font-medium text-gray-800">129K</td>
                            <!-- Column 4: Category -->
                            <td class="py-4 px-4 text-sm font-bold text-gray-800">Technology</td>
                             <!-- Column 5: Method -->
                            <td class="py-4 px-4 text-sm font-bold text-gray-800">QRIS</td>
                            <!-- Column 6: Status -->
                            <td class="py-4 px-6 text-right">
                                <!-- Status Successful Badge (Full Green Block) -->
                                <span class="inline-block bg-[#439F23] text-white text-xs font-bold px-6 py-1.5 rounded-full shadow-sm">
                                    Successful
                                </span>
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection