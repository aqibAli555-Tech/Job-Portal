@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width: 100%; ">
    <table>
        <tr>
            <td>
                <h2 style="padding-left: 20px;padding-right:20px ;font-size:20px;font-weight:bolder;padding-top:20px;padding-bottom:10px">
                    Dear {{$company_name}},</h2>
            </td>
        </tr>
        <tr>
            <td style="padding-left: 20px;padding-right:20px;">
                <br>
                You have applicants in the Interview status for more than two weeks in your Applicants Page - either in the Applicants Tab or Archived Applicants Tab.
                <br>
                <br>
                Names: {{$user_names}}
                <br>
                <br>
                Please click
                    <a href="{{url('account/applicants')}}">HERE</a> 
                to go to the Applicants Page to change the status of the Interview to either Hired or Rejected
                <br>
                <br>
                If you still need time and they are still in the Interview process, no need to do anything 
                now - we will 
                send another email every 7 days until you change the status of these applicants once 
                you’re ready.
                <br>
                <br>
                Goodluck and we hope you find the perfect candidates for your business!
                <br><br>
                Stay Hungry, Stay Munching, <br>
                <strong>Hungry For Jobs Team</strong>
                <br>
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])

