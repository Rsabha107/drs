<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VAPP Request Rejected: {{ $vappRequest['request_ref_number'] }}</title>
</head>
<body>
<!-- <h1>VAPP Ref#: {{ $vappRequest['request_ref_number'] }}</h1> -->

Hello {{ $vappRequest['requester_name'] }},<br> 

<p>We regret to inform you that your request {{ $vappRequest['request_ref_number'] }} for a Vehicle Access & Parking Permit (VAPP) has been Rejected</p>

<p>If you believe this decision was made in error or if you wish to resubmit your request, please contact the <a href="mailto:vappsys@scqa0.onmicrosoft.com">VAPP Team</a></p>

<p>Thank you for your understanding,<br>
VAPP Administration Team</p>

</body>
</html>
