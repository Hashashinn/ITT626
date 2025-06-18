<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8" />
    <title>Altus Attendance – Exceed Time Off Duration</title>
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
                            <h1>Altus Attendance – Half Day Leave</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px;">
                            <p>Dear Sir/Madam,</p>

                            <p>Please apply for half-day leave as you have exceeded the time off duration. The details
                                are as follows:</p>

                            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                                <tr>
                                    <th
                                        style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc; font-weight: bold; color: #007bff;">
                                        Name</th>
                                    <td style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc;">:</td>
                                    <td style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc;">
                                        {{ $data['data']->user->name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc;">Start
                                        time off</th>
                                    <td style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc;">:</td>
                                    <td style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc;">
                                        {{ $data['data']->timeoff_start ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc;">End time
                                        off</th>
                                    <td style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc;">:</td>
                                    <td style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc;">
                                        {{ $data['data']->timeoff_end ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc;">Latitude
                                    </th>
                                    <td style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc;">:</td>
                                    <td style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc;">
                                        {{ $data['data']->lat ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc;">Longitude
                                    </th>
                                    <td style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc;">:</td>
                                    <td style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc;">
                                        {{ $data['data']->lng ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc;">Date</th>
                                    <td style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc;">:</td>
                                    <td style="padding: 8px; text-align: left; border-bottom: 1px solid #ccc;">
                                        {{ $data['data']->created_at ?? '' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="padding: 40px; background-color: #f2f2f2; font-size: 14px; color: #888; border-bottom-left-radius: 4px; border-bottom-right-radius: 4px;">
                            <p>Thank you.<br>Attendance System Administrator</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>
