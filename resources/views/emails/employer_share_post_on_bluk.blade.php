@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width: 100%;">
    <table>
        <tr>
            <td>
                <h2 style="padding-left: 20px;padding-right:20px ;font-size:20px;font-weight:bolder;padding-top:20px;padding-bottom:10px">Hello {{$employee}},</h2></td>
        </tr>
        <tr>
            <td style="padding: 20px;">Please see this <a href="{{$url}}" style="color:#7ed0de;">Job Post</a> for {{$position}} by {{$company}}.

                <br>
                If you’re interested in applying, log in to your Hungry For Jobs account and click the Apply Now button on this job post!
                <br>
                If the Company is interested in your Skills Sets and CV, they will get in contact with you!
                <br><br>
                Stay Hungry, Stay Munching, <br>
                <strong>Hungry For Jobs Team</strong>
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])
