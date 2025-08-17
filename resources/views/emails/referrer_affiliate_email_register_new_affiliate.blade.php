@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])

<div class="body" style="width: 100%;">
    <table>
        <tr>
            <td style="padding-left: 20px; padding-right: 20px; font-size: 20px; font-weight: bolder; padding-top: 20px; padding-bottom: 10px;">
                Dear {{ $name }},
            </td>
        </tr>
        <tr>
            <td style="padding: 20px;">
                Big news — someone just registered to Hungry for Jobs using your unique affiliate invite link! 🙌
                <br><br>
                Their name? <strong>{{ $new_affiliate_name }}</strong> — welcome them to the team! 🎉
                <br><br>
                This means you’ve officially referred a new affiliate to our platform. But here’s the best part…
                <br><br>
                👉 You’ll now earn 5% of every subscription sale that {{ $new_affiliate_name }} brings to Hungry for Jobs — for life!                
                <br>
                Whether it’s a monthly plan or a yearly package, as long as their referred companies keep subscribing, you’ll keep earning. 💸
                <br><br>
                This is just the beginning. The more affiliates you refer, the more passive income you build — month after month, year after year. 🌱
                <br><br>
                Your hustle is now earning you double the rewards.
                <br><br>
                If you ever need support or have questions, we’re just one email away at:<br> 
                📧 contact@hungryforjobs.com
                <br><br>
                Keep growing your network. Keep earning.<br>
                The world is hungry for opportunities — and you’re serving them right.
                <br><br>
                🍔<br>
                Stay Hungry, Stay Munching, <br>
                <strong>Hungry For Jobs</strong>
            </td>
        </tr>
    </table>
</div>

@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])
