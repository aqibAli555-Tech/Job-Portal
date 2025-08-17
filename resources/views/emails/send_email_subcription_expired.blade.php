@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width: 100%; ">
    <table>
        <tr>
            <td>
                <h2 style="padding-left: 20px;padding-right:20px ;font-size:20px;font-weight:bolder;padding-top:20px;padding-bottom:10px">
                    Hello Company {{$myName}},</h2></td>
        </tr>
        <tr>
            <td style="padding-left: 20px;padding-right:20px;">
                Your subscription for the package {{$package_name}} has ended and therefore has expired.
                Click here to go to the <a href="{{$myurl}}">Upgrade Account </a> page and resubscribe to whichever package you like.
                <br><br>
                We offer both monthly and yearly subscription packages and you can save 1.5 months with the yearly subscription package!
                <br><br>
                Stay Hungry, Stay Munching, <br>
                <strong>Hungry For Jobs Team</strong>

            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])

