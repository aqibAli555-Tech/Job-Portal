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
                Your affiliate commission for <strong>{{ $month }} {{ $year }}</strong> has just been calculated! 💰<br>
                Want to know how much you made?<br>
                👉 Head to your Commissions Page in your dashboard to view your full earnings.<br><br>
                
                🔒 Please note:<br>
                Commission payouts are held for 30 days after the end of the current month for validation and quality assurance.<br>
                So your <strong>{{ $month }} {{ $year }}</strong> earnings will be eligible for withdrawal starting <strong>{{ $withdraw_date }}</strong>.<br><br>
                
                ✅ Don’t worry — you’ll receive an email notification the moment your funds are ready to be withdrawn.<br><br>
                
                This includes commissions from:<br>
                    •	Companies you referred directly<br>
                    •	Companies brought in by affiliates you referred<br><br>
                
                Every month your impact grows — and so does your income. Keep it up, you’re building something powerful! 💼🚀<br><br>
                
                Questions? Hit us up anytime at:<br>
                📧 contact@hungryforjobs.com<br><br>

                Keep munching on success,<br>
                🍔<br>
                <strong>The Hungry for Jobs Team</strong>
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])
