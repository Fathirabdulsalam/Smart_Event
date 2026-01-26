<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Categories;
use App\Models\MasterType;
use App\Models\MasterZone;
use App\Models\MasterLocation;
use App\Models\MasterEventKind;
use App\Models\MasterTicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserEventController extends Controller
{
    /* =========================
        LIST EVENT USER
    ========================== */
    public function index()
    {
        $events = Event::with([
                'category',
                'type',
                'eventKind',
                'zone',
                'ticketCategory',
                'location',
            ])
            ->where('author_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('users.event.index', [
            'events' => $events,
            'categories' => Categories::all(),
            'types' => MasterType::all(),
            'kinds' => MasterEventKind::all(),
            'zones' => MasterZone::all(),
            'locations' => MasterLocation::all(),
            'ticketCategories' => MasterTicketCategory::all(),
        ]);
    }

    /* =========================
        STORE EVENT
    ========================== */
    public function store(Request $request)
{
    // Validasi dasar
    $rules = [
        'name' => 'required|string|max:255',
        'poster' => 'required|image|max:2048',
        'date' => 'required|date',
        'end_date' => 'nullable|date|after_or_equal:date',
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'master_type_id' => 'required|exists:master_types,id',
        'master_event_kind_id' => 'required|exists:master_event_kinds,id',
        'master_zone_id' => 'required|exists:master_zones,id',
        'master_ticket_category_id' => 'required|exists:master_ticket_categories,id',
        'details' => 'required|string',
    ];

    // Tipe Event (Online / Offline)
    $typeName = MasterType::find($request->master_type_id)?->name;

    if ($typeName === 'Online') {
        $rules['online_link'] = 'required|url';
    }

    if ($typeName === 'Offline') {
        $rules['offline_place_name'] = 'required|string|max:255';
        $rules['offline_address'] = 'required|string';
        $rules['offline_maps_link'] = 'required|url';
    }

    // Validasi Input
    $validated = $request->validate($rules);

    // Menyimpan data
    $data = $validated;
    $data['author_id'] = Auth::id();
    $data['status'] = 'active';

    // Normalisasi data sesuai lokasi
    if ($typeName === 'Online') {
        $data['offline_place_name'] = null;
        $data['offline_address'] = null;
        $data['offline_maps_link'] = null;
    } else {
        $data['online_link'] = null;
    }

    // Upload poster
    if ($request->hasFile('poster')) {
        $data['poster_path'] = $request->file('poster')
            ->store('events/posters', 'public');
    }

    // Simpan Event ke Database
    Event::create($data);

    return redirect()
        ->route('user.events.index')
        ->with('success', 'Event berhasil dibuat');
}

public function update(Request $request, $id)
{
    // Cari event yang akan diupdate
    $event = Event::where('id', $id)
        ->where('author_id', Auth::id())
        ->firstOrFail();

    // Validasi dasar
    $rules = [
        'name' => 'required|string|max:255',
        'poster' => 'nullable|image|max:2048',
        'date' => 'required|date',
        'end_date' => 'nullable|date|after_or_equal:date',
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'master_type_id' => 'required|exists:master_types,id',
        'master_event_kind_id' => 'required|exists:master_event_kinds,id',
        'master_zone_id' => 'required|exists:master_zones,id',
        'master_ticket_category_id' => 'required|exists:master_ticket_categories,id',
        'details' => 'required|string',
    ];

    // Tipe Event
    $typeName = MasterType::find($request->master_type_id)?->name;

    if ($typeName === 'Online') {
        $rules['online_link'] = 'required|url';
    }

    if ($typeName === 'Offline') {
        $rules['offline_place_name'] = 'required|string|max:255';
        $rules['offline_address'] = 'required|string';
        $rules['offline_maps_link'] = 'required|url';
    }

    // Validasi Input
    $validated = $request->validate($rules);

    // Menyimpan data
    $data = $validated;

    // Normalisasi data lokasi
    if ($typeName === 'Online') {
        $data['offline_place_name'] = null;
        $data['offline_address'] = null;
        $data['offline_maps_link'] = null;
    } else {
        $data['online_link'] = null;
    }

    // Jika ada file poster baru
    if ($request->hasFile('poster')) {
        if ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) {
            Storage::disk('public')->delete($event->poster_path);
        }
        $data['poster_path'] = $request->file('poster')
            ->store('events/posters', 'public');
    }

    // Update Event
    $event->update($data);

    return redirect()
        ->route('user.events.index')
        ->with('success', 'Event berhasil diperbarui');
}


    /* =========================
        DELETE EVENT
    ========================== */
    public function destroy($id)
    {
        // Find event by ID and ensure it belongs to the logged-in user
        $event = Event::where('id', $id)
            ->where('author_id', Auth::id())
            ->firstOrFail();

        // Delete the poster if it exists
        if ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) {
            Storage::disk('public')->delete($event->poster_path);
        }

        // Delete the event
        $event->delete();

        // Redirect back with success message
        return redirect()
            ->route('user.events.index')
            ->with('success', 'Event berhasil dihapus');
    }
}
