<?php

namespace App\Http\Controllers\Drs\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Drs\Event;
use App\Models\Drs\VenueMatchReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;


class VenueMatchReportController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('drs.customer.report.list', compact(
            'events'
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

            $actions_pass = '<a href="' . route('drs.report.pdf', $op->id) . '" target="_blank" class="btn p-1"'
                . ' title="Generate Pass">'
                . '<i class="fas fa-passport text-success"></i></a>';

            $edit_actions = '<a href="' . route('drs.report.edit', ['id' => $op->id]) . '" class="btn btn-sm" id="edit_report" data-id="' .
                $op->id .
                '" data-table="report_table" data-bs-toggle="tooltip" data-bs-placement="right" title="Update">' .
                '<i class="fa-solid fa-pen-to-square text-primary"></i></a>';
            $delete_actions =
                '<a href="javascript:void(0)" class="btn btn-sm" data-table="report_table" data-id="' .
                $op->id .
                '" id="deleteReport" data-bs-toggle="tooltip" data-bs-placement="right" title="Delete">' .
                '<i class="bx bx-trash text-danger"></i></a>';
            $upload_img_actions =
                '<a href="javascript:void(0)" class="btn btn-sm" data-table="report_table" data-id="' .
                $op->id .
                '" id="uploadImagesReport" data-bs-toggle="tooltip" data-bs-placement="right" title="Delete">' .
                '<i class="bx bx-arrow-to-top text-success"></i></a>';

            $actions .=  $actions . $actions_pass . $edit_actions . $delete_actions;
            $actions .= '</div>';

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
                'image' => '<div class="align-middle white-space-wrap fs-9 px-3">' . $image,
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
                return redirect()->route('drs.report')->with('message', 'Event Switched.');
                // return back()->with('message', 'Event Switched.');
            }
        }
        //  else {
        // return back()->with('error', 'Workspace not found.');
        // return redirect()->route('tracki.project.show.card')->with('error', 'Workspace not found.');
        // appLog('event_id is null');
        return redirect()->route('drs.report')->with('error', 'Event not found.');
        // }
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
        $pdf = Pdf::loadView('drs.admin.report.rpdf', $data);
        Storage::disk('private')->put('wdr/pdf-exports/' . $wdr->reference_number . '.pdf', $pdf->output());

        return 1;
    }

}
