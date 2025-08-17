@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width: 100%; ">
    <table>
        <tr>
            <td>
                <h2 style="padding-left: 20px;padding-right:20px ;font-size:20px;font-weight:bolder;padding-top:20px;padding-bottom:10px">
                    Dear {{$myName}},</h2></td>
        </tr>
        <tr>
            <td style="padding-left: 20px;padding-right:20px;">{{$content}}
                <br>
                <br>
                For any assistance or inquiries contact us on customercare@hungryforjobs.com
                <br>
                <br>
                If you would like to renew your subscription again please <a href="{{url('account/transactions')}}"
                                                                             target="_blank"
                                                                             style="color:#7ed0de;text-decoration: underline;cursor: pointer;">Click
                    here</a>.
                <br>
                <br>
                We hope to see you soon again!
                <br>
                <br>
                Stay Hungry, Stay Munching, <br>
                <strong>Hungry For Jobs Team</strong>
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])

