@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width: 100%;">
    <table>
        <tr>
            <td style="padding-left: 20px;padding-right:20px; font-size:20px; font-weight:bolder; padding-top:20px; padding-bottom:10px;">
                Hey {{ $name }}, 👋
            </td>
        </tr>
        <tr>
            <td style="padding: 20px; font-size: 16px; line-height: 1.7;">
                Great news — your commission earnings from <strong>{{ $month }} {{ $year }}</strong> are now ready to be withdrawn! 💼💰<br><br>

                You’ve done the work, and now it’s time to enjoy the reward.<br><br>

                👉 To request your withdrawal, just head over to your Commissions Page in your dashboard.<br><br>

                💳 How to withdraw:<br>
                All withdrawals are processed through PayPal, so make sure your PayPal email is added and up to date in your account settings to avoid any delays.<br><br>

                This is your hustle paying off — and there’s plenty more to come if you keep at it. 🔥<br><br>

                Need help or have questions? We’re always here:<br>
                📧 contact@hungryforjobs.com<br><br>

                Keep building, keep earning.<br>
                🍔<br>
                Stay Hungry, Stay Munching,<br>
                <strong>The Hungry for Jobs Team</strong>
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])
