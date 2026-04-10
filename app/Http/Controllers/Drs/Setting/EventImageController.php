<?php

namespace App\Http\Controllers\Drs\Setting;

use App\Http\Controllers\Controller;
use App\Models\Drs\EventDocument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

// use Illuminate\Support\Facades\Redirect;

class EventImageController extends Controller
{
    //
    public function getPrivateFile($id)
    {
        // Log::info('EventImageController::getPrivateFile called with id: ' . $id);

        $doc = EventDocument::where('event_id', $id)
            ->first();

        $path = $doc->path;
        // Log::info('EventImageController::getPrivateFile document path: ' . $path);

        // $file_path = 'uploads/events/' . $id . '/logo/' . $file;
        // $file_path = 'app/private/vapp/event/logo/' . $file;
        if (!Storage::disk('private')->exists($path)) {
            abort(404);
        }
        // $path = storage_path($file_path);

        // appLog('EventImageController::getPrivateFile path: ' . $path);

        // return response()->file($path);
        return Storage::disk('private')->response($path);
        // return Storage::disk('private')->response($file_path);
    }
}
