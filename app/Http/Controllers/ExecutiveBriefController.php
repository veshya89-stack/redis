<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExecutiveBriefController extends Controller
{
    public function index(Request $request)
    {
        $meetings = Meeting::orderByDesc('tanggal')->get();

        $meetingId = $request->query('meeting_id', $meetings->first()?->id);
        $selectedMeeting = null;
        $initiatives = collect();

        if ($meetingId) {
            $selectedMeeting = Meeting::with('strategicInitiatives.actionPlans')->find($meetingId);
            $initiatives = $selectedMeeting?->strategicInitiatives ?? collect();
        }

        return view('executive-brief', [
            'meetings' => $meetings,
            'selectedMeeting' => $selectedMeeting,
            'initiatives' => $initiatives,
        ]);
    }

    public function pdf(Meeting $meeting)
    {
        $meeting->load('strategicInitiatives.actionPlans');

        $pdf = Pdf::loadView('executive-brief-pdf', [
            'meeting' => $meeting,
            'initiatives' => $meeting->strategicInitiatives,
        ])->setPaper('a4', 'portrait');

        $fileName = 'Executive-Brief-'.str($meeting->judul)->slug().'.pdf';

        return $pdf->download($fileName);
    }
}
