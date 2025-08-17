@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width: 100%; ">
    <table>
        <tr>
            <td>
                <h2 style="padding-left: 20px;padding-right:20px ;font-size:20px;font-weight:bolder;padding-top:20px;padding-bottom:10px">
                    Hello {{$myName}},</h2></td>
        </tr>
        <tr>

            <td style="padding-left: 20px;padding-right:20px ;">Your <a href="{{$url}}" target="_blank" style="color:#7ed0de;text-decoration: underline;cursor: pointer;">
                    job post </a> is now live, you can expect employees (job seekers) to start applying for this job soon.
                <br>
                <br>
                You can filter your applicants by interviewing, rejecting, or hiring them. 
                <br><br>
                You can also search for employees (job seekers) through our Search CV page and contact who you’re interested in.
                <br><br>
                We hope you find the best candidates for your open job post!
                <br><br>
                <br>
                <br>
                Stay Hungry, Stay Munching, <br>
                <strong>Hungry For Jobs Team</strong>
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])
