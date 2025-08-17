@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width: 100%; ">
    <table>
        <tr>
        </tr>
        <tr>
            <td style="padding-left: 20px;padding-right:20px;">

                <p> Dear {{$name}},</p>
                <p>{{$company_name}} looking to hire a recruit has recently viewed your CV.</p>
                <p> There is potential that this company {{$company_name}} could contact you by the phone number and/or email you put on your CV.</P>
                <P>They may also contact you by Direct Message through your HungryForJobs dashboard.</p>
                <p>Good Luck!</p>
                <p>Stay Hungry, Stay Munching,</p>
                <strong>Hungry For Jobs Team</strong>
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])
