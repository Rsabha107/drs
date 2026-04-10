<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VAPP Confirmation for a New Request: {{ $vappRequest['request_ref_number'] }}</title>
</head>
<body>
<!-- <h1>VAPP Ref#: {{ $vappRequest['request_ref_number'] }}</h1> -->

Hello {{ $vappRequest['requester_name'] }},<br> 

<p>We have received your request for a Vehicle Access & Parking Permit (VAPP).<br />
Your request {{ $vappRequest['request_ref_number'] }} is currently In-Progress and is being reviewed by our VAPP team:</p>

<p>Kindly note that if any modifications or changes are needed for a confirmed booking (Venue, Driver, or Vehicle), you will need to submit a Modify Booking Request before 17:00 for the next day.</p>

        &bull; Parking Code: <b>{{ $vappRequest['parking_code'] }}</b><br>
        &bull; Match: <b>{{ $vappRequest['match'] }}</b><br>
        &bull; Venue: <b>{{ $vappRequest['venue'] }}</b><br>
        &bull; Size: <b>{{ $vappRequest['vapp_size'] }}</b><br>
        &bull; Requested Quantity: <b>{{ $vappRequest['requested_quantity'] }}</b><br>

<p>We will notify you once the review is complete.<br />
On behalf of the VAPP Team, we appreciate your use of our system and wish you a wonderful day ahead.</p>

<p>Thank you,<br>
VAPP Administration Team</p>

</body>
</html>
