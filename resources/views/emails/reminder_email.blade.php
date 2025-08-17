@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width: 100%; ">
        <table>
            <tr>
            </tr>
            <tr>
                <td style="padding-left: 20px;padding-right:20px;">
                    <p>Your package FREE FOOD PACKAGE will expire in one week - this is just a reminder email incase you need to post more jobs or contact more employees (job seekers).
                        <br>
                        <br>
                    You can also <a href="{{$url}}" target="_blank" style="color:#7ed0de;text-decoration: underline;cursor: pointer;">Click
                    here</a> to go to the Upgrade Account page and change your subscription to whichever package you like.</p>
                   <p>Stay Hungry, Stay Munching,</p>
                    <strong>Hungry For Jobs Team</strong>
                </td>
            </tr>
        </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])