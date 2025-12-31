<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #fff; padding: 30px; border: 1px solid #e5e7eb; }
        .message-box { background: #f9fafb; padding: 15px; border-left: 4px solid #f97316; margin: 20px 0; border-radius: 4px; }
        .original-message { background: #fef3c7; padding: 15px; border-left: 4px solid #f59e0b; margin: 20px 0; border-radius: 4px; }
        .footer { background: #1f2937; color: #9ca3af; text-align: center; padding: 15px; border-radius: 0 0 8px 8px; font-size: 12px; }
        .signature { margin-top: 30px; padding-top: 20px; border-top: 2px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">🍽️ Food Court</h1>
            <p style="margin: 5px 0 0 0;">Customer Support Reply</p>
        </div>
        
        <div class="content">
            <p style="font-size: 16px;">Dear {{ $contactMessage->name }},</p>
            
            <p>Thank you for contacting Food Court. We have received your message and here's our response:</p>
            
            <div class="message-box">
                <strong style="color: #f97316;">Our Reply:</strong>
                <p style="margin-top: 10px; white-space: pre-wrap;">{{ $replyText }}</p>
            </div>
            
            <div class="original-message">
                <strong style="color: #f59e0b;">Your Original Message:</strong>
                <p style="margin-top: 10px; color: #6b7280;">{{ $contactMessage->message }}</p>
                <p style="margin-top: 10px; font-size: 12px; color: #9ca3af;">
                    Sent on: {{ $contactMessage->created_at->format('d M Y, h:i A') }}
                </p>
            </div>
            
            <p>If you have any further questions or concerns, please don't hesitate to reach out to us.</p>
            
            <div class="signature">
                <p style="margin: 0;"><strong>Best regards,</strong></p>
                <p style="margin: 5px 0;"><strong>Food Court Team</strong></p>
                <p style="margin: 5px 0; color: #6b7280; font-size: 14px;">
                    📧 Email: info@foodcourt.com<br>
                    📞 Phone: +91 98765 43210<br>
                    📍 Address: 123 Food Street, City Center, New Delhi
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p style="margin: 0;">Food Court Restaurant Management System</p>
            <p style="margin: 5px 0 0 0;">© {{ date('Y') }} Food Court. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
