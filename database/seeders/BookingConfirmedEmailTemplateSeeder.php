<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gms\EmailTemplate;

class BookingConfirmedEmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'key' => 'booking_confirmed',
                'locale' => 'en',
                'name' => 'Booking Confirmed',
                'subject' => 'Booking Confirmed: {booking_ref}',
                'body' => '<p>Hello {user_name},</p><p>Your booking {booking_ref} for {event_name} is confirmed.</p>',
            ],
            [
                'key' => 'booking_confirmed',
                'locale' => 'ar',
                'name' => 'تأكيد الحجز',
                'subject' => 'تم تأكيد الحجز: {booking_ref}',
                'body' => '<p>مرحباً {user_name}،</p><p>تم تأكيد حجزك {booking_ref} لفعالية {event_name}.</p>',
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(
                ['key' => $template['key'], 'locale' => $template['locale']],
                array_merge($template, [
                    'allowed_variables' => ['user_name', 'booking_ref', 'event_name'],
                    'active' => true,
                ])
            );
        }
    }
}
