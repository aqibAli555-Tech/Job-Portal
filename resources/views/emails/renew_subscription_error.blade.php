@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width: 100%; ">
    <table>
        <tr>
            <td>
                <h2 style="padding-left: 20px;padding-right:20px ;font-size:20px;font-weight:bolder;padding-top:20px;padding-bottom:10px">
                    Dear {{$myName}},</h2></td>
        </tr>
        <tr>
            <td style="padding-left: 20px;padding-right:20px;">Thank you for renewing another 30 days with our Elite Premium Packages. You can still enjoy our advanced searching tools to help you discover the best candidates for your organization.
                <br>
                <br>
                If you'd like to stop your subscription at anytime, login and visit the transaction page on your profile, there you’ll find a button to cancel.
                <br>
                <br>
                Good luck and we hope you find the perfect candidates!
                <br><br>
                Stay Hungry, Stay Munching, <br>
                <strong>Hungry For Jobs Team</strong>
                <br>
                <br>
                Package name: {{$package_name}}
                <br>
                Package price: {{$price}}
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])

