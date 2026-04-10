<?php

return [
    'send_emails' => env('SEND_EMAIL', false),
    'cut_off_time' => env('CUT_OFF_TIME', '17'),
    'gantt_event_color' => 'green',
    'gantt_task_color' => 'orange',
    'gantt_text_color' => 'white',
    'gantt_progress_color' => 'orange',

    'show_task_progress' => false,
    'project_strict_save' => false,
    'show_project_status_field' => false,
    'check_event_selection' => true,
    'check_event_selection' => env('MULTI_EVENT', true),
];
