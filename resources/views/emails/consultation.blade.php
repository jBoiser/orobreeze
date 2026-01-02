<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #1a202c; margin: 0; padding: 0; background-color: #f7fafc; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .header { background-color: #062a4d; padding: 30px; text-align: center; border-bottom: 4px solid #f7c948; }
        .content { padding: 40px; }
        .lead-info { background-color: #f8fafc; border-left: 4px solid #00b5d8; padding: 20px; margin-bottom: 30px; border-radius: 0 8px 8px 0; }
        .label { font-size: 12px; font-weight: bold; color: #718096; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; display: block; }
        .value { font-size: 16px; color: #2d3748; font-weight: 500; }
        .message-box { background: #ffffff; border: 1px dashed #cbd5e0; padding: 20px; border-radius: 8px; margin-top: 10px; font-style: italic; color: #4a5568; }
        .footer { background: #edf2f7; padding: 20px; text-align: center; font-size: 12px; color: #a0aec0; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #00b5d8; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #f7c948; margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 2px;">New Consultation Request</h1>
        </div>

        <div class="content">
            <h2 style="margin-top: 0; color: #062a4d;">A new client is interested!</h2>
            <p>You received a new inquiry from your coming soon page.</p>

            <div class="lead-info">
                <span class="label">Client Email</span>
                <div class="value">{{ $email }}</div>
            </div>

            <span class="label">Request Details</span>
            <div class="message-box">
                "{{ $content }}"
            </div>

            <a href="mailto:{{ $email }}" class="btn">Reply to Client</a>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} OroBreeze Airconditioning Services.<br>
            Innovating Air Comfort for Homes and Businesses.
        </div>
    </div>
</body>
</html>