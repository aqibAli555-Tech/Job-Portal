@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width: 100%; ">
    <table>
        <tr>
            <td style="padding-left: 20px;padding-right:20px ;font-size:20px;font-weight:bolder;padding-top:20px;padding-bottom:10px">Hello Admin,</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;padding-right:20px ;">A new {{$role}} has just registered on Hungry For Jobs.
                <br> <br>
                Name: {{$username}}
                <br>
                Registration Date: {{$created_at}}
                <br>
                @if($role == 'Employer')
                Email: {{$useremail}}
                <br>
                Phone #: {{$phone}}
                <br>
                @endif
                @if($role != 'Employer')
                Skills Sets: {{$skill_set}}
                @endif
                @if(!empty($availability))
                <br>
                Availability: {{$availability}}
                @endif
             
                @if(!empty($nationality))
                <br>
                Nationality: {{$nationality}}
                @endif
           
                @if(!empty($experience))
                <br>
                Experience: {{$experience}}
                @endif
                <br>
                City: {{$city_name}}
                <br>
                Country: {{$country_name}}
                <br>
                <br>
                Stay Hungry, Stay Munching, <br>
                <strong>Hungry For Jobs Team</strong>
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])
