<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8" />
</head>

<body style="font-family: Arial, sans-serif; background-color: #f2f2f2; margin: 0; padding: 0;">
    <table style="width: 100%; height: 100vh; table-layout: fixed; background-color: #f2f2f2;">
        <tr>
            <td align="center" valign="middle">
                <table style="max-width: 600px; background-color: #fff; border-radius: 4px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
                    <tr>
                        <td style="padding: 20px; background-color: #007bff; color: #fff; font-size: 15px; font-weight: bold; border-top-left-radius: 4px; border-top-right-radius: 4px;">
                            <h1>Altus Attendance – Registration</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px; font-size: 16px; line-height: 1.6;">
                            <p>Dear Sir/Madam,</p>

                            <table style="width: 100%; margin-top: 20px; border-collapse: collapse;">
                                <tr>
                                    <th style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc; font-weight: bold; color: #007bff;">Your Login Details:</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc;">Username</td>
                                    <td>:</td>
                                    <td style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc;">{{ $data['data']['email'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc;">Password</td>
                                    <td>:</td>
                                    <td style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc;">{{ $data['data']['password'] ?? '-' }}</td>
                                </tr>
                            </table>

                            <p>Please log in using the provided login details and change your password immediately.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px; background-color: #f2f2f2; font-size: 14px; color: #888; border-bottom-left-radius: 4px; border-bottom-right-radius: 4px;">
                            <p>Thank you for your attention.</p>
                            <p>Attendance System Administrator</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
