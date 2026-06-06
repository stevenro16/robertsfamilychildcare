<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; color: #334155; margin: 0; padding: 0; background: #f1f5f9; }
        .wrapper { padding: 40px 16px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .header { background: #3D7222; padding: 28px 32px; }
        .header h1 { margin: 0 0 4px; font-size: 22px; color: white; }
        .header p { margin: 0; font-size: 13px; color: rgba(255,255,255,.75); }
        .body { padding: 32px; }
        .section-label {
            font-size: 11px; font-weight: 700; letter-spacing: .08em;
            text-transform: uppercase; color: #94a3b8;
            margin: 24px 0 10px; padding-bottom: 6px;
            border-bottom: 1px solid #e2e8f0;
        }
        .section-label:first-child { margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        td { padding: 8px 0; font-size: 14px; vertical-align: top; }
        td.label { font-weight: 600; color: #64748b; width: 150px; padding-right: 16px; white-space: nowrap; }
        td.value { color: #1e293b; }
        .message-box {
            background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 8px; padding: 16px;
            font-size: 14px; line-height: 1.7; color: #334155;
            white-space: pre-wrap;
        }
        .cta-wrap { text-align: center; margin: 32px 0 8px; }
        .cta {
            display: inline-block;
            background: #3D7222; color: white !important;
            text-decoration: none; font-weight: 700;
            font-size: 15px; padding: 14px 32px;
            border-radius: 8px; letter-spacing: .01em;
        }
        .cta:hover { background: #2f5a1a; }
        .footer {
            padding: 16px 32px; font-size: 12px; color: #94a3b8;
            background: #f8fafc; border-top: 1px solid #e2e8f0;
            text-align: center;
        }
        a { color: #3D7222; }
    </style>
</head>
<body>
<div class="wrapper">
<div class="container">

    <div class="header">
        <h1>New Inquiry Received</h1>
        <p>Submitted {{ $inquiry->createdAt->format('l, F j, Y \a\t g:i A') }}</p>
    </div>

    <div class="body">

        {{-- Parent --}}
        <div class="section-label">Parent / Guardian</div>
        <table>
            <tr>
                <td class="label">Name</td>
                <td class="value">{{ $inquiry->parentName }}</td>
            </tr>
            @if($inquiry->parentEmail)
            <tr>
                <td class="label">Email</td>
                <td class="value"><a href="mailto:{{ $inquiry->parentEmail }}">{{ $inquiry->parentEmail }}</a></td>
            </tr>
            @endif
            @if($inquiry->parentPhone)
            <tr>
                <td class="label">Phone</td>
                <td class="value"><a href="tel:{{ preg_replace('/\D/', '', $inquiry->parentPhone) }}">{{ $inquiry->parentPhone }}</a></td>
            </tr>
            @endif
        </table>

        {{-- Child --}}
        <div class="section-label">Child Information</div>
        <table>
            @if($inquiry->childName)
            <tr>
                <td class="label">Child's Name</td>
                <td class="value">{{ $inquiry->childName }}</td>
            </tr>
            @endif
            @if($inquiry->childDob)
            <tr>
                <td class="label">Date of Birth</td>
                <td class="value">{{ \Carbon\Carbon::parse($inquiry->childDob)->format('F j, Y') }}</td>
            </tr>
            @endif
            @if($inquiry->desiredStart)
            <tr>
                <td class="label">Desired Start</td>
                <td class="value">{{ \Carbon\Carbon::parse($inquiry->desiredStart)->format('F j, Y') }}</td>
            </tr>
            @endif
            @if($inquiry->programInterest)
            <tr>
                <td class="label">Program Interest</td>
                <td class="value">{{ $inquiry->programInterest }}</td>
            </tr>
            @endif
        </table>

        {{-- How they found us --}}
        @if($inquiry->hearAbout)
        <div class="section-label">How They Found Us</div>
        <p style="font-size:14px; margin: 0 0 0;">{{ $inquiry->hearAbout }}</p>
        @endif

        {{-- Message --}}
        @if($inquiry->message)
        <div class="section-label">Message</div>
        <div class="message-box">{{ $inquiry->message }}</div>
        @endif

        {{-- CTA --}}
        <div class="cta-wrap">
            <a href="{{ route('portal.inquiries.show', $inquiry->id) }}" class="cta">
                View Inquiry in Admin Portal &rarr;
            </a>
        </div>

    </div>

    <div class="footer">
        Roberts Family ChildCare &middot; This is an automated notification.<br>
        <a href="{{ config('app.url') }}">robertsfamilychildcare.com</a>
    </div>

</div>
</div>
</body>
</html>
