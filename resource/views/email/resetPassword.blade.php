<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8" />
</head>

<body style="font-family: Arial, sans-serif; background-color: #f2f2f2; margin: 0; padding: 0;">
    <table style="width: 100%; height: 50vh; table-layout: fixed; background-color: #f2f2f2;">
        <tr>
            <td align="center" valign="middle">
                <table
                    style="max-width: 600px; background-color: #fff; border-radius: 4px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
                    <tr>
                        <td
                            style="padding: 10px 20px; background-color: #007bff; color: #fff !important; font-size: 15px; font-weight: bold; border-top-left-radius: 4px; border-top-right-radius: 4px;">
                            <h1>Altus Attendance – Reset Password</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px;">
                            <p>Dear Sir/Madam,</p>

                            <p>
                                To reset your password, please click the following link:<br>
                                <a href="{{ env('APP_URL') }}/resetPasswordView/{{ $data['data']['token'] ?? '' }}"
                                    style="color: #007bff; text-decoration: none;">Reset Password</a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="padding: 40px; background-color: #f2f2f2; font-size: 12px; color: #888; border-bottom-left-radius: 4px; border-bottom-right-radius: 4px;">
                            <p>Thank you.<br>Attendance System Administrator</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
