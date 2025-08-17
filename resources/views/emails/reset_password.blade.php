@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])

<div class="body" style="width: 100%;">
    <table>
        <tr>
            <td><h2 style="margin-left: 20px;">Hello {{$myName}},</h2></td>
        </tr>
        <tr>
            <td style="padding: 20px;">Let’s get you a new password, click here to reset:
                <br><br>
                <button style="padding: 9px 20px 9px 20px; display: block; margin: auto; background: #22d3fd; border: none;">
                    <a href="{{$reseturl}}"
                       style="text-decoration: none; color: white; font-size: 15px;">Reset Password</a>
                </button>
                <br>
                If you did not request to change your password, no further action is required.
                <br><br>
                Stay Hungry, Stay Munching, <br>
                <strong>Hungry For Jobs Team</strong>
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])
