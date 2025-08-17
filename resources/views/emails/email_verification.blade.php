@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width:100%;">
    <table>
        <tr>
            <td>
                <h2 style="padding-left: 20px;padding-right:20px ;font-size:20px;font-weight:bolder;padding-top:20px;padding-bottom:10px">Hello {{$myName}},</h2></td>
        </tr>
        <tr>
            <td style="padding-left:20px;padding-right:20px;">
                Welcome to Hungry For Jobs and thank you for
                registering on the world's first
                FREE Hospitality, Food & Beverage employment platform!
                <br> <br>
                Hungry for Jobs is an online based website developed to link employers and employees (job
                seekers)
                looking for exciting opportunities in the global Hospitality, Food & Beverage market.
                <br> <br>
                Click here to verify your email address:
                <br><br>
                <button style="padding: 9px 20px 9px 20px; display: block; margin: auto; background: #22d3fd; border: none;">
                    <a href="{{$verificationUrl}}" style="text-decoration: none; color: white; font-size: 15px;">Verify mail</a>
                </button>
                <br> <br>
                We hope Hungry For Jobs will be your launch-pad for a delicious journey and career!
                <br> <br>
                Stay Hungry, Stay Munching, <br>
                <strong>Hungry For Jobs Team</strong>
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])
