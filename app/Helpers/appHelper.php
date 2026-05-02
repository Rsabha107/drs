<?php

// namespace App\Http\Helpers;

use App\Models\Drs\Event;

use App\Models\Drs\Venue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Permission\Models\Role;

/**
 * Get a private image as a base64 data URI
 *
 * @param string $disk
 * @param string $path
 * @return string|null
 */

function getEventNameById($id)
{
    return Event::where('id', $id)->value('name');
}

function getVenueNameById($id)
{
    return Venue::where('id', $id)->value('title');
}

function getFunctionalAreaNameById($id)
{
    return DB::table('functional_areas')->where('id', $id)->value('title');
}

function private_image_base64($disk, $path)
{
    if (!Storage::disk($disk)->exists($path)) {
        return null;
    }

    $mime = Storage::disk($disk)->mimeType($path);
    $data = base64_encode(Storage::disk($disk)->get($path));

    return "data:$mime;base64,$data";
}

if (! function_exists('get_current_event_id')) {
    /**
     * Get the current event ID from session or default
     *
     * @return int|null
     */
    function get_current_event_id()
    {
        // Assuming event ID is stored in session
        return session('EVENT_ID', null);
    }
}

if (! function_exists('nextSequence')) {
    /**
     * Get the next value in a named sequence
     *
     * @param string $key
     * @return int
     * @throws \Exception
     */

    function nextSequence(string $key): int
    {
        return DB::transaction(function () use ($key) {
            $row = DB::table('sequences')
                ->where('key', $key)
                ->lockForUpdate()
                ->first();

            if (!$row) {
                throw new Exception("Sequence '{$key}' not found");
            }

            $next = $row->value + 1;

            DB::table('sequences')
                ->where('key', $key)
                ->update(['value' => $next]);

            return $next;
        });
    }
}

if (! function_exists('getNameById')) {
    /**
     * Get a column value (default: name) from any table by ID
     *
     * @param string $table
     * @param int|string $id
     * @param string $column
     * @return string|null
     */
    function getNameById(string $table, $id, string $column = 'name')
    {
        return DB::table($table)->where('id', $id)->value($column);
    }
}

if (! function_exists('getIdByName')) {
    /**
     * Get a column value (default: name) from any table by ID
     *
     * @param string $table
     * @param int|string $id
     * @param string $column
     * @return string|null
     */
    function getIdByName(string $table, $name, string $column = 'title')
    {
        return DB::table($table)->where($column, $name)->value('id');
    }
}

if (!function_exists('getVenueIdByLabel')) {
    function getVenueIdByLabel(string $label): ?int
    {
        $op_id = Venue::where('short_name', $label)->pluck('id')->first();

        return $op_id ?? null;
    }
}

if (!function_exists('getEventIdByLabel')) {
    function getEventIdByLabel(string $label): ?int
    {
        $op_id = Event::where('name', $label)->pluck('id')->first();

        return $op_id ?? null;
    }
}

if (!function_exists('getEventLabelById')) {
    function getEventLabelById(int $id): ?string
    {
        $label = Event::where('id', $id)->pluck('name')->first();

        return $label ?? null;
    }
}

if (!function_exists('getRoleIdByLabel')) {
    function getRoleIdByLabel(string $label): ?int
    {
        $op_id = Role::where('name', $label)->pluck('id')->first();

        return $op_id ?? null;
    }
}


if (!function_exists('get_label')) {

    function get_label($label, $default, $locale = '')
    {
        if (Lang::has('labels.' . $label, $locale)) {
            return trans('labels.' . $label, [], $locale);
        } else {
            return $default;
        }
    }
}

if (!function_exists('getQrCode')) {

    function getQrCode($id, $size)
    {
        // $qr_code = QrCode::size($size)->generate($id);
        $qr_code = base64_encode(QrCode::format('svg')->size($size)->errorCorrection('H')->generate($id));

        return ($qr_code);
    }
}

if (!function_exists('time_range_segment')) {

    function time_range_segment($time_range, $segment)
    {
        if ($segment == 'from') {
            $return_segment = Str::substr($time_range, 0,  Str::position($time_range, "-") - 1);
            return $return_segment;
        } elseif ($segment == 'to') {
            $return_segment = Str::substr($time_range, Str::position($time_range, "-") + 1);
            return $return_segment;
        } else {
            return null;
        }
    }
}

/**
 * Generate initials from a name
 *
 * @param string $name
 * @return string
 */
if (!function_exists('generate')) {
    function generateInitials(string $name): string
    {
        $words = explode(' ', $name);
        if (count($words) >= 2) {
            return mb_strtoupper(
                mb_substr($words[0], 0, 1, 'UTF-8') .
                    mb_substr(end($words), 0, 1, 'UTF-8'),
                'UTF-8'
            );
        }
        return makeInitialsFromSingleWord($name);
    }
}

/**
 * Make initials from a word with no spaces
 *
 * @param string $name
 * @return string
 */
if (!function_exists('makeInitialsFromSingleWord')) {
    function makeInitialsFromSingleWord(string $name): string
    {
        preg_match_all('#([A-Z]+)#', $name, $capitals);
        if (count($capitals[1]) >= 2) {
            return mb_substr(implode('', $capitals[1]), 0, 2, 'UTF-8');
        }
        return mb_strtoupper(mb_substr($name, 0, 2, 'UTF-8'), 'UTF-8');
    }
}


if (!function_exists('format_date')) {
    function format_date($date, $time = null, $format = null, $apply_timezone = true)
    {
        if ($date) {
            // appLog('date: '.$date);
            // appLog('time: '.$time);
            // appLog('format: '.$format);
            $format = $format ?? get_php_date_format();
            $time = $time ?? '';

            $date = $time != '' ? \Carbon\Carbon::parse($date) : \Carbon\Carbon::parse($date);

            // appLog('date: '.$date);

            // if ($time !== '') {
            //     if ($apply_timezone) {
            //         $date->setTimezone(config('app.timezone'));
            //     }
            //     $format .= ' ' . $time;
            // }

            // appLog($date->format($format));

            return $date->format($format);
        } else {
            return '-';
        }
    }
}

if (!function_exists('get_php_date_format')) {
    function get_php_date_format()
    {
        // $general_settings = get_settings('general_settings');
        $date_format = 'DD-MM-YYYY|d-m-Y';
        // $date_format = $general_settings['date_format'] ?? 'DD-MM-YYYY|d-m-Y';
        $date_format = explode('|', $date_format);
        return $date_format[1];
    }
}



if (!function_exists('generateSecurePassword')) {
    function generateSecurePassword($length = 12)
    {
        $lowercase    = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase    = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers      = '0123456789';
        $specialChars = '!@#$%^&*()-_=+<>?';

        // Ensure at least one of each
        $password = '';
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $specialChars[random_int(0, strlen($specialChars) - 1)];

        // Fill the rest
        $all = $lowercase . $uppercase . $numbers . $specialChars;
        for ($i = strlen($password); $i < $length; $i++) {
            $password .= $all[random_int(0, strlen($all) - 1)];
        }

        // Shuffle to randomize order
        return str_shuffle($password);
    }
}

if (!function_exists('getPublicIp')) {
    function getPublicIp()
    {
        try {
            return Http::timeout(5)
                ->get('https://api64.ipify.org?format=json')
                ->json()['ip'];
        } catch (\Exception $e) {
            return 'Unable to fetch public IP';
        }
    }
}
