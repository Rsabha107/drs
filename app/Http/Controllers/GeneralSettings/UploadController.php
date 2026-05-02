<?php

namespace App\Http\Controllers\GeneralSettings;

use App\Http\Controllers\Controller;
use App\Models\Drs\TempUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    // public function process(Request $request)
    // {
    //     Log::info('UploadController process called');
    //     Log::info('Request: '. json_encode($request->all()));
    //     // FilePond sends the file under "file" by default
    //     $file = $request->file('qid_files');

    //     Log::info('files:' . json_encode($file));
    //     Log::info('content-type: ' . $request->header('content-type'));

    //     abort_unless($file, 422, 'No file uploaded');

    //     Log::info('File received: ' . $file->getClientOriginalName());
    //     $serverId = (string) Str::uuid();

    //     // store temp
    //     $path = $file->store('tmp/filepond', 'private');

    //     TempUpload::create([
    //         'id' => $serverId,
    //         'user_id' => Auth::id(),
    //         'disk' => 'private',
    //         'path' => $path,
    //         'original_name' => $file->getClientOriginalName(),
    //         'mime' => $file->getMimeType(),
    //         'size' => $file->getSize(),
    //     ]);

    //     // IMPORTANT: return a unique id (string). We'll return the path.
    //     return response($path, 200)->header('Content-Type', 'text/plain');
    // }

    // public function process(Request $request)
    // {
    //     // Get uploaded file (supports filepond name "file", "qid_files", or "qid_files[]")
    //     $file = $request->file('file');

    //     if (!$file) {
    //         Log::info('Trying qid_files key');
    //         $f = $request->file('qid_files'); // could be UploadedFile OR array
    //         if (is_array($f)) {
    //             $file = $f[0] ?? null;
    //         } else {
    //             $file = $f;
    //         }
    //     }

    //     if (!$file) {
    //         return response('No file uploaded', 422);
    //     }

    //     // Validate the actual file here
    //     $request->validate([
    //         // validate based on the found file key:
    //         // easiest: validate loosely then check mime/size manually OR validate by "file" key if you keep name="file"
    //     ]);

    //     // Better: validate using the file you found:
    //     // (Laravel doesn't validate dynamic keys nicely, so do manual checks)
    //     $mime = $file->getClientMimeType();
    //     $allowed = ['image/jpeg', 'image/png', 'image/gif'];
    //     if (!in_array($mime, $allowed)) {
    //         return response('Invalid file type', 422);
    //     }
    //     if ($file->getSize() > 2 * 1024 * 1024) {
    //         return response('File too large', 422);
    //     }

    //     $path = $file->store('tmp/qid', 'private');
    //     $serverId = (string) Str::uuid();

    //     $temp = TempUpload::create([
    //         'id' => $serverId,
    //         'path'          => $path,
    //         'disk'          => 'private',
    //         'user_id'        => auth()->id(), // will be null for guest registration - ok
    //         'original_name' => $file->getClientOriginalName(),
    //         'mime'          => $mime,
    //         'size'          => $file->getSize(),
    //     ]);

    //     // IMPORTANT: return ONLY the id as serverId
    //     // return response((string) $temp->id, 200)->header('Content-Type', 'text/plain');
    //     return response($path, 200)->header('Content-Type', 'text/plain');
    // }

    public function process(Request $request)
    {
        try {
            Log::info('UploadController::process called', [
                'has_file' => $request->hasFile('file'),
                'has_qid_files' => $request->hasFile('qid_files'),
                'all_files' => array_keys($request->allFiles()),
                'content_type' => $request->header('Content-Type'),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Collect all uploaded files (single or multiple)
            |--------------------------------------------------------------------------
            */
            $files = [];

            if ($request->hasFile('file')) {
                $files = [$request->file('file')];
            } elseif ($request->hasFile('qid_files')) {
                $f = $request->file('qid_files');
                $files = is_array($f) ? $f : [$f];
            }

            if (empty($files)) {
                Log::error('UploadController::process - No files found in request');
                return response('No file uploaded', 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Validate & process each file
            |--------------------------------------------------------------------------
            */
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
            $maxSize = 5 * 1024 * 1024; // 5MB

            $serverIds = [];

            foreach ($files as $file) {
                if (!$file->isValid()) {
                    Log::warning('UploadController::process - Invalid file', [
                        'error' => $file->getError(),
                    ]);
                    continue;
                }

                $mime = $file->getClientMimeType();
                Log::info('UploadController::process - Processing file', [
                    'name' => $file->getClientOriginalName(),
                    'mime' => $mime,
                    'size' => $file->getSize(),
                ]);

                if (!in_array($mime, $allowedMimes)) {
                    Log::error('UploadController::process - Invalid file type', [
                        'mime' => $mime,
                        'allowed' => $allowedMimes,
                    ]);
                    return response('Invalid file type: ' . $mime, 422);
                }

                if ($file->getSize() > $maxSize) {
                    Log::error('UploadController::process - File too large', [
                        'size' => $file->getSize(),
                        'max' => $maxSize,
                    ]);
                    return response('File too large', 422);
                }

                $serverId = (string) Str::uuid();
                $ext = $file->getClientOriginalExtension();
                $filename = $serverId . ($ext ? '.' . $ext : '');
                $path = 'tmp/qid/' . $filename;
                Storage::disk('private')->put($path, file_get_contents($file->getPathname()));

                TempUpload::create([
                    'id'             => $serverId,
                    'path'           => $path,
                    'disk'           => 'private',
                    'user_id'        => auth()->id(), // null allowed
                    'original_name'  => $file->getClientOriginalName(),
                    'mime'           => $mime,
                    'size'           => $file->getSize(),
                ]);

                Log::info('UploadController::process - File stored successfully', [
                    'serverId' => $serverId,
                    'path' => $path,
                ]);

                $serverIds[] = $serverId;
            }

            /*
            |--------------------------------------------------------------------------
            | FilePond response
            |--------------------------------------------------------------------------
            | - Single upload → plain text
            | - Multiple upload → JSON array
            */
            if (count($serverIds) === 1) {
                return response($serverIds[0], 200)
                    ->header('Content-Type', 'text/plain');
            }

            return response()->json($serverIds);
        } catch (\Exception $e) {
            Log::error('UploadController::process - Exception occurred', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response('Upload failed: ' . $e->getMessage(), 500);
        }
    }


    public function revert(Request $request)
    {
        // FilePond sends the "server id" as raw body (the string we returned in process)
        // Log::info('UploadController::Revert called with body: ' . $request->all());
        $serverId = trim($request->getContent() ?? '');

        Log::info('UploadController::Revert called with serverId: ' . $serverId);
        if (!$serverId) {
            return response('', 200);
        }

        Log::info('UploadController::Revert called for serverId: ' . $serverId);
        $temp = TempUpload::where('id', $serverId)
            ->when(Auth::check(), fn($q) => $q->where('user_id', Auth::id()))
            ->first();

        if ($temp) {
            Storage::disk($temp->disk)->delete($temp->path);
            $temp->delete();
        }

        return response('', 200);
    }
}
