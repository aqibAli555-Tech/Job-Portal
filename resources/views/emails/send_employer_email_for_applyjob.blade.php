@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width: 100%; ">
        <table>
            <tr>
                <td><h2 style="padding-left: 20px;padding-right:20px ;font-size:20px;font-weight:bolder;padding-top:20px;padding-bottom:10px">Hello {{$myName}},</h2></td>
            </tr>
            <tr>
                <td style="padding-left: 20px;padding-right:20px;">
                    {!! $post_text !!}
                    <br>
                    <a href="{{url('account/applied_applicants')}}" target="_blank" style="color:#7ed0de;text-decoration: underline;cursor: pointer;">Click here</a> to go to the Applicants Page to view their CV and filter them as interview, hired or rejected.
                    <br><br>
                    When someone applies for your job, our team checks how accurate their CV and Skills Sets are to your job offer and we filter applicants for you as Not Accurate, Accurate, and Very Accurate.
                    <br><br>
                     Important: Applicants will only show up on your Applicants page once we filter them for you and this usually takes our team 24-48 hours after an employee (job seeker) applies for your job.
                     <br><br>
                     We wish you the best of luck and hope you find the right applicants for your job offer!
                     <br>
                     <br>
                    Stay Hungry, Stay Munching, <br>
                    <strong>Hungry For Jobs Team</strong>
                </td>
            </tr>
        </table>
    </div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])
