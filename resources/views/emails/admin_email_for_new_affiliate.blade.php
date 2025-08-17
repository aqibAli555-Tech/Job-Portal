@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width: 100%; ">
    <table>
        <tr>
            <td style="padding-left: 20px;padding-right:20px ;font-size:20px;font-weight:bolder;padding-top:20px;padding-bottom:10px">Hi {{ $name }},</td>
        </tr>
        <tr>
            <td style="padding: 20px;">A new affiliate has just registered on Hungry For Jobs! 🎉
                <br> <br>
                Affiliate Name: {{ $username }} <br>
                Affiliate Email: {{ $useremail }} <br>
                City, Country: {{ $city_name }}, {{ $country_name }} <br>
                Phone Number: {{ $phone }}
                <br><br>
                Please reach out to them with any necessary onboarding information.
                <br><br>
                Best regards, <br>
                <strong>The Hungry For Jobs Team</strong>🍔
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])
