<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Payment Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        <h1 style="color: #f87575;">Order Payment Confirmation</h1>
        <p>Dear {{ $order->client->name }},</p>

        <p>We are pleased to confirm that your recent order has been successfully processed, and payment has been received. Thank you for choosing {{ config('app.name') }}!</p>

        <h3>Order Details:</h3>
        <ul style="list-style: none; padding: 0;">
            <li><strong>Order Number:</strong> {{ $order->id_per_vendor }}</li>
            <li><strong>Order Date:</strong> {{ $order->updated_at->format('d.m.Y') }}</li>
            <li><strong>Payment Method:</strong> {{ 'Card' }}</li>
        </ul>

        <p>Your order is now on its way to you.</p>

        <p>If you have any questions or need further assistance regarding your order, please don’t hesitate to contact our customer support team at <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>.</p>

        <p>Thank you once again for trusting us with your purchase. We value your business and look forward to serving you in the future.</p>

        <p>Best regards,<br>
        {{ config('app.name') }} Team</p>
    </div>
</body>
</html>