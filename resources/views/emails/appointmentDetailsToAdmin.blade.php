{{-- File: resources/views/emails/appointmentDetailsToAdmin.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enquiry Details</title>
</head>
<body>
    <p>Dear Admin,</p>

    <p>Please find below the details of the upcoming Enquiry scheduled for {{ $enquiry->name }}:</p>
    <ul>
        <li><strong>Enquiry Name:</strong> {{ $enquiry->name }}</li>
        <li><strong>Date of Enquiry:</strong> {{ $enquiry->date }}</li>
        <li><strong>Contact Information:</strong> {{ $enquiry->phone }}, {{ $enquiry->email }}</li>
        <li><strong>Adult:</strong> {{ $enquiry->adult }}</li>
        <li><strong>Child:</strong> {{ $enquiry->child }}</li>
        <li><strong>Room:</strong> {{ $enquiry->room_type }}</li>
        {{--  <li><strong>Package Name:</strong> {{ $enquiry->package_name }}</li>  --}}
    </ul>

    <p>Please ensure that the necessary preparations are made to accommodate this Enquiry effectively. Should there be any changes or additional requirements, I will update you accordingly.</p>

    <p>Thank you for your attention to this matter.</p>

    <p>Best regards,</p>
    <p>Afro Asian Travel<br>
        booking@afroasiantravel.com</p>
</body>
</html>
