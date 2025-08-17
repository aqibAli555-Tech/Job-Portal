<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta name="robots" content="noindex,nofollow"/>
</head>
<body>
<div class="body" style="width: 100%; ">
    <table>
        <tr>
            <td style="padding-left: 20px;padding-right:20px;padding-top:20px;padding-bottom:10px">
                {{ $data['user_data']->name }} (ID: {{ $data['user_data']->id }}) attempted a Tap payment, but below required element was missing:
            </td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">
                    <strong>Token ID:</strong> Missing <br>

                <br><br>
                    <strong>URL:</strong> {{ $data['url'] }} <br>
                <br><br>

                Best regards, <br>
                <strong>The Hungry For Jobs Team</strong> 🍔
            </td>

        </tr>
    </table>
</div>
</body>
</html>