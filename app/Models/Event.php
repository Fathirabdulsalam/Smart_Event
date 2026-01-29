<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        // BASIC
        'name',
        'date',
        'end_date',
        'start_time',
        'end_time',

        // PRICE
        'price',
        'discount_percentage',

        // LOCATION TYPE
        'location_type',        // online | offline
        'online_link',

        // OFFLINE DETAIL
        'offline_place_name',
        'offline_address',
        'offline_maps_link',

        // CONTENT
        'details',
        'poster_path',
        'status',

        // RELATIONS
        'author_id',
        'category_id',
        'master_type_id',
        'master_event_kind_id',
        'master_zone_id',
        'master_ticket_category_id',
        'master_subcategory_id',
        'master_location_id',

        // 🔹 TAMBAHAN BARU: Deskripsi, Ketentuan, Kontak, Periode Penjualan
        'description',
        'terms',
        'contact_name',
        'contact_email',
        'contact_phone',
        'sale_start_date',
        'sale_end_date',
        'sale_start_time',
        'sale_end_time',
    ];

    protected $casts = [
        'date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'sale_start_date' => 'date',
        'sale_end_date' => 'date',
        'sale_start_time' => 'datetime:H:i',
        'sale_end_time' => 'datetime:H:i',
    ];

    /* =====================
        ACCESSOR
    ===================== */

    public function getDateLabelAttribute()
    {
        if (!$this->end_date || $this->date->format('Y-m-d') === $this->end_date->format('Y-m-d')) {
            return $this->date->format('d M Y');
        }

        if ($this->date->format('M Y') === $this->end_date->format('M Y')) {
            return $this->date->format('d') . ' - ' . $this->end_date->format('d M Y');
        }

        return $this->date->format('d M') . ' - ' . $this->end_date->format('d M Y');
    }

    /* =====================
        RELATIONS
    ===================== */

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function type()
    {
        return $this->belongsTo(MasterType::class, 'master_type_id');
    }

    public function eventKind()
    {
        return $this->belongsTo(MasterEventKind::class, 'master_event_kind_id');
    }

    public function zone()
    {
        return $this->belongsTo(MasterZone::class, 'master_zone_id');
    }

    public function ticketCategory()
    {
        return $this->belongsTo(MasterTicketCategory::class, 'master_ticket_category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(MasterSubCategory::class, 'master_subcategory_id');
    }

    // Legacy (optional)
    public function location()
    {
        return $this->belongsTo(MasterLocation::class, 'master_location_id');
    }

    // 🔹 TAMBAHAN BARU: Relasi ke Tiket
    public function tickets()
    {
        return $this->hasMany(EventTicket::class, 'event_id');
    }
}