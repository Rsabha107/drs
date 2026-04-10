<?php

namespace App\Exports;

use App\Models\Mds\BookingSlot;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookingSlotExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings(): array
    {
        return [
            '#',
            'EVENT',
            'VENUE',
            'BOOKING DATE',
            'RSP BOOKING SLOT',
            'VENUE ARRIVAL TIME',
            'BOOKING SLOTS ALL',
            'BOOKING SLOTS CAT',
            'AVAILABLE SLOTS',
            'USED SLOTS',
            'SLOT VISIBILITY',
            'RSP',
            'MATCH DAY',
            'COMMENTS',
            'CREATED AT',
        ];
    }
    public function collection()
    {
        $bookingSlots = BookingSlot::all();
        $bookingSlots->transform(function ($bookingSlot) {
            return [
                'id' => $bookingSlot->id,
                'event' => $bookingSlot->event->name,
                'venue' => $bookingSlot->venue->title,
                'booking_date' => $bookingSlot->booking_date,
                'rsp_booking_slot' => $bookingSlot->rsp_booking_slot,
                'venue_arrival_time' => $bookingSlot->venue_arrival_time,
                'bookings_slots_all' => $bookingSlot->bookings_slots_all,
                'bookings_slots_cat' => $bookingSlot->bookings_slots_cat,
                'available_slots' => $bookingSlot->available_slots,
                'used_slots' => $bookingSlot->used_slots,
                'slot_visibility' => $bookingSlot->slot_visibility,
                'rsp' => $bookingSlot->rsp?->title,
                'match_day' => $bookingSlot->match_day,
                'comments' => $bookingSlot->comments,
                'created_at' => $bookingSlot->created_at,
            ];
        });
        return $bookingSlots;
    }
}
