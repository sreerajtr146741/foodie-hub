<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #fff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; }
        .field { margin: 15px 0; padding: 10px; background: #f9fafb; border-radius: 4px; }
        .label { font-weight: bold; color: #6b7280; font-size: 12px; text-transform: uppercase; }
        .value { color: #111827; margin-top: 5px; }
        .footer { background: #1f2937; color: #9ca3af; text-align: center; padding: 15px; border-radius: 0 0 8px 8px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">🍽️ Food Court</h1>
            <p style="margin: 5px 0 0 0;">New Contact Form Submission</p>
        </div>
        
        <div class="content">
            <p style="font-size: 16px; color: #374151;">You have received a new message from the contact form:</p>
            
            <div class="field">
                <div class="label">Name</div>
                <div class="value">{{ $contact->name }}</div>
            </div>
            
            <div class="field">
                <div class="label">Email</div>
                <div class="value">{{ $contact->email }}</div>
            </div>
            
            <div class="field">
                <div class="label">Phone</div>
                <div class="value">{{ $contact->phone }}</div>
            </div>
            
            <div class="field">
                <div class="label">Message</div>
                <div class="value">{{ $contact->message }}</div>
            </div>
            
            <p style="margin-top: 20px; padding: 15px; background: #fef3c7; border-left: 4px solid #f59e0b; font-size: 14px;">
                <strong>⏰ Submitted:</strong> {{ $contact->created_at->format('d M Y, h:i A') }}
            </p>
        </div>
        
        <div class="footer">
            <p style="margin: 0;">Food Court Restaurant Management System</p>
            <p style="margin: 5px 0 0 0;">© {{ date('Y') }} Food Court. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
