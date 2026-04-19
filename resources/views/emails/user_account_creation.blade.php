<!DOCTYPE html>
<html>
<head>
    <title>DRS Account Creation</title>
</head>
<body>
   Dear {{ $details['name'] }},<br> 
 <p>A new user account has been created for you in the DRS System.<br>
You can now access the system using the following details:</p>

    <ul>
        <li>Username: {{ $details['email'] }}</li>
        <li>Password: {{ $details['password'] }}</li>
    </ul>

    <p>To get started, please log in to your account using the link below:</p>
    <p><a href="https://drs.sc.qa">Log In to Your DRS System Account</a></p>
    <p>If you encounter any issues logging in or need assistance, please contact the DRS Support Team at drs@sc.qa.</p>
    <p>Best regards,<br>DRS Support Team</p>
</body>
</html>
