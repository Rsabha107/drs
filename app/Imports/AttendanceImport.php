<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\Audience;
use Maatwebsite\Excel\Concerns\ToModel;

class AttendanceImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        $audience = Audience::where('name', $row[7])->first();

        // dd($audience);
        return new Attendance([
            //
            'first_name'            => $row[0],
            'last_name'             => $row[1],
            'email_address'         => $row[2],
            'phone_number'          => $row[3],
            'event_id'              => $row[4],
            'attendance_flag'       => $row[5],
            'reference_number'      => $row[6],
            'type_id'               => $audience?->id,
            //
        ]);
    }
}
