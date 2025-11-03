<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Account is Ready</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family: Arial, sans-serif; color:#333;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8; padding:40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.1);">

                    <!-- Header -->
                    <tr>
                        <td style="background-color:#0d6efd; padding:20px; text-align:center;">
                            <h1 style="color:#ffffff; margin:0; font-size:22px; font-weight:600;">Welcome to TaxBridge</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px;">
                            <p style="font-size:16px; margin-bottom:15px;">
                                Hello <strong>{{ $name }}</strong>,
                            </p>

                            <p style="font-size:15px; line-height:1.6; margin-bottom:20px;">
                                Your account has been successfully created and activated.
                            </p>

                            <p style="font-size:15px; line-height:1.6; margin-bottom:20px;">
                                You can log in using the email address below:
                            </p>

                            <table cellpadding="8" cellspacing="0" width="100%" style="background-color:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; margin-bottom:20px;">
                                <tr>
                                    <td style="font-size:14px; width:120px;"><strong>Email:</strong></td>
                                    <td style="font-size:14px;">{{ $email }}</td>
                                </tr>
                            </table>

                            <p style="font-size:15px; line-height:1.6; margin-bottom:25px;">
                                For security reasons, we do not send passwords via email.  
                                To set your password, please click “Forgot Password” on the login page and follow the instructions.
                            </p>

                            <p style="text-align:center; margin-bottom:30px;">
                                <a href="{{ $loginUrl }}"
                                   style="background-color:#0d6efd; color:#ffffff; text-decoration:none;
                                          padding:12px 24px; border-radius:6px; display:inline-block;
                                          font-weight:600; font-size:15px;">
                                    🔐 Go to Login Page
                                </a>
                            </p>

                            <p style="font-size:14px; color:#555;">
                                If you need any assistance, feel free to reach out to us.
                            </p>

                            <p style="font-size:14px; color:#555; margin-top:30px;">
                                Best regards,<br>
                                <strong>TaxBridge Team</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color:#f0f2f5; text-align:center; padding:15px; font-size:12px; color:#888;">
                            © {{ date('Y') }} TaxBridge. All rights reserved.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
