@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width: 100%;">
    <table>
        <tr>
            <td>
                <h2 style="padding-left: 20px;padding-right:20px ;font-size:20px;font-weight:bolder;padding-top:20px;padding-bottom:10px">Hello,</h2></td>
        </tr>
        <tr>
            <td style="padding: 20px;">You are invited to view a job vacancy post on Hungry For Jobs by email address:
                <a href="{{$sender_email}}" style="color:#7ed0de;">{{$sender_email}}</a>
                <br><br>
                <a href="{{$url}}" style="color:#7ed0de; "> Click here</a> to view and apply to the job offer.
                <br><br>
                Stay Hungry, Stay Munching, <br>
                <strong>Hungry For Jobs Team</strong>
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])
