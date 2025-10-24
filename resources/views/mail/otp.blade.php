<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login OTP Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding: 10px 0;
            background-color: #007bff;
            color: #ffffff;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            margin: 20px 0;
            text-align: center;
        }

        .otp-code {
            display: inline-block;
            background-color: #f1f1f1;
            padding: 10px 20px;
            font-size: 24px;
            letter-spacing: 2px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777777;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Login OTP Code</h1>
        </div>
        <div class="content">
            <p>Hello {{ $user->getName() }},</p>
            <p>Use the following One-Time Password (OTP) to log in to your account:</p>
            <div class="otp-code">{{ $user->otp }}</div>
            <p>This OTP is valid for the next 10 minutes.</p>
            <p>If you did not request this code, please secure your account by resetting your password.</p>
        </div>

    </div>
</body>

</html>
