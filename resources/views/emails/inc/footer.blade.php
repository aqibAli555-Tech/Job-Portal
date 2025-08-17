<br><br>
<div class="" style="text-align: center;">
    <table style="width:100%">
        <tr>
            <td style="text-align: center;">
                <a href="{{$fb}}"><img src="{{ url()->asset('icon/facebook.png') }}" alt="" style=" width: 20px; cursor: pointer;"></a>
                <a href="{{$insta}}"><img src="{{ url()->asset('icon/insta.png') }}" alt="" style=" width: 20px; cursor: pointer;"></a>
                <a href="{{$linkedin}}"><img src="{{ url()->asset('icon/linkedin.png') }}" alt="" style=" width: 20px;cursor: pointer;"></a>
                <a href="{{$tiktok}}"><img src="{{ url()->asset('icon/tiktok.png') }}" alt="" style=" width: 20px;cursor: pointer;border-radius: 8px;"></a>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">
                <?php $now = new DateTime();
                $year = $now->format("Y");
                ?>
                © {{$year}} Hungry For Jobs. All rights reserved.
            </td>
        </tr>
    </table>
</div>
</body>
</html>