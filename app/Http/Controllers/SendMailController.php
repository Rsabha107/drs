<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Mail\MdsNewBookingMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SampleMail;
use App\Mail\TaskAssignmentMail;
use App\Models\User;

use Illuminate\Support\Facades\Notification;

use App\Notifications\AnnouncementCenter;
use App\Services\TemplatedEmailService;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Auth;

class SendMailController extends Controller
{
    //
    public function testDynamicEmail(TemplatedEmailService $templatedEmailService)
    {
        $user = Auth::user();
        $user->locale = 'en';

        $payload = [
            'user_name' => $user->name,
            'booking_ref' => 'BK-123456',
            'event_name' => 'Annual Gala Dinner',
        ];

        $status = $templatedEmailService->sendNow(
            'booking_confirmed', 
            $payload, [
            'to' => $user->email,
            // 'cc' => ['ops@yourdomain.com'],
            ],
            'ar'
        );

        return back()->with([
            'message' => $status['message'] ?? 'Test email sent.',
            'alert-type' => $status['status'] ? 'success' : 'error',
        ]);
    }
 
}
