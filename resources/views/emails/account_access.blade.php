<!DOCTYPE html>
<html>

<head>
    <title>Access Granted for DRS System</title>
</head>

<body>
    Dear {{ $details['name'] }},<br>
    <p>Your request for access to the DRS System has been approved.<br>
    <ul>
        <li>Event: {{ $details['event'] }}</li>
        <li>Venue: {{ $details['venue'] }}</li>
        <li>Functional Area: {{ $details['functional_area'] }}</li>
    </ul>
    <p>You can now access the system using your existing credentials at: <br>
        <a href="{{ $details['url'] }}">Log In to Your DRS System Account</a>
    </p>


    <p>If you encounter any issues logging in or need assistance, please contact the DRS Support Team at drs@sc.qa.</p>
    <p>Best regards,<br>DRS Support Team</p>
</body>

</html>
