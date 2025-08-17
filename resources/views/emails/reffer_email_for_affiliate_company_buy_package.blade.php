@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width: 100%;">
    <table>
        <tr>
            <td style="padding-left: 20px;padding-right:20px ;font-size:20px;font-weight:bolder;padding-top:20px;padding-bottom:10px">Hey {{ $name }},</td>
        </tr>
        <tr>
            <td style="padding: 20px;">
                🎉 Congratulations! One of the companies you referred — {{ $company_name }} — used the {{ $package_discount }} discount that you offered them and subscribed to our {{ $package['name'] }}(${{ number_format($package['after_discount'], 2) }}) plan on Hungry for Jobs! 💼🔥
                <br><br>
                Thanks to your efforts, more employers are discovering how easy and powerful it is to hire in the hospitality, food and beverage industries — and you’re getting rewarded for it! 💸💪
                <br><br>
                Here’s what just happened:
                <br><br>
                ✅ You referred {{ $company_name }}<br>
                ✅ They subscribed to the {{ $package['name'] }}(${{ number_format($package['after_discount'], 2) }}) plan<br>
                ✅ You’ve officially earned a commission 💰<br>
                ✅ And best of all — you’ll continue to earn every month (or year) they stay subscribed, as per our terms and conditions.
                <br><br>
                Why this matters:
                <br><br>
                Your referral isn’t just a one-time win — it’s the start of recurring income.<br>
                As long as {{ $company_name }} stays with us, you’ll keep getting paid. 🔁✨
                <br><br>
                Let this be your reminder:<br>
                Every link you share = real impact + real income.
                <br><br>
                💬 Got questions? Just reply to this email or reach out anytime at contact@hungryforjobs.com or through direct chat on your Dashboard!
                <br><br>
                We’re proud to have you on the team, and we can’t wait to see who you refer next 🚀
                <br><br>
                Stay Hungry, Stay Munching,<br>
                <strong>– The Hungry for Jobs Team</strong>
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])
