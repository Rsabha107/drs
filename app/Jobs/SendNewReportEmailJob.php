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
        //         MAIL_MAILER=smtp
        // MAIL_HOST=smtp.gmail.com
        // MAIL_PORT=465
        // MAIL_USERNAME=rsabha@gmail.com
        // MAIL_PASSWORD=eploqntpgghoiofx
        // MAIL_ENCRYPTION=tls
        // MAIL_FROM_ADDRESS=pd_tracki@gmail.com
        // MAIL_FROM_NAME="Tracki"
        // $mail_config = Cache::remember('mail_config', 60*60, function () {
        //     return MailConfig::first();
        // });

        // Log::info('Mail Config: ', [
        //     'transport' => $mail_config->transport,
        //     'host' => $mail_config->host,
        //     'port' => $mail_config->port,
        //     'from_address' => $mail_config->from_address,
        //     'from_name' => $mail_config->from_name,
        //     'encryption' => $mail_config->encryption,
        //     'username' => $mail_config->username,
        // ]);

        // $mailer = Mail::build([
        //     'transport' => $mail_config->transport,// 'smtp',
        //     'host' => $mail_config->host, //'smtp.gmail.com',
        //     'port' => $mail_config->port, //'465',
        //     'from' => [
        //         'address' => $mail_config->from_address, // 'mds@example.com',
        //         'name' => $mail_config->from_name, //'MDS',
        //     ],
        //     'encryption' => $mail_config->encryption, //'tls',
        //     'username' => $mail_config->username, // 'rsabha@gmail.com',
        //     'password' => $mail_config->password, // 'eploqntpgghoiofx',
        // ]);

        // $mailer->to($this->details['email'])
        //        ->send(new MdsNewBookingMail($this->details));
        Mail::to('workforcecc@sc.qa')
            ->bcc('r.sabha@sc.qa')
            ->send(new NewReportMail($this->details));
    }
}
