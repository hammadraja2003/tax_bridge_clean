<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Successful - Pending Review</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family:Arial, sans-serif; color:#333;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8; padding:40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color:#0d6efd; padding:20px; text-align:center;">
                            <h1 style="color:#ffffff; margin:0; font-size:22px; font-weight:600;">
                                Welcome to TaxBridge
                            </h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px;">
                            <p style="font-size:16px; margin-bottom:15px;">Hello <strong>{{ $name }}</strong>,</p>

                            <p style="font-size:15px; line-height:1.6; margin-bottom:20px;">
                                Thank you for registering on <strong>TaxBridge</strong>.  
                                We’ve successfully received your submission.
                            </p>

                            <p style="font-size:15px; line-height:1.6; margin-bottom:20px;">
                                Our technical team is currently reviewing your registration details.  
                                This process may take up to <strong>24 hours</strong>.
                                Once your account is approved and activated, you will receive another
                                email with your login details.
                            </p>

                            <p style="font-size:15px; line-height:1.6; margin-bottom:25px;">
                                In the meantime, feel free to explore our platform and learn how we can help you streamline your tax processes.
                            </p>

                            <p style="font-size:15px; margin-top:20px; margin-bottom:10px;">
                                ✅ <strong>No action is required on your side right now</strong>
                            </p>

                            <p style="font-size:15px; margin-bottom:30px;">
                                If you have any questions, please reach out to us at:  
                                <a href="mailto:info@taxbridge.pk" style="color:#0d6efd; text-decoration:none;">
                                    info@taxbridge.pk
                                </a>
                            </p>

                            <p style="font-size:14px; color:#555;">
                                Thank you for choosing TaxBridge.
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
