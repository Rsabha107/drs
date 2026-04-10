<?php

namespace App\Http\Controllers\GeneralSettings;

use App\Http\Controllers\Controller;
use App\Models\Wdr\ParticipantDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ParticipantDocumentController extends Controller
{
    public function download(ParticipantDocument $document)
    {
        Log::info('Request to download participant document: ' . $document->id);
        // TODO: add policy check (important!)
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        Log::info('Downloading participant document: ' . $document->id);

        abort_unless(Storage::disk($document->disk)->exists($document->path), 404);

        // inline preview for images/pdf
        return Storage::disk($document->disk)->response($document->path, $document->original_name ?? basename($document->path), [
            'Content-Disposition' => 'inline; filename="' . ($document->original_name ?? basename($document->path)) . '"',
        ]);

        // download for other file types
        return Storage::disk($document->disk)->download(
            $document->path,
            $document->original_name ?? basename($document->path)
        );
    }

    public function destroy(ParticipantDocument $document)
    {
        // TODO: add policy check (important!)
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }
        Storage::disk($document->disk)->delete($document->path);
        $document->delete();

        Log::info('Deleted participant document: ' . $document->id);

        return response()->json(['error' => false, 'message' => 'Document deleted']);
    }
}
