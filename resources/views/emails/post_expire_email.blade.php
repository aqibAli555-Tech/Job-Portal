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
                We wanted to let you know that your job posting for {{$post_title}} has expired after 30 days on our platform.
                <br>
                <br>
                To review the applicants you’ve received, please log into your account. You can check who applied but if you’re not currently subscribed, you’ll need a subscription to access the full list of applicants CV’s.
                <br>
                <br>
                Stay Hungry, Stay Munching,<br>
                <strong>Hungry For Jobs Team</strong>
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])

