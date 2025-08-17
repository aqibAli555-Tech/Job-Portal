@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width: 100%; ">
    <table>
        <tr>
            <td style="padding-left: 20px;padding-right:20px;padding-top:20px;padding-bottom:10px">
                {{ $user_data->name }} (ID: {{ $user_data->id }}) attempted a Tap payment, but one or more required elements were missing:
            </td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">
                @if (!empty($data->card->id))
                    <strong>Card ID:</strong> {{ $data->card->id }} <br>
                @else
                    <strong>Card ID:</strong> Missing <br>
                @endif

                @if (!empty($data->customer->id))
                    <strong>Tap Customer ID:</strong> {{ $data->customer->id }} <br>
                @else
                    <strong>Tap Customer ID:</strong> Missing <br>
                @endif

                @if (!empty($data->payment_agreement->id))
                    <strong>Tap Agreement ID:</strong> {{ $data->payment_agreement->id }} <br>
                @else
                    <strong>Tap Agreement ID:</strong> Missing <br>
                @endif

                <br><br>

                Best regards, <br>
                <strong>The Hungry For Jobs Team</strong> 🍔
            </td>

        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])