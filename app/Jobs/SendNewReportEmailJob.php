<?php

namespace App\Jobs;

use App\Mail\MdsNewBookingMail;
use App\Mail\NewReportMail;
use App\Models\GeneralSettings\MailConfig;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNewReportEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function handle()
    {

        Mail::to('workforcecc@sc.qa')
            ->bcc('r.sabha@sc.qa')
            ->send(new NewReportMail($this->details));
    }
}
