<?php

namespace App\Http\Controllers\Drs\Shared;

use App\Exports\IssueLogExport;
use App\Http\Controllers\Controller;
use App\Imports\VocIssuesImport;
use App\Jobs\SendNewReportEmailJob;
use Illuminate\Http\Request;
use App\Models\Drs\Event;
use App\Models\Drs\EventMatch;
use App\Models\Drs\TempUpload;
use App\Models\Drs\Team;
use App\Models\Drs\Venue;
use App\Models\Drs\VenueMatchReport;
use App\Models\Drs\VenueMatchReportDocument;
use App\Models\Drs\VocIssue;
use App\Models\Drs\WorkforceDailyReport;
use App\Models\Drs\WorkforceDailyReportDocument;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class VenueMatchReportController extends Controller
{

    public function create()
    {
        $events = Event::findOrFail(session()->get('EVENT_ID'));
        $venues = $events->venues;
        $draftToken = (string) Str::uuid();
        $user = Auth::user();
        $teams = Team::all();

        $vocIssues = VocIssue::where('draft_token', $draftToken)->get();

        return view('drs.shared.create', compact(
            'events',
            'venues',
            'draftToken',
            'vocIssues',
            'user',
            'teams',
        ));
    }

    public function edit($id)
    {
        $report = VenueMatchReport::findOrFail($id);
        $events = Event::findOrFail(session()->get('EVENT_ID'));
        $venues = $events->venues;
        $user = Auth::user();
        $teams = Team::all();

        $vocIssues = VocIssue::where('draft_token', $report->draft_token)->get();

        return view('drs.shared.edit', compact(
            'events',
            'venues',
            'vocIssues',
            'user',
            'teams',
            'report'
        ));
    }

    public function preview(Request $request)
    {
        $request->validate([
            'draft_token' => 'required|string|size:36',
        ]);

        $issues = VocIssue::where('draft_token', $request->draft_token)
            ->orderByRaw('CAST(issue_id AS UNSIGNED) ASC')
            ->get();

        return view('drs.shared.voc_table', compact('issues'));
    }

    public function clear(Request $request)
    {
        $request->validate([
            'draft_token' => 'required|string|size:36',
        ]);

        VocIssue::where('draft_token', $request->draft_token)->delete();

        return response()->json(['ok' => true]);
    }

    public function gallery($id)
    {
        $events = Event::all();
        $report = VenueMatchReport::find($id);

        // $this->authorize('view', $report);
        return view('drs.shared.gallery', compact(
            'events',
            'report',
        ));
    }

    public function store(Request $request)
    {
        Log::info('Store VenueMatchReport called');
        // Log::info('Request Data: ' . json_encode($request->all()));

        return DB::transaction(function () use ($request) {

            $rules = [
                'match_number' => ['required', 'string', 'max:255'],
                // 'venue_manager_name' => ['required', 'string', 'max:255'],
                'match_date' => ['required', 'date_format:d/m/Y'],
                'match_time' => ['required'], // keep as string if you store time string
                'stage' => ['required', 'string', 'max:255'],
                'venue_id' => ['required', 'integer'],
                'team_a_name' => ['required', 'string', 'max:255'],
                'team_b_name' => ['required', 'string', 'max:255'],
                'final_score' => ['required', 'string', 'max:50'],
                'match_half_time' => ['required'], // keep as string if you store time string
                'official_attendance' => ['required'], // number or string depending on your column

                'actions_vum' => ['nullable', 'string'],
                'mobility_section' => ['nullable', 'string'],
                'general_issues' => ['nullable', 'string'],
                'fa_observations' => ['nullable', 'string'],

                'submit_action' => ['required', Rule::in(['draft', 'submitted'])],

                // filepond ids are JSON string
                'qid_server_ids' => ['nullable', 'string'],

                // // native photos
                // 'photos.*' => ['nullable', 'file', 'max:2048', 'mimes:jpg,jpeg,png,gif,webp,pdf'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                Log::info($validator->errors());
                $error = true;
                $type = 'error';
                // $message = 'Guest could not be created';
                $message = implode($validator->errors()->all());
                $toastr_message = [
                    'alert-type' => $type,
                    'message' => $message,
                ];

                return redirect()->back()->with('toastr', [
                    'type' => 'error',
                    'messages' => $validator->errors()->all()
                ])->withInput();

                // return response()->json($toastr_message, 422);
                // return response()->json(['error' => $error, 'message' => $message]);
            }

            DB::beginTransaction();
            try {

                $seq = nextSequence('vms');
                $user = Auth::user();
                $op = new VenueMatchReport();
                // draft vs publish
                $op->event_id = session()->get('EVENT_ID');
                $op->reference_number = 'VMS-' . date('Y') . '-' . $request->match_number . '-' . get_current_event_id() . '-' . str_pad($seq, 5, '0', STR_PAD_LEFT);

                $isDraft = ($request->input('submit_action') === 'draft');

                // adjust fields to your columns
                $op->draft_token = $request->draft_token;
                $op->status = $isDraft ? 'draft' : 'submitted';

                // main information
                $op->match_number = $request->match_number;
                $op->match_date = $request->match_date ? Carbon::createFromFormat('d/m/Y', $request->match_date)->toDateString() : null;
                $op->match_time = $request->match_time;
                $op->match_half_time = $request->match_half_time;
                $op->stage = $request->stage;
                $op->venue_id = $request->venue_id;
                $op->team_a_name = $request->team_a_name;
                $op->team_b_name = $request->team_b_name;
                $op->final_score = $request->final_score;
                $op->official_attendance = $request->official_attendance;
                $op->venue_manager_name = $user->name;

                //extra time and penalties logic
                $op->match_extra_time_flag = $request->filled('extra_time') ? (int) $request->extra_time : null;
                $op->match_penalties_flag = $request->filled('penalties') ? (int) $request->penalties : null;
                $op->penalties_final_score = $request->filled('penalties_final_score') ?  $request->penalties_final_score : null;

                // Actions Taken by VUM (Pre-Match Day)
                $op->actions_vum = $request->actions_vum ?? null;

                // client groups 
                // spectators

                $op->spectators_vvip = $request->filled('spectators_vvip') ? (int) $request->spectators_vvip : null;
                $op->spectators_vip = $request->filled('spectators_vip') ? (int) $request->spectators_vip : null;
                $op->spectators_hospitality_skyboxes = $request->filled('spectators_hospitality_skyboxes') ? (int) $request->spectators_hospitality_skyboxes : null;
                $op->spectators_hospitality_lounges = $request->filled('spectators_hospitality_lounges') ? (int) $request->spectators_hospitality_lounges : null;

                // media 
                $op->media_tribune = $request->filled('media_tribune') ? (int) $request->media_tribune : null;
                $op->photo_tribune = $request->filled('photo_tribune') ? (int) $request->photo_tribune : null;
                $op->photo_pitch = $request->filled('photo_pitch') ? (int) $request->photo_pitch : null;
                $op->mixed_zone = $request->filled('mixed_zone') ? (int) $request->mixed_zone : null;
                $op->press_conference = $request->filled('press_conference') ? (int) $request->press_conference : null;

                // broadcast
                $op->broadcast_personnel = $request->filled('broadcast_personnel') ? (int) $request->broadcast_personnel : null;

                // services
                $op->sps_staff_expected = $request->filled('sps_staff_expected') ? (int) $request->sps_staff_expected : null;
                $op->sps_staff_arrived = $request->filled('sps_staff_arrived') ? (int) $request->sps_staff_arrived : null;
                $op->volunteers_expected = $request->filled('volunteers_expected') ? (int) $request->volunteers_expected : null;
                $op->volunteers_arrived = $request->filled('volunteers_arrived') ? (int) $request->volunteers_arrived : null;
                $op->hospitality_services_expected = $request->filled('hospitality_services_expected') ? (int) $request->hospitality_services_expected : null;
                $op->hospitality_services_arrived = $request->filled('hospitality_services_arrived') ? (int) $request->hospitality_services_arrived : null;
                $op->fnb_concessions_expected = $request->filled('fnb_concessions_expected') ? (int) $request->fnb_concessions_expected : null;
                $op->fnb_concessions_arrived = $request->filled('fnb_concessions_arrived') ? (int) $request->fnb_concessions_arrived : null;
                $op->medical_staff_expected = $request->filled('medical_staff_expected') ? (int) $request->medical_staff_expected : null;
                $op->medical_staff_arrived = $request->filled('medical_staff_arrived') ? (int) $request->medical_staff_arrived : null;
                $op->cleaning_waste_expected = $request->filled('cleaning_waste_expected') ? (int) $request->cleaning_waste_expected : null;
                $op->cleaning_waste_arrived = $request->filled('cleaning_waste_arrived') ? (int) $request->cleaning_waste_arrived : null;


                //PSA/Turnstiles Operations
                $op->psa_scanned = $request->filled('psa_scanned') ? (int) $request->psa_scanned : null;
                $op->turnstiles_scanned = $request->filled('turnstiles_scanned') ? (int) $request->turnstiles_scanned : null;
                $op->accreditation_scanned = $request->filled('accreditation_scanned') ? (int) $request->accreditation_scanned : null;

                // Mobility Section
                $op->metro_inbound = $request->filled('metro_inbound') ? (int) $request->metro_inbound : null;
                $op->metro_outbound = $request->filled('metro_outbound') ? (int) $request->metro_outbound : null;
                $op->taxi_inbound = $request->filled('taxi_inbound') ? (int) $request->taxi_inbound : null;
                $op->taxi_outbound = $request->filled('taxi_outbound') ? (int) $request->taxi_outbound : null;
                $op->parking_count = $request->filled('parking_count') ? (int) $request->parking_count : null;
                $op->mobility_section = $request->mobility_section ?? null;

                // Shukran program
                $op->shukran_programme = $request->has('shukran_programme') ? 1 : 0;
                $op->shukran_count = $request->filled('shukran_count')
                    ? (int) $request->shukran_count
                    : null;

                //Venue Manager General Comments
                $op->general_issues = $request->general_issues ?? null;

                $op->created_by = $user->id;

                $op->save();

                //check if voc issues exist for this draft token and attach to report
                $vocIssues = VocIssue::where('draft_token', $request->draft_token)->get();
                if ($vocIssues->count() > 0) {
                    foreach ($vocIssues as $issue) {
                        $issue->report_id = $op->id;
                        // $issue->draft_token = null; // clear draft token
                        $issue->save();
                    }
                }

                // Attach FilePond docs
                $this->attachTempUploadsToReport($op, $request);

                DB::commit();

                return redirect()
                    ->route('home')
                    ->with('message', $isDraft ? 'Draft saved.' : 'Report published.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error storing Venue Match Report: ' . $e->getMessage());
                $message = $e->getMessage();
                // $message = 'There was an error saving the report. Please try again.';
                return redirect()->back()->with('toastr', [
                    'type' => 'error',
                    'messages' => $message
                ])->withInput();
                // return redirect()->back()->with('error', 'There was an error saving the report. Please try again.')->withInput();
                // return redirect()->back()
                //     ->withErrors(['error' => 'There was an error saving the report. Please try again.'])
                //     ->withInput(); // this shows for $errors->all() in blade
            }
        });
    }

    public function update(Request $request)
    {
        Log::info('Update VenueMatchReport called');
        Log::info('Request Data: ' . json_encode($request->all()));

        $rules = [
            'match_number' => ['required', 'string', 'max:255'],
            // 'venue_manager_name' => ['required', 'string', 'max:255'],
            'match_date' => ['required', 'date_format:d/m/Y'],
            'match_time' => ['required'], // keep as string if you store time string
            'match_half_time' => ['required'], // keep as string if you store time string
            'stage' => ['required', 'string', 'max:255'],
            'venue_id' => ['required', 'integer'],
            'team_a_name' => ['required', 'string', 'max:255'],
            'team_b_name' => ['required', 'string', 'max:255'],
            'final_score' => ['required', 'string', 'max:50'],
            'official_attendance' => ['required'], // number or string depending on your column

            'actions_vum' => ['nullable', 'string'],
            'mobility_section' => ['nullable', 'string'],
            'general_issues' => ['nullable', 'string'],
            'fa_observations' => ['nullable', 'string'],

            'submit_action' => ['required', Rule::in(['draft', 'submitted'])],

            // filepond ids are JSON string
            'qid_server_ids' => ['nullable', 'string'],

            // // native photos
            // 'photos.*' => ['nullable', 'file', 'max:2048', 'mimes:jpg,jpeg,png,gif,webp,pdf'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::info($validator->errors());
            $error = true;
            $type = 'error';
            // $message = 'Guest could not be created';
            $message = implode($validator->errors()->all());
            $toastr_message = [
                'alert-type' => $type,
                'message' => $message,
            ];

            return redirect()->back()->with('toastr', [
                'type' => 'error',
                'messages' => $validator->errors()->all()
            ])->withInput();

            // return response()->json($toastr_message, 422);
            // return response()->json(['error' => $error, 'message' => $message]);
        }

        DB::beginTransaction();
        try {

            $user = Auth::user();
            $op = VenueMatchReport::findOrFail($request->report_id);
            // draft vs publish
            $op->event_id = session()->get('EVENT_ID');

            $isDraft = ($request->input('submit_action') === 'draft');
            $op->status = $isDraft ? 'draft' : 'submitted';

            // adjust fields to your columns
            $op->draft_token = $request->draft_token;

            // main information
            $op->match_number = $request->match_number;
            $op->match_date = $request->match_date ? Carbon::createFromFormat('d/m/Y', $request->match_date)->toDateString() : null;
            $op->match_time = $request->match_time;
            $op->match_half_time = $request->match_half_time;
            $op->stage = $request->stage;
            $op->venue_id = $request->filled('venue_id') ? (int) $request->venue_id : null;
            $op->team_a_name = $request->team_a_name;
            $op->team_b_name = $request->team_b_name;
            $op->final_score = $request->final_score;
            $op->official_attendance = $request->official_attendance;
            $op->venue_manager_name = $user->name;

            //extra time and penalties logic
            $op->match_extra_time_flag = $request->filled('extra_time') ? (int) $request->extra_time : null;
            $op->match_penalties_flag = $request->filled('penalties') ? (int) $request->penalties : null;
            $op->penalties_final_score = $request->filled('penalties_final_score') ?  $request->penalties_final_score : null;

            // Actions Taken by VUM (Pre-Match Day)
            $op->actions_vum = $request->actions_vum ?? null;

            // client groups 
            // spectators
            $op->spectators_vvip = $request->filled('spectators_vvip') ? (int) $request->spectators_vvip : null;
            $op->spectators_vip = $request->filled('spectators_vip') ? (int) $request->spectators_vip : null;
            $op->spectators_hospitality_skyboxes = $request->filled('spectators_hospitality_skyboxes') ? (int) $request->spectators_hospitality_skyboxes : null;
            $op->spectators_hospitality_lounges = $request->filled('spectators_hospitality_lounges') ? (int) $request->spectators_hospitality_lounges : null;

            // media 
            $op->media_tribune = $request->filled('media_tribune') ? (int) $request->media_tribune : null;
            $op->photo_tribune = $request->filled('photo_tribune') ? (int) $request->photo_tribune : null;
            $op->photo_pitch = $request->filled('photo_pitch') ? (int) $request->photo_pitch : null;
            $op->mixed_zone = $request->filled('mixed_zone') ? (int) $request->mixed_zone : null;
            $op->press_conference = $request->filled('press_conference') ? (int) $request->press_conference : null;

            // broadcast
            $op->broadcast_personnel = $request->filled('broadcast_personnel') ? (int) $request->broadcast_personnel : null;

            // services
            $op->sps_staff_expected = $request->filled('sps_staff_expected') ? (int) $request->sps_staff_expected : null;
            $op->sps_staff_arrived = $request->filled('sps_staff_arrived') ? (int) $request->sps_staff_arrived : null;
            $op->volunteers_expected = $request->filled('volunteers_expected') ? (int) $request->volunteers_expected : null;
            $op->volunteers_arrived = $request->filled('volunteers_arrived') ? (int) $request->volunteers_arrived : null;
            $op->hospitality_services_expected = $request->filled('hospitality_services_expected') ? (int) $request->hospitality_services_expected : null;
            $op->hospitality_services_arrived = $request->filled('hospitality_services_arrived') ? (int) $request->hospitality_services_arrived : null;
            $op->fnb_concessions_expected = $request->filled('fnb_concessions_expected') ? (int) $request->fnb_concessions_expected : null;
            $op->fnb_concessions_arrived = $request->filled('fnb_concessions_arrived') ? (int) $request->fnb_concessions_arrived : null;
            $op->medical_staff_expected = $request->filled('medical_staff_expected') ? (int) $request->medical_staff_expected : null;
            $op->medical_staff_arrived = $request->filled('medical_staff_arrived') ? (int) $request->medical_staff_arrived : null;
            $op->cleaning_waste_expected = $request->filled('cleaning_waste_expected') ? (int) $request->cleaning_waste_expected : null;
            $op->cleaning_waste_arrived = $request->filled('cleaning_waste_arrived') ? (int) $request->cleaning_waste_arrived : null;

            //PSA/Turnstiles Operations
            $op->psa_scanned = $request->filled('psa_scanned') ? (int) $request->psa_scanned : null;
            $op->turnstiles_scanned = $request->filled('turnstiles_scanned') ? (int) $request->turnstiles_scanned : null;
            $op->accreditation_scanned = $request->filled('accreditation_scanned') ? (int) $request->accreditation_scanned : null;

            // Mobility Section
            $op->metro_inbound = $request->filled('metro_inbound') ? (int) $request->metro_inbound : null;
            $op->metro_outbound = $request->filled('metro_outbound') ? (int) $request->metro_outbound : null;
            $op->taxi_inbound = $request->filled('taxi_inbound') ? (int) $request->taxi_inbound : null;
            $op->taxi_outbound = $request->filled('taxi_outbound') ? (int) $request->taxi_outbound : null;
            $op->parking_count = $request->filled('parking_count') ? (int) $request->parking_count : null;
            $op->mobility_section = $request->mobility_section ?? null;

            // Shukran program
            $op->shukran_programme = $request->has('shukran_programme') ? 1 : 0;
            $op->shukran_count = $request->filled('shukran_count')
                ? (int) $request->shukran_count
                : null;

            //Venue Manager General Comments
            $op->general_issues = $request->general_issues ?? null;

            $op->created_by = $user->id;

            $op->save();

            //check if voc issues exist for this draft token and attach to report
            $vocIssues = VocIssue::where('draft_token', $request->draft_token)->get();
            if ($vocIssues->count() > 0) {
                foreach ($vocIssues as $issue) {
                    $issue->report_id = $op->id;
                    // $issue->draft_token = null; // clear draft token
                    $issue->save();
                }
            }


            // Attach FilePond docs
            $this->attachTempUploadsToReportEdit($op, $request);

            DB::commit();

            return redirect()
                ->route('home')
                ->with('message', $isDraft ? 'Draft saved.' : 'Report published.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing Venue Match Report: ' . $e->getMessage());
            $message = $e->getMessage();
            // $message = 'There was an error saving the report. Please try again.';
            return redirect()->back()->with('toastr', [
                'type' => 'error',
                'messages' => $message
            ])->withInput();
            // return redirect()->back()->with('error', 'There was an error saving the report. Please try again.')->withInput();
            // return redirect()->back()
            //     ->withErrors(['error' => 'There was an error saving the report. Please try again.'])
            //     ->withInput(); // this shows for $errors->all() in blade
        }
    }

    private function attachTempUploadsToReportEdit(VenueMatchReport $report, Request $request): void
    {
        $keepRaw = json_decode($request->input('qid_server_ids', '[]'), true) ?? [];
        $deleteDocIds = json_decode($request->input('delete_doc_ids', '[]'), true) ?? [];

        $keepDocIds = [];
        $tempIds = [];

        foreach ($keepRaw as $v) {
            if (is_string($v) && str_starts_with($v, 'doc:')) {
                $keepDocIds[] = (int) substr($v, 4);
            } else {
                $tempIds[] = $v; // new TempUpload UUIDs
            }
        }

        Log::info('Keeping existing document IDs: ' . implode(',', $keepDocIds));
        Log::info('Deleting document IDs: ' . implode(',', $deleteDocIds));
        Log::info('New TempUpload IDs to attach: ' . implode(',', $tempIds));

        // Delete removed documents
        $docsToDelete = VenueMatchReportDocument::where('report_id', $report->id)
            ->whereIn('id', $deleteDocIds)
            ->get();
        foreach ($docsToDelete as $doc) {
            // delete file
            Log::info('Deleting file for document ID ' . $doc->id . ' at path ' . $doc->path . ' on disk ' . $doc->disk);
            if ($doc->disk && $doc->path) {
                Storage::disk($doc->disk)->delete($doc->path);
            }
            // delete record
            $doc->delete();
        }

        // Attach new TempUploads
        if (empty($tempIds)) return;
        $temps = TempUpload::whereIn('id', $tempIds)->get();

        Log::info('Found ' . $temps->count() . ' TempUploads to attach.');
        Log::info('TempUploads Data: ' . json_encode($temps));
        Log::info('TempUploads IDs: ' . implode(',', $temps->pluck('id')->toArray()));

        foreach ($temps as $temp) {
            // Avoid duplicates if update called multiple times
            Log::info('Attaching TempUpload ID ' . $temp->id . ' file name:' . $temp->original_name . ' to report ID ' . $report->id);
            $exists = VenueMatchReportDocument::where('report_id', $report->id)
                ->where('temp_upload_id', $temp->id)
                ->exists();
            Log::info('Exists check: ' . ($exists ? 'yes' : 'no'));
            if ($exists) continue;

            // move from tmp/qid/... to reports/{id}/qid/...
            $newPath = "reports/{$report->id}/qid/" . basename($temp->path);

            // move on same disk
            if (Storage::disk($temp->disk)->exists($temp->path)) {
                Storage::disk($temp->disk)->move($temp->path, $newPath);
            } else {
                // If file missing, skip (don’t crash whole save)
                continue;
            }

            VenueMatchReportDocument::create([
                'report_id' => $report->id,
                'temp_upload_id' => $temp->id,
                'disk' => $temp->disk,
                'extension' => pathinfo($temp->path, PATHINFO_EXTENSION),
                'path' => $newPath,
                'original_name' => $temp->original_name,
                'mime' => $temp->mime,
                'size' => $temp->size,
            ]);

            // optional: delete temp row after attach
            $temp->delete();
            // remove temp upload file if needed
            if (Storage::disk($temp->disk)->exists($temp->path)) {
                Storage::disk($temp->disk)->delete($temp->path);
            }
        }
    }

    private function attachTempUploadsToReport(VenueMatchReport $report, Request $request): void
    {
        $raw = $request->input('qid_server_ids');
        if (!$raw) return;

        $ids = json_decode($raw, true);
        if (!is_array($ids) || empty($ids)) return;

        // only take temp uploads that exist
        $temps = TempUpload::whereIn('id', $ids)->get();

        foreach ($temps as $temp) {
            // Avoid duplicates if update called multiple times
            $exists = VenueMatchReportDocument::where('report_id', $report->id)
                ->where('temp_upload_id', $temp->id)
                ->exists();
            if ($exists) continue;

            // move from tmp/qid/... to reports/{id}/qid/...
            $newPath = "reports/{$report->id}/qid/" . basename($temp->path);

            // move on same disk
            if (Storage::disk($temp->disk)->exists($temp->path)) {
                Storage::disk($temp->disk)->move($temp->path, $newPath);
            } else {
                // If file missing, skip (don’t crash whole save)
                continue;
            }

            VenueMatchReportDocument::create([
                'report_id' => $report->id,
                'temp_upload_id' => $temp->id,
                'disk' => $temp->disk,
                'extension' => pathinfo($temp->path, PATHINFO_EXTENSION),
                'path' => $newPath,
                'original_name' => $temp->original_name,
                'mime' => $temp->mime,
                'size' => $temp->size,
            ]);

            // optional: delete temp row after attach
            $temp->delete();
            // remove temp upload file if needed
            if (Storage::disk($temp->disk)->exists($temp->path)) {
                Storage::disk($temp->disk)->delete($temp->path);
            }
        }
    }

    public function switch($id)
    {
        if ($id) {
            if (Event::findOrFail($id)) {
                appLog('Event ID: ' . $id);

                session()->put('EVENT_ID', $id);
                appLog('Event ID: ' . session()->get('EVENT_ID'));
                // return redirect()->route('tracki.project.show.card')->with('message', 'Workspace switched successfully.');
                return redirect()->route('home')->with('message', 'Event Switched.');
                // return back()->with('message', 'Event Switched.');
            } else {
                // return back()->with('error', 'Workspace not found.');
                // return redirect()->route('tracki.project.show.card')->with('error', 'Workspace not found.');
                return back()->with('error', 'Event not found.');
            }
        } else {
            session()->forget('EVENT_ID');
            // return redirect()->route('tracki.project.show.card')->with('message', 'Workspace switched successfully. now showing all workspace data');
            return back()->withInput();
        }
    }

    public function pickEvent(Request $request)
    {
        // $events = Event::all();
        // $this->switch($request->event_id);
        // return view('vapp.admin.booking.pick', compact('events'));
        if ($request->event_id) {
            // appLog('Event ID: ' . $request->event_id);
            if (Event::findOrFail($request->event_id) && !session()->has('EVENT_ID')) {
                // appLog('Inside if statement Event ID: ' . $request->event_id);

                session()->put('EVENT_ID', $request->event_id);
                session()->put('VENUE_ID', $request->venue_id);
                // appLog('session EVENT_ID: ' . session()->get('EVENT_ID'));
                // appLog('before redirect');
                // return redirect()->route('tracki.project.show.card')->with('message', 'Workspace switched successfully.');
                return redirect()->route('home')->with('message', 'Event Switched.');
                // return back()->with('message', 'Event Switched.');
            }
        }
        //  else {
        // return back()->with('error', 'Workspace not found.');
        // return redirect()->route('tracki.project.show.card')->with('error', 'Workspace not found.');
        // appLog('event_id is null');
        return redirect()->route('home')->with('error', 'Event not found.');
        // }
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            $report = VenueMatchReport::with('photos')->findOrFail($id);

            // Delete files first
            foreach ($report->photos as $doc) {
                if ($doc->disk && $doc->path) {
                    Storage::disk($doc->disk)->delete($doc->path);
                }
            }
            Storage::disk('private')->deleteDirectory("reports/{$report->id}");

            // Delete document records
            $report->photos()->delete();

            // Delete the report itself
            $report->delete();
        });

        return response()->json([
            'error'   => false,
            'message' => 'Venue Match Report and all images deleted successfully.',
        ]);
    }

    public function save_pass_pdf($wdr)
    {
        // set_time_limit(300);

        $qr_code = null;

        $data = [
            'wdr' => $wdr,
            'qr_code' => $qr_code,
        ];

        $data['css'] = public_path('assets/css/invoice.css');
        $pdf = Pdf::loadView('drs.shared.report.rpdf', $data);
        Storage::disk('private')->put('wdr/pdf-exports/' . $wdr->reference_number . '.pdf', $pdf->output());

        return 1;
    }

    public function importAjax(Request $request)
    {
        $data = $request->validate([
            'excel' => 'required|file|mimes:xlsx,xls|max:5120',
            'draft_token' => 'required|string|size:36',
        ]);

        // clear previous import for this draft (optional)
        VocIssue::where('draft_token', $data['draft_token'])->delete();

        Excel::import(new VocIssuesImport(null, $data['draft_token']), $request->file('excel'));

        $issues = VocIssue::where('draft_token', $data['draft_token'])
            ->orderByRaw('CAST(issue_id AS UNSIGNED) ASC')
            ->get();

        $html = view('drs.shared.voc_table', compact('issues'))->render();

        return response()->json(['ok' => true, 'html' => $html]);
    }

    // pdf report
    public function reportPdf(Request $request, $id)
    {

        $op = VenueMatchReport::findOrFail($id);

        $qr_code = null;

        $data = [
            'op' => $op,
            'qr_code' => $qr_code,
            // 'rsp_arrival_date' => $rspArrivalDate,
        ];

        if ($request->has('preview')) {
            $data['css'] = asset('assets/css/invoice.css');
            return view('drs.shared.rpdf', $data);
        } else {
            $data['css'] = public_path('assets/css/invoice.css');
        }

        // ---------- Build automated filename ----------
        // Pick the right date field from your model (adjust if needed)
        $date = $op->match_date ?? $op->report_date ?? $op->created_at;
        $dateStr = \Carbon\Carbon::parse($date)->format('Ymd');

        // Stadium / venue code (adjust field names)
        $stadiumCode = $op->venue?->short_name ?? 'Stadium';

        // Report number (adjust field names)
        $reportNo = $op->match_number ?? '0';

        // Match label like Kuwait-Qatar (adjust field names)
        $teamA = $op->team_a_name ?? 'A';
        $teamB = $op->team_b_name ?? 'B';
        $matchLabel = trim($teamA . '-' . $teamB);

        // Example: 20260216_974_Match Report #1_Kuwait-Qatar.pdf
        $filename = "{$dateStr}_{$stadiumCode}_Match_Report_{$reportNo}" . ($matchLabel ? "_{$matchLabel}" : '') . ".pdf";

        // sanitize filename for Windows/Linux
        $filename = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $filename);
        $filename = preg_replace('/\s+/', ' ', trim($filename));
        // ---------------------------------------------


        // Pdf::view('mds.booking.passx');
        // Pdf::view('mds.booking.passx')->save('/upload/passx.pdf');
        // return view('mds.booking.passx', $data);
        $pdf = Pdf::loadView('drs.shared.rpdf', $data);
        // return $pdf->download('itsolutionstuff.pdf');
        return $pdf->stream($filename);
    }  //reportPdf

    public function export(Request $request, $id)
    {
        // $venue   = $request->venue;
        // $matchNo = $request->match_no;
        // $date    = $request->issue_date;

        $fileName = 'issue_log_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new IssueLogExport($id),  $fileName);
    }

    public function getByVenue($venueId)
    {
        $eventId = session('EVENT_ID');

        $matches = EventMatch::where('event_id', $eventId)
            ->where('venue_id', $venueId)
            ->orderBy('match_number')
            ->get(['id', 'match_number']);

        return response()->json($matches);
    }

    public function getDetails($matchId)
    {
        $match = EventMatch::findOrFail($matchId);
        $match_date = $match->match_date ? Carbon::parse($match->match_date)->format('d/m/Y') : null;


        return response()->json([
            'id' => $match->id,
            'match_number' => $match->match_number,
            'match_date' => $match_date,
            'stage' => $match->stage,
            'pma1' => $match->pma1,
            'pma2' => $match->pma2,
        ]);
    }
}
