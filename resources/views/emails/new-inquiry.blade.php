<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; color: #334155; margin: 0; padding: 0; background: #f8fafc; }
        .container { max-width: 600px; margin: 40px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
        .header { background: #3D7222; color: white; padding: 24px 32px; }
        .header h1 { margin: 0; font-size: 20px; }
        .body { padding: 32px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        td { padding: 10px 0; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        td:first-child { font-weight: 600; color: #64748b; width: 130px; }
        .message-box { background: #f8fafc; border-radius: 8px; padding: 16px; font-size: 14px; line-height: 1.6; }
        .footer { padding: 16px 32px; font-size: 12px; color: #94a3b8; background: #f8fafc; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Inquiry Received</h1>
        </div>
        <div class="body">
            <p style="margin-top: 0;">A new inquiry has been submitted on Roberts Family ChildCare.</p>

            <table>
                <tr><td>Name</td><td>{{ $inquiry->parentName }}</td></tr>
                @if($inquiry->parentEmail)
                <tr><td>Email</td><td><a href="mailto:{{ $inquiry->parentEmail }}">{{ $inquiry->parentEmail }}</a></td></tr>
                @endif
                @if($inquiry->parentPhone)
                <tr><td>Phone</td><td>{{ $inquiry->parentPhone }}</td></tr>
                @endif
                @if($inquiry->childDob)
                <tr><td>Child DOB</td><td>{{ \Carbon\Carbon::parse($inquiry->childDob)->format('M j, Y') }}</td></tr>
                @endif
                <tr><td>Submitted</td><td>{{ $inquiry->createdAt->format('M j, Y g:i A') }}</td></tr>
            </table>

            @if($inquiry->message)
            <p style="font-weight: 600; color: #64748b; font-size: 14px; margin-bottom: 8px;">Message</p>
            <div class="message-box">{{ $inquiry->message }}</div>
            @endif
        </div>
        <div class="footer">
            Roberts Family ChildCare &middot; This is an automated notification.
        </div>
    </div>
</body>
</html>
