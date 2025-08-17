@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width: 100%; ">
    <table>
        <tr>
            <td><h2 style="padding-left: 20px;padding-right:20px ;font-size:20px;font-weight:bolder;padding-top:20px;padding-bottom:10px">Hello!</h2></td>
        </tr>
        <tr>
            <td style="padding-left: 20px;padding-right:20px;">
                An new contact request has been submitted with following information
                <br>
                Country:<a href="{{url('lang/en?d='.$country_code)}}" target="_blank" style="color:#7ed0de;text-decoration: underline;cursor: pointer;">{{$country_name}}</a>.
                <br>
                First Name: {{$first_name}}
                <br>
                Last Name: {{$last_name}}
                <br>
                Email Address: {{$email_address}}
                <br>
                User type: {{$user_type}}
                <br>
                Phone number: {{$phone}}
                <br>
                Description: <?php echo (string)$user_message; ?>
                <br><br>
                Stay Hungry, Stay Munching, <br>
                <strong>Hungry For Jobs Team</strong>
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])
