@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.header', 'emails.inc.header'])
<div class="body" style="width: 100%;">
    <table>
        <tr>
            <td style="padding-left: 20px;padding-right:20px ;font-size:20px;font-weight:bolder;padding-top:20px;padding-bottom:10px">Dear {{ $name }},</td>
        </tr>
        <tr>
            <td style="padding: 20px;">We’re happy to inform you that your withdrawal request has been successfully processed!
                <br><br>
                    Amount Withdrawn: ${{ $affiliate_data->amount }} <br>
                    Withdrawal Method: Bank Transfer <br>
                    Date of Withdrawal: {{ $affiliate_data->created_at->format('d F Y') }}
                <br><br>
                    Please allow up to 5 business days for the funds to be transferred to your account, depending on your chosen withdrawal method. If you have any questions or concerns, feel free to contact us via the chat on your dashboard or email us at 
                <a href="mailto:contact@hungryforjobs.com">contact@hungryforjobs.com</a>.                
                <br><br>
                    Thank you for being a valued affiliate, and we look forward to continuing our successful partnership!
                <br><br>
                Stay Hungry, Stay Munching, <br>
                <strong>Hungry For Jobs</strong>
            </td>
        </tr>
    </table>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])
