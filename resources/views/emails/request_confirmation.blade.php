<!DOCTYPE html>
<html>
<head>
    <title>Request Confirmation</title>
</head>
<body>
    <h2>Hi {{ $vappRequest->functional_area?->focal_point }},</h2>
    <p>Your request has been submitted successfully.</p>

    <p><strong>Reference Number:</strong> {{ $vappRequest->ref_number }}</p>

    <p>Use the attached QR code for collection:</p>
    {{-- <img src="{{ $qrBase64 }}" alt="QR Code"> --}}

    <p>Thank you!</p>
</body>
</html>