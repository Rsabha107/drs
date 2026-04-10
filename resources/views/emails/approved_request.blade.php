<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VAPP Request Approved: {{ $vappRequest['request_ref_number'] }}</title>
</head>
<body>
<!-- <h1>VAPP Ref#: {{ $vappRequest['request_ref_number'] }}</h1> -->

Hello {{ $vappRequest['requester_name'] }},<br> 

<p>Good news! Your request {{ $vappRequest['request_ref_number'] }} for a Vehicle Access & Parking Permit (VAPP) has been Approved with the following details:</p>

        &bull; Requested Quantity: <b>{{ $vappRequest['requested_quantity'] }}</b><br>
        &bull; Approved Quantity: <b>{{ $vappRequest['approved_quantity'] }}</b><br>

<p>Our team is now preparing your permit. You will be informed once it is ready for collection.</p>

<p>Thank you,<br>
VAPP Administration Team</p>

</body>
</html>
