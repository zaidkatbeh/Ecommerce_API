<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Verification</title>
</head>
<body>
<table style="width: 100%; background-color: #f5f5f5; padding: 20px;">
    <tr>
        <td style="text-align: center; padding: 20px;">
            <h2>Account Verification</h2>
        </td>
    </tr>
    <tr>
        <td style="padding: 20px;">
            <p>Hello {{ $userName }},</p>
            <p>Thank you for registering an account with us. To complete your registration, please use the following verification code:</p>
            <p style="font-size: 24px; font-weight: bold;">{{ $verificationCode }}</p>
            <p>This code will verify your account and grant you access to our services.</p>
            <p>If you didn't request this code, please ignore this email. Your account will not be verified until you use this code.</p>
        </td>
    </tr>
    <tr>
        <td style="text-align: center; padding: 20px;">
            <p>Thank you for choosing our platform!</p>
        </td>
    </tr>
</table>
</body>
</html>
