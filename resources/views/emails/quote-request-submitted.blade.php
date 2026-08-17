<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New quote request</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1a1a1a; line-height: 1.5;">
    <h1 style="font-size: 20px;">New quote request</h1>
    <p>A visitor submitted the Get a quote form.</p>

    <table cellpadding="6" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <td style="font-weight: bold;">Full name</td>
            <td>{{ $quoteRequest->full_name }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Email</td>
            <td>{{ $quoteRequest->email }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Phone</td>
            <td>{{ $quoteRequest->phone ?: '—' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Company</td>
            <td>{{ $quoteRequest->company ?: '—' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Vessel / reference</td>
            <td>{{ $quoteRequest->vessel_or_reference ?: '—' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Timeline</td>
            <td>{{ $quoteRequest->timeline ?: '—' }}</td>
        </tr>
    </table>

    <h2 style="font-size: 16px; margin-top: 24px;">What they need quoted</h2>
    <p style="white-space: pre-wrap;">{{ $quoteRequest->request_details }}</p>

    <p>
        <a href="{{ route('admin.quote-requests.show', $quoteRequest) }}">
            View in admin
        </a>
    </p>
</body>
</html>
