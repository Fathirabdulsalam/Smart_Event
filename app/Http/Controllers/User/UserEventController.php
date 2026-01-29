<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\Categories;
use App\Models\MasterType;
use App\Models\MasterZone;
use App\Models\MasterLocation;
use App\Models\MasterEventKind;
use App\Models\MasterTicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
                'tickets',
            ])
            ->where('author_id', Auth::id())
            ->latest()
            ->paginate(10);

        // Auto-update status berdasarkan sale_end_date
        foreach ($events as $event) {
            if ($event->sale_end_date && Carbon::parse($event->sale_end_date)->isPast()) {
                if ($event->status !== 'ended') {
                    $event->update(['status' => 'ended']);
                }
            }
        }

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
        $rules = [
            'name' => 'required|string|max:255',
            'poster' => 'required|image|max:2048',
            'date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'description' => 'nullable|string',
            'terms' => 'nullable|string',
            'contact_name' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'sale_start_date' => 'nullable|date',
            'sale_end_date' => 'nullable|date|after_or_equal:sale_start_date',
            'sale_start_time' => 'nullable',
            'sale_end_time' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'master_type_id' => 'required|exists:master_types,id',
            'master_event_kind_id' => 'required|exists:master_event_kinds,id',
            'master_zone_id' => 'required|exists:master_zones,id',
            'master_ticket_category_id' => 'required|exists:master_ticket_categories,id',
            'details' => 'required|string',
            'tickets.*.name' => 'required|string|max:255',
            'tickets.*.quantity' => 'required|integer|min:1',
            'tickets.*.price' => 'required|integer|min:0',
            'tickets.*.description' => 'nullable|string',
        ];

        $typeName = MasterType::find($request->master_type_id)?->name;

        if ($typeName === 'Online') {
            $rules['online_link'] = 'required|url';
        }

        if ($typeName === 'Offline') {
            $rules['offline_place_name'] = 'required|string|max:255';
            $rules['offline_address'] = 'required|string';
            $rules['offline_maps_link'] = 'required|url';
        }

        $validated = $request->validate($rules);

        // Normalisasi lokasi
        if ($typeName === 'Online') {
            $validated['offline_place_name'] = null;
            $validated['offline_address'] = null;
            $validated['offline_maps_link'] = null;
        } else {
            $validated['online_link'] = null;
        }

        // Set status awal
        $validated['author_id'] = Auth::id();
        $validated['status'] = 'active';

        // Upload poster
        if ($request->hasFile('poster')) {
            $validated['poster_path'] = $request->file('poster')
                ->store('events/posters', 'public');
        }

        // Simpan event
        $event = Event::create($validated);


        // Simpan tiket
        if ($request->has('tickets')) {
            foreach ($request->tickets as $ticketData) {
                $event->tickets()->create([
                    'name' => $ticketData['name'],
                    'quantity' => $ticketData['quantity'],
                    'price' => $ticketData['price'],
                    'description' => $ticketData['description'] ?? null,
                ]);
            }
        }

        return redirect()
            ->route('user.events.index')
            ->with('success', 'Event berhasil dibuat!');
    }

    /* =========================
        UPDATE EVENT
    ========================== */
    public function update(Request $request, $id)
    {
        $event = Event::where('id', $id)
            ->where('author_id', Auth::id())
            ->firstOrFail();

        $rules = [
            'name' => 'required|string|max:255',
            'poster' => 'nullable|image|max:2048',
            'date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'description' => 'nullable|string',
            'terms' => 'nullable|string',
            'contact_name' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'sale_start_date' => 'nullable|date',
            'sale_end_date' => 'nullable|date|after_or_equal:sale_start_date',
            'sale_start_time' => 'nullable',
            'sale_end_time' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'master_type_id' => 'required|exists:master_types,id',
            'master_event_kind_id' => 'required|exists:master_event_kinds,id',
            'master_zone_id' => 'required|exists:master_zones,id',
            'master_ticket_category_id' => 'required|exists:master_ticket_categories,id',
            'details' => 'required|string',
            'tickets.*.name' => 'required|string|max:255',
            'tickets.*.quantity' => 'required|integer|min:1',
            'tickets.*.price' => 'required|integer|min:0',
            'tickets.*.description' => 'nullable|string',
        ];

        $typeName = MasterType::find($request->master_type_id)?->name;

        if ($typeName === 'Online') {
            $rules['online_link'] = 'required|url';
        }

        if ($typeName === 'Offline') {
            $rules['offline_place_name'] = 'required|string|max:255';
            $rules['offline_address'] = 'required|string';
            $rules['offline_maps_link'] = 'required|url';
        }

        $validated = $request->validate($rules);

        // Normalisasi lokasi
        if ($typeName === 'Online') {
            $validated['offline_place_name'] = null;
            $validated['offline_address'] = null;
            $validated['offline_maps_link'] = null;
        } else {
            $validated['online_link'] = null;
        }

        // Handle poster
        if ($request->hasFile('poster')) {
            if ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) {
                Storage::disk('public')->delete($event->poster_path);
            }
            $validated['poster_path'] = $request->file('poster')->store('events/posters', 'public');
        }

       
        // Update event
        $event->update($validated);
        

        // Hapus & simpan ulang tiket
        $event->tickets()->delete();
        if ($request->has('tickets')) {
            foreach ($request->tickets as $ticketData) {
                $event->tickets()->create([
                    'name' => $ticketData['name'],
                    'quantity' => $ticketData['quantity'],
                    'price' => $ticketData['price'],
                    'description' => $ticketData['description'] ?? null,
                ]);
            }
        }

        return redirect()
            ->route('user.events.index')
            ->with('success', 'Event berhasil diperbarui!');
    }

    /* =========================
        DELETE EVENT
    ========================== */
    public function destroy($id)
    {
        $event = Event::where('id', $id)
            ->where('author_id', Auth::id())
            ->firstOrFail();

        if ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) {
            Storage::disk('public')->delete($event->poster_path);
        }

        $event->delete();

        return redirect()
            ->route('user.events.index')
            ->with('success', 'Event berhasil dihapus!');
    }
}