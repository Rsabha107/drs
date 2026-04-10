<?php

namespace App\Http\Controllers\Drs\Customer;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class VmsReportController extends Controller
{
    public function create()
    {
        // if you want to pass defaults, do it here
        return view('reports.create', [
            'today' => now()->toDateString(),
            'time'  => now()->format('H:i'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'matchNumber' => ['required','string','max:100'],
            'date' => ['nullable','date'],
            'time' => ['required','regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/'],

            'finalScore' => ['required','string','max:20'],
            'venueManagerName' => ['required','string','max:255'],

            // photos (max 10)
            'photos' => ['nullable','array','max:10'],
            'photos.*' => ['image','max:5120'], // 5MB each
        ]);

        // TODO: save to DB + generate PDF + store uploads, etc.

        return redirect()->route('reports.create')->with('success', 'Report created successfully.');
    }

    public function saveDraft(Request $request)
    {
        // minimal validation for draft (can be lighter)
        $payload = $request->all();

        // TODO: store draft in DB or session
        return response()->json(['ok' => true]);
    }
}
