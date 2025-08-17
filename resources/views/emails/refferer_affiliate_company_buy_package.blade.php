@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width: 100%;">
    <table>
        <tr>
            <td style="padding-left: 20px;padding-right:20px ;font-size:20px;font-weight:bolder;padding-top:20px;padding-bottom:10px">Hey {{ $name }},</td>
        </tr>
        <tr>
            <td style="padding: 20px;">
                Huge news coming your way! 🎉
                <br><br>
                The affiliate you referred — {{ $referral_affiliate_name }} — just brought in a new company: {{ $company_name }}, and guess what?
                <br><br>
                They subscribed to one of our plans on Hungry for Jobs, which means you’ve now locked in your 5% lifetime commission on this company’s subscription revenue. 💰💼                
                <br><br>
                🔒 Here’s what you’ve just secured:<br>
                •	5% of every subscription made by {{ $company_name }}<br>
                •	Whether it’s monthly or yearly, you’ll keep earning as long as they stay subscribed<br>
                •	All thanks to your referral of {{ $referral_affiliate_name }} 🙌<br>
                <br><br>
                This is how affiliate power compounds — your network is working for you, even while you sleep. 🛌💸
                <br><br>
                The more affiliates and companies you bring in, the more lifetime income you create.<br>
                🔥 Keep building. Keep earning.
                <br><br>
                Need any help? We’ve got your back:<br>
                📧 contact@hungryforjobs.com
                <br><br>
                Keep munching on those commissions,<br>
                🍔<br>
                <strong>The Hungry for Jobs Team</strong>
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])
