@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])

<div class="body" style="width: 100%;">
    <table>
        <tr>
            <td><h2 style="margin-left: 20px;">Dear <?= $employee_name ?> </h2></td>
        </tr>
        <tr>

            <td style="padding: 20px;">
                Company <?= $Company_name; ?>  has shortlisted you for a potential interview. The company Unlocked This Contact Through CV Search Page (they found your profile through our database of CV’s).
                <br><br>
                If Company <?= $Company_name; ?> chooses you between other shortlisted applicants and is interested, they will contact you by the phone number and/or email you put on your CV.
                They may also contact you by Direct Message through your HungryForJobs dashboard.
                <br><br>
                Good Luck!
                <br><br>
                Stay Hungry, Stay Munching, <br>
                <strong>Hungry For Jobs Team</strong>
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])
