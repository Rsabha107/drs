<?php

namespace App\Imports;

use App\Models\Mds\BookingSlot;
use App\Models\Mds\DeliveryRsp;
use App\Models\Mds\DeliveryVenue;
use App\Models\Mds\MdsEvent;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use \PhpOffice\PhpSpreadsheet\Shared\Date;

class BookingSlotImport implements ToModel, WithCalculatedFormulas
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        appLog('BookingSlotImport::model with row: ' . json_encode($row));

        $event_id = MdsEvent::where('name', $row[0])->first();
        $venue_id = DeliveryVenue::where('title', $row[1])->first();
        $rsp_id = DeliveryRsp::where('title', $row[8])->first();

        appLog($event_id, $venue_id, $rsp_id);

        return new BookingSlot([
            'event_id' => $event_id?->id,
            'event_name' => $row[0],
            'venue_id' => $venue_id?->id,
            'venue_name' => $row[1],
            'booking_date' => Date::excelToDateTimeObject($row[2]),
            'rsp_booking_slot' => $row[3],
            'venue_arrival_time' => $row[4],
            'bookings_slots_all' => ($row[5] ? $row[5] : 0),
            'available_slots' => ($row[5] ? $row[5] : ($row[6] ? $row[6] : 0)),
            'used_slots' => 0,
            'bookings_slots_cat' => ($row[6] ? $row[6] : 0),
            'slot_visibility' => ($row[7] ? Date::excelToDateTimeObject($row[7]) : null),
            'rsp_id' => $rsp_id?->id,
            'remote_search_park' => $row[8],
            'match_day' => $row[9],
            'comments' => $row[10],
        ]);
    }
}
