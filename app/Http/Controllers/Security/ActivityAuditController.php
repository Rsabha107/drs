<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;

use \OwenIt\Auditing\Models\Audit;

class ActivityAuditController extends Controller
{
    //
    public function index()
    {
        $audit = Audit::all();
        return view('sec.audit.list', compact('audit'));
    }

    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $op = Audit::select('audits.id as id', 'users.email as user_id', 'event', 'auditable_type', 'old_values', 'new_values', 'ip_address', 'audits.created_at', 'audits.updated_at')
            ->join('users', 'audits.user_id', '=',  'users.id')
            ->orderBy($sort, $order);

        // $o = $op->select('old_values')->first();
        // $a = json_encode($op->select('old_values')->first());
        // $b = json_decode($op->select('old_values')->first());
        // dd($o, $a, $b);
        if ($search) {
            $op = $op->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }
        $total = $op->count();


        // dd($op->get());
        $limit = request("limit");
        $limit = max(1, min($limit, 100)); // min=1, max=100
        $op = $op->paginate($limit)->through(function ($op) {

            return [
                'id' => $op->id,
                'id1' => '<div class="ms-3">' . $op->id . '</div>',
                'user_id' => '<div class="align-middle white-space-wrap fw-bold fs-8 ms-3">' . $op->user_id . '</div>',
                'event' => '<div class="align-middle white-space-wrap fw-bold fs-8 ms-3">' . $op->event . '</div>',
                'auditable_type' => '<div class="align-middle white-space-wrap fw-bold fs-8 ms-3">' . $op->auditable_type . '</div>',
                'old_values' => '<div class="align-middle white-space-wrap fw-bold fs-8 ms-3">' . json_encode($op->old_values) . '</div>',
                'new_values' => '<div class="align-middle white-space-wrap fw-bold fs-8 ms-3">' . json_encode($op->new_values, true) . '</div>',
                'ip_address' => '<div class="align-middle white-space-wrap fw-bold fs-8 ms-3">' . $op->ip_address . '</div>',
                // 'total' => '<div class="align-middle white-space-wrap fw-bold fs-8">' . $op->employees->count() . '</div>',
                'created_at' => format_date($op->created_at,  'H:i:s'),
                'updated_at' => format_date($op->updated_at, 'H:i:s'),
            ];
        });

        return response()->json([
            "rows" => $op->items(),
            "total" => $total,
        ]);
    }
}
