@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width: 100%; ">
    <table>
        <tr>
            <td>
                <h2 style="padding-left: 20px;padding-right:20px ;font-size:20px;font-weight:bolder;padding-top:20px;padding-bottom:10px">
                    Dear {{$myName}},</h2></td>
        </tr>
        <tr>
            <td style="padding-left: 20px;padding-right:20px;">
                Thank you for subscribing to Hungry For Jobs. You can now discover and recruit the best candidates to your company by:
                <br><br>
                1. Posting a job and waiting for employees to apply.<br>
                2. Searching HFJ’s CV database .
                <br><br>
                All employees have Contact Cards which includes their CV, so once you’re ready to get in touch with anyone, just click their Contact Card.
                <br><br>
                Upon expiration of your subscription package, your package will be renewed and your account will be automatically charged again.
                <br>
                If you would like to stop your subscription at any time, login and visit the transaction page on your profile to cancel your subscription.
                <br><br>
                Good luck and we hope you find the perfect candidates!
                <br><br>

                Package name: {{$package_name}}
                <br>
                Package price: {{$price}}
                <br>
                @if(!empty($discount))
                Discount: {{ $discount }}
                <br>
                @endif
                @if(!empty($after_discount))
                Package price after discount: {{ $after_discount }}
                <br>
                @endif
                @if(!empty($package_type))
                Duration: {{$package_type}}
                @endif
                <br>
                <br>
                Stay Hungry, Stay Munching,<br>
                <strong>Hungry For Jobs Team</strong>
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])

