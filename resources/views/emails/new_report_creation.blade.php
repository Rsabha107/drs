<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VMS Confirmation for a New Report: {{ $details['reference_number'] }}</title>
</head>
<body>
<h1>VMS Ref#: {{ $details['reference_number'] }}</h1>

Dear Team,<br> 

The Daily Workforce Operations Report for {{ $details['venue'] }} for the {{ $details['event'] }} on {{ $details['report_date'] }} has been successfully submitted.<br>

Please find the report attached to this email for your reference.<br>

<p>Kind Regards,<br>
VMS team </p>

</body>
</html>
