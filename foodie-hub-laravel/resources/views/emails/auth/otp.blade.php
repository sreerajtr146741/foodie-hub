<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - Food Court</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Arial', sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); padding: 40px 20px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 32px; font-weight: bold;">Food Court</h1>
                            <p style="color: #ffffff; margin: 10px 0 0 0; font-size: 16px; opacity: 0.9;">Welcome to Food Court</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #1f2937; margin: 0 0 20px 0; font-size: 24px;">Verify Your Email Address</h2>
                            
                            <p style="color: #4b5563; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
                                Thank you for registering with Food Court! To complete your registration, please use the verification code below:
                            </p>
                            
                            <!-- OTP Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border: 3px dashed #f59e0b; border-radius: 12px; padding: 30px; display: inline-block;">
                                            <p style="color: #92400e; font-size: 14px; margin: 0 0 10px 0; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Your Verification Code</p>
                                            <h1 style="color: #ea580c; font-size: 48px; margin: 0; letter-spacing: 8px; font-weight: bold; font-family: 'Courier New', monospace;">{{ $otp }}</h1>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            
                            <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px 20px; margin: 20px 0; border-radius: 4px;">
                                <p style="color: #92400e; font-size: 14px; margin: 0; line-height: 1.5;">
                                    ⏰ <strong>Important:</strong> This verification code will expire in <strong>10 minutes</strong> for your security.
                                </p>
                            </div>
                            
                            <p style="color: #6b7280; font-size: 14px; line-height: 1.6; margin: 20px 0 0 0;">
                                If you didn't create an account with Food Court, please ignore this email. No further action is required.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="color: #6b7280; font-size: 14px; margin: 0 0 10px 0;">
                                Need help? Contact us at <a href="mailto:support@foodcourt.com" style="color: #f97316; text-decoration: none;">support@foodcourt.com</a>
                            </p>
                            <p style="color: #9ca3af; font-size: 12px; margin: 10px 0 0 0;">
                                © {{ date('Y') }} Food Court. All rights reserved.
                            </p>
                            <p style="color: #9ca3af; font-size: 11px; margin: 10px 0 0 0;">
                                This is an automated message, please do not reply to this email.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
