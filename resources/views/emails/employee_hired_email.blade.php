@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width: 100%; ">
    <table>
        <tr>
        </tr>
        <tr>
            <td style="padding-left: 20px;padding-right:20px;">

                <p> Dear {{$name}},</p>
                <p>Congrats on being hired by Company {{$company_name}} for the position of {{$post_title}} that you applied for!</p>
                <p> Your CV will always be in our database incase you need to find another job in the future!</P>
                <p>Good Luck!</p>
                <p>Stay Hungry, Stay Munching,</p>
                <strong>Hungry For Jobs Team</strong>
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])
