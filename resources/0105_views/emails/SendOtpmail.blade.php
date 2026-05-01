<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $data['subject'] ?? 'Verify Your OTP' }}</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            background-color: #ffffff;
            margin: auto;
            border: 3px solid #2a7d3e;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 12px rgba(42, 125, 62, 0.2);
        }

        .header {
            text-align: center;
            padding: 30px;
            background-color: #ffffff;
        }

        .header img {
            width: 180px;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 24px;
            color: #2a7d3e;
        }

        .header p {
            margin: 6px 0 0;
            font-size: 14px;
            color: #666;
        }

        .divider {
            border: 1px solid #d4af37;
            margin: 0;
        }

        .content {
            padding: 30px;
            color: #444;
        }

        .content p {
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 16px;
        }

        .otp-box {
            display: inline-block;
            padding: 14px 26px;
            background: linear-gradient(135deg, #2a7d3e, #8bc34a);
            color: #ffffff;
            border-radius: 8px;
            font-size: 22px;
            letter-spacing: 3px;
            font-weight: 700;
            margin: 20px 0;
        }

        .note {
            font-size: 13px;
            color: #777;
            margin-top: 10px;
        }

        .footer {
            background-color: #f6f6f6;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #555;
        }

        .footer .strong {
            color: #2a7d3e;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- Header -->
        <div class="header">
            <img src="https://www.getdemo.in/Actifab/assets/images/logo.png" alt="ActiFab Logo">
            <h2>OTP Verification</h2>
            <p>{{ config('app.name') ?? 'ActiFab' }}</p>
        </div>

        <hr class="divider">

        <!-- Content -->
        <div class="content">
            <p>Hi {{ $data['contactname'] ?? 'User' }},</p>

            <p>
                Thank you for registering with us. To complete your verification,
                please use the One-Time Password (OTP) below:
            </p>

            <div class="otp-box">{{ $data['otp'] }}</div>

            <p>
                If you did not request this verification, you can safely ignore this email.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            Regards,<br>
            <span class="strong">Team ActiFab</span><br><br>
            <small>
                Need help? Call us at <strong>+91 81560 88203</strong>
            </small>
        </div>

    </div>
</body>

</html>
