<!DOCTYPE html>
<html>
<head>
    <title>VMS Account Creation</title>
</head>
<body>
   Dear {{ $details['name'] }},<br> 
 <p>A new user account has been created for you in the VMS System.<br>
You can now access the system using the following details:</p>

    <ul>
        <li>Username: {{ $details['email'] }}</li>
        <li>Password: {{ $details['password'] }}</li>
    </ul>

    <p>To get started, please log in to your account using the link below:</p>
    <p><a href="https://drs.sc.qa">Log In to Your VMS System Account</a></p>
    <p>If you encounter any issues logging in or need assistance, please contact the VMS Support Team at wdrsys@scqa0.onmicrosoft.com.</p>
    <p>Best regards,<br>VMS Support Team</p>
</body>
</html>
