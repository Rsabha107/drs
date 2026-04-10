<?php

namespace App\Http\Controllers\Drs\Admin;

use App\Http\Controllers\Controller;
use App\Models\Drs\Event;
use App\Models\Drs\Venue;
use App\Models\Drs\VenueMatchReport;
use Illuminate\Http\Request;

use ZipArchive;

class VenueMatchReportController extends Controller
{
    //
    public function index()
    {
        $wd_reports = VenueMatchReport::all();
        $event = Event::with('venues')->where('id', session()->get('EVENT_ID'))->get();
        // get the venues of the event
        $venues = $event->first()->venues ?? collect();

        return view('drs.admin.report.list', compact(
            'wd_reports',
            'event',
            'venues',
        ));
    }

    public function list(Request $request)
    {
        $search = request('search');
        $filter = request('filter');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $mds_schedule_event_filter = (request()->mds_schedule_event_filter) ? request()->mds_schedule_event_filter : "";
        $mds_schedule_venue_filter = (request()->mds_schedule_venue_filter) ? request()->mds_schedule_venue_filter : "";
        $mds_schedule_rsp_filter = (request()->mds_schedule_rsp_filter) ? request()->mds_schedule_rsp_filter : "";

        $ops = VenueMatchReport::orderBy($sort, $order);
        $ops = $ops->where('event_id', session()->get('EVENT_ID'));

        if ($search) {
            $ops = $ops->where('incidents', 'like', '%' . $search . '%')
                ->orWhere('other_notes', 'like', '%' . $search . '%');
        }


        if ($mds_schedule_event_filter) {
            $ops = $ops->where('event_id', $mds_schedule_event_filter);
        }

        if ($mds_schedule_venue_filter) {
            $ops = $ops->where('venue_id', $mds_schedule_venue_filter);
        }

        if ($mds_schedule_rsp_filter) {
            $ops = $ops->where('rsp_id', $mds_schedule_rsp_filter);
        }

        $total = $ops->count();
        $ops = $ops->paginate(request("limit"))->through(function ($op) {

            // $location = Location::find($guests->location_id);
            $full_name = $op->first_name . ' ' . $op->last_name;
            if ($op->is_admin == 'X') {
                $avatar_status = 'status-away';
            } else {
                $avatar_status = '';
            }

            if ($op->photo) {
                $image = ' <div class="avatar avatar-m ' . $avatar_status . '">
                                <a  href="#" role="button" title="' . $full_name . '">
                                    <img class="rounded-circle pull-up" src="/storage/upload/profile_images/' . $op->photo . '" alt="" />
                                </a>
                            </div>';
            } else {
                $image = '  <div class="avatar avatar-m ' . $avatar_status . '  me-1" id="project_team_members_init">
                                <a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" title="' . $full_name . '">
                                    <div class="avatar avatar-m  rounded-circle pull-up">
                                        <div class="avatar-name rounded-circle me-2"><span>' . generateInitials($full_name) . '</span></div>
                                    </div>
                                </a>
                            </div>';
            }

            $actions = '<div class="font-sans-serif btn-reveal-trigger position-static">';
            // Generate Pass
            $actions_pass = '<a href="' . route('drs.report.pdf', $op->id) . '" target="_blank" class="btn p-1"'
                . ' title="Generate Pass">'
                . '<i class="fas fa-passport text-success"></i></a>';
            $actions_export_issue = '<a href="' . route('issue-logs.export', $op->id) . '" target="_blank" class="btn p-1"'
                . ' title="Export Issue">'
                . '<i class="fas fa-file-excel text-warning"></i></a>';
            // $edit_actions = '<a href="javascript:void(0)" class="btn btn-sm" id="edit_guest_offcanv" data-id="' .
            //     $op->id .
            //     '" data-table="guest_table" data-bs-toggle="tooltip" data-bs-placement="right" title="Update">' .
            //     '<i class="fa-solid fa-pen-to-square text-primary"></i></a>';
            $edit_actions = '<a href="' . route('drs.report.edit', $op->id) . '" class="btn p-1" id="edit_report" data-id="' .
                 $op->id .  '" data-table="report_table" title="Edit Report" data-bs-toggle="tooltip" data-bs-placement="right">'
                . '<i class="fa-solid fa-pen-to-square text-primary"></i></a>';
            $delete_actions =
                '<a href="javascript:void(0)" class="btn p-1" data-table="report_table" data-id="' .
                $op->id .
                '" id="deleteReport" data-bs-toggle="tooltip" data-bs-placement="right" title="Delete">' .
                '<i class="bx bx-trash text-danger"></i></a>';
            // $upload_img_actions =
            //     '<a href="javascript:void(0)" class="btn btn-sm" data-table="report_table" data-id="' .
            //     $op->id .
            //     '" id="uploadImagesReport" data-bs-toggle="tooltip" data-bs-placement="right" title="Upload Images">' .
            //     '<i class="bx bx-arrow-to-top text-success"></i></a>';

            $actions .=  $actions . $actions_pass . $actions_export_issue . $edit_actions . $delete_actions;
            $actions .= '</div>';

            // $order_status =  '<span class="badge badge-phoenix fs--2 ms-2 badge-phoenix-' . $op->status?->color . ' "><span class="badge-label" id="change_participant_status" style="cursor:pointer" data-id="' . $op->id . '"data-status_id="' . $op->status?->id . '" data-table="participant_table">' . $op->status?->title . '</span><span class="ms-1" data-feather="x" style="height:12.8px;width:12.8px;cursor:pointer"></span></span>';
            // $qid_image_route = $op->qidDocument
            //     ? '<a href="' . route('participant.docs.download', $op->qidDocument) . '" target="_blank" ><span><i class="fa-solid fa-eye me-2"></i>' . $op->qid . '</span></a>'
            //     : $op->qid;

            // $gardian_qid_image_route = $op->guardian->qidDocument
            //     ? '<a href="' . route('guardian.docs.download', $op->guardian->qidDocument) . '" target="_blank" ><span><i class="fa-solid fa-eye me-2"></i>' . $op->guardian->qid . '</span></a>'
            //     : $op->guardian->qid;
            if ($op->photos()->exists()) {
                $image_icon_color = 'text-success';
                $href_route = route('drs.report.gallery', ['id' => $op->id]);
                // $link = '<a href="' . $href_route . '" target="_blank"><i class="fa-solid fa-image fa-2x ' . $image_icon_color . '"></i></a>';
                $link =  '<a href="' . $href_route . '" target="_blank" class="position-relative d-inline-block">
                    <i class="fa-solid fa-image fa-2x ' . $image_icon_color . '"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                        ' . $op->photos()->count() . '
                    </span>
                </a>';
            } else {
                $image_icon_color = 'text-muted';
                $href_route = 'javascript:void(0)';
                $link = '<i class="fa-solid fa-image fa-2x ' . $image_icon_color . '"></i>';
            }
            $image = $link;
            return  [
                'id' => $op->id,
                'status' => '<div class="align-middle white-space-wrap fs-9 ps-2">' . $op->status . '</div>',
                'ref_number' => '<div class="align-middle white-space-wrap fs-9 ps-2">' . $op->reference_number . '</div>',
                'image' => '<div class="align-middle white-space-wrap fs-9 px-3">' . $image . '</div>',
                'venue_id' => '<div class="align-middle white-space-wrap fs-9 ps-2">' . $op->venue?->title . '</div>',
                'match_date' => '<div class="align-middle white-space-wrap fs-9 ps-2">' . format_date($op->match_date) . '</div>',
                'event_id' => '<div class="align-middle white-space-wrap fs-9 ps-2">' .  $op->event?->name . '</div>',
                'match_number' => '<div class="align-middle white-space-wrap fs-9 ps-2">' . $op->match?->match_number . '</div>',
                'stage' => '<div class="align-middle white-space-wrap fs-9 ps-2">' . $op->stage . '</div>',
                'final_score' => '<div class="align-middle white-space-wrap fs-9 ps-2">' . $op->final_score . '</div>',
                'match_teams' => '<div class="align-middle white-space-wrap fs-9 ps-2">' . $op->team_a_name . ' vs ' . $op->team_b_name . '</div>',
                'official_attendance' => '<div class="align-middle white-space-wrap fs-9 ps-2">' .  $op->official_attendance . '</div>',
                'reported_by' => '<div class="align-middle white-space-wrap fs-9 ps-2">' .  $op->reportedBy?->name . '</div>',
                'action' => $actions,
                'created_at' => format_date($op->created_at,  'H:i:s'),
                'updated_at' => format_date($op->updated_at, 'H:i:s'),
            ];
        });

        return response()->json([
            "rows" => $ops->items(),
            "total" => $total,
        ]);
    }

    public function switch($id)
    {
        if ($id) {
            if (Event::findOrFail($id)) {
                appLog('Event ID: ' . $id);

                session()->put('EVENT_ID', $id);
                appLog('Event ID: ' . session()->get('EVENT_ID'));
                // return redirect()->route('tracki.project.show.card')->with('message', 'Workspace switched successfully.');
                return redirect()->route('drs.admin.report')->with('message', 'Event Switched.');
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
}
