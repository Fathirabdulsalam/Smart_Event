<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Models\Categories;
use App\Models\MasterType;
use App\Models\MasterZone;
use App\Models\MasterLocation;
use App\Models\MasterEventKind;
use App\Models\MasterTicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventsController extends Controller
{
    /* =========================
        LIST EVENT (ADMIN)
    ========================== */
    public function index(Request $request)
    {
        $query = Event::with([
            'author',
            'category',
            'type',
            'eventKind',
            'zone',
            'ticketCategory',
            'location',
        ]);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $events = $query->latest()->paginate(10);

        return view('admin.events.index', [
            'events' => $events,
            'authors' => User::whereIn('role', ['author', 'creator'])->get(),
            'categories' => Categories::all(),
            'types' => MasterType::all(),
            'kinds' => MasterEventKind::all(),
            'zones' => MasterZone::all(),
            'locations' => MasterLocation::all(),
            'ticketCategories' => MasterTicketCategory::all(),
        ]);
    }

    /* =========================
        STORE EVENT (ADMIN)
    ========================== */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'poster' => 'required|image|max:2048',

            'date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',

            'price' => 'required|numeric|min:0',

            'author_id' => 'required|exists:users,id',

            'location_type' => 'required|in:online,offline',

            // ONLINE
            'online_link' => 'required_if:location_type,online|nullable|url',

            // OFFLINE
            'master_location_id' => 'required_if:location_type,offline|nullable|exists:master_locations,id',
            'offline_place_name' => 'required_if:location_type,offline|nullable|string|max:255',
            'offline_address' => 'required_if:location_type,offline|nullable|string',
            'offline_maps_link' => 'required_if:location_type,offline|nullable|url',

            // MASTER
            'category_id' => 'required|exists:categories,id',
            'master_type_id' => 'required|exists:master_types,id',
            'master_event_kind_id' => 'required|exists:master_event_kinds,id',
            'master_zone_id' => 'required|exists:master_zones,id',
            'master_ticket_category_id' => 'required|exists:master_ticket_categories,id',

            'details' => 'required|string',
        ]);

        $data = $request->only([
            'name',
            'date',
            'end_date',
            'start_time',
            'end_time',
            'price',
            'location_type',
            'online_link',
            'offline_place_name',
            'offline_address',
            'offline_maps_link',
            'author_id',
            'category_id',
            'master_type_id',
            'master_event_kind_id',
            'master_zone_id',
            'master_ticket_category_id',
            'master_location_id',
            'details',
        ]);

        $data['status'] = 'active';

        // NORMALISASI LOKASI
        if ($request->location_type === 'online') {
            $data['master_location_id'] = null;
            $data['offline_place_name'] = null;
            $data['offline_address'] = null;
            $data['offline_maps_link'] = null;
        } else {
            $data['online_link'] = null;
        }

        // POSTER
        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')
                ->store('events/posters', 'public');
        }

        Event::create($data);

        return redirect()
            ->route('events.index')
            ->with('success', 'Event berhasil dibuat');
    }

    /* =========================
        UPDATE EVENT (ADMIN)
    ========================== */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'poster' => 'nullable|image|max:2048',

            'date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',

            'price' => 'required|numeric|min:0',

            'author_id' => 'required|exists:users,id',

            'location_type' => 'required|in:online,offline',

            // ONLINE
            'online_link' => 'required_if:location_type,online|nullable|url',

            // OFFLINE
            'master_location_id' => 'required_if:location_type,offline|nullable|exists:master_locations,id',
            'offline_place_name' => 'required_if:location_type,offline|nullable|string|max:255',
            'offline_address' => 'required_if:location_type,offline|nullable|string',
            'offline_maps_link' => 'required_if:location_type,offline|nullable|url',

            // MASTER
            'category_id' => 'required|exists:categories,id',
            'master_type_id' => 'required|exists:master_types,id',
            'master_event_kind_id' => 'required|exists:master_event_kinds,id',
            'master_zone_id' => 'required|exists:master_zones,id',
            'master_ticket_category_id' => 'required|exists:master_ticket_categories,id',

            'details' => 'required|string',
        ]);

        $data = $request->only([
            'name',
            'date',
            'end_date',
            'start_time',
            'end_time',
            'price',
            'location_type',
            'online_link',
            'offline_place_name',
            'offline_address',
            'offline_maps_link',
            'author_id',
            'category_id',
            'master_type_id',
            'master_event_kind_id',
            'master_zone_id',
            'master_ticket_category_id',
            'master_location_id',
            'details',
        ]);

        // NORMALISASI LOKASI
        if ($request->location_type === 'online') {
            $data['master_location_id'] = null;
            $data['offline_place_name'] = null;
            $data['offline_address'] = null;
            $data['offline_maps_link'] = null;
        } else {
            $data['online_link'] = null;
        }

        // GANTI POSTER
        if ($request->hasFile('poster')) {
            if ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) {
                Storage::disk('public')->delete($event->poster_path);
            }

            $data['poster_path'] = $request->file('poster')
                ->store('events/posters', 'public');
        }

        $event->update($data);

        return redirect()
            ->route('events.index')
            ->with('success', 'Event berhasil diperbarui');
    }

    /* =========================
        DELETE EVENT (ADMIN)
    ========================== */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) {
            Storage::disk('public')->delete($event->poster_path);
        }

        $event->delete();

        return redirect()
            ->route('events.index')
            ->with('success', 'Event berhasil dihapus');
    }
}
