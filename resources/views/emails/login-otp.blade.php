<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your FEC sign-in code</title>
</head>
<body style="margin: 0; padding: 32px 16px; background-color: #f3f5f8; color: #233044; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 560px; border-collapse: collapse; background-color: #ffffff; border-radius: 12px;">
                    <tr>
                        <td align="center" style="padding: 32px 24px 20px;">
                            <img src="{{ $logoUrl }}" alt="FEC International School" width="112" height="112" style="display: block; width: 112px; height: auto; border: 0;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0 32px 32px; font-size: 16px; line-height: 1.6;">
                            <h1 style="margin: 0 0 20px; color: #173b67; font-size: 24px; text-align: center;">Your FEC sign-in code</h1>
                            <p>Hello {{ $name }},</p>
                            <p>Use this one-time code to finish signing in:</p>
                            <p style="margin: 24px 0; text-align: center;">
                                <span style="display: inline-block; padding: 12px 24px; border-radius: 8px; background-color: #f0f5fb; color: #173b67; font-size: 30px; font-weight: bold; letter-spacing: 8px;">{{ $code }}</span>
                            </p>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin: 20px 0; border-collapse: collapse; background-color: #fff7e6; border-left: 4px solid #e49b0f;">
                                <tr>
                                    <td style="padding: 12px 16px; color: #704b00; font-size: 15px;">
                                        <strong>Expires in <span style="padding: 2px 6px; border-radius: 4px; background-color: #ffe3a3; color: #553900;">{{ $expiresInMinutes }} minutes</span></strong>
                                        <br>This code can only be used once.
                                    </td>
                                </tr>
                            </table>
                            <p>If you did not try to sign in, you can ignore this email.</p>
                            <p style="margin: 28px 0 0; color: #667085; font-size: 14px;">FEC International School</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
