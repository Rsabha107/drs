<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VAPP Ready for Collection: {{ $vappRequest['request_ref_number'] }}</title>
</head>
<body>
<!-- <h1>VAPP Ref#: {{ $vappRequest['request_ref_number'] }}</h1> -->

Hello {{ $vappRequest['requester_name'] }},<br> 

<p>Your Vehicle Access s & Parking Permit (VAPP) is now Ready for Collection</p>

<p>📍 Collection Location: <b>{{ $vappRequest['collection_location'] }}</b></p>
<p>🕒 Collection Time: <b>{{ $vappRequest['collection_time'] }}</b></p>

<p>Please present the attached QR code along with your email at the collection point</p>

<p>Thank you,<br>
VAPP Administration Team</p>

</body>
</html>
