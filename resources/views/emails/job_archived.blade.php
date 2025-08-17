@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width: 100%; ">
    <table>
        <tr>
            <td>
                <h2 style="padding-left: 20px;padding-right:20px ;font-size:20px;font-weight:bolder;padding-top:20px;padding-bottom:10px">
                    Hello {{$myName}},</h2></td>
        </tr>
        <tr>
            <td style="padding-left: 20px;padding-right:20px ;">Your job post has been archived on Hungry For
                Jobs
                <br>
                You may repost the same job vacancy by <a href="{{url('account/archived')}}" target="_blank"
                                                          style="color:#7ed0de;text-decoration: underline;cursor: pointer;">Clicking
                    here</a>.
                <br>
                <br>
                If nothing is done, your job post will be permanently deleted on <b>{{$delete_date}}</b>.
                <br>
                <br>
                Stay Hungry, Stay Munching, <br>
                <strong>Hungry For Jobs Team</strong>
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])
