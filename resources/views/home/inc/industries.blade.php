<style>
    .img-caption-heading{
        font-size:18px;
        font-weight: 700;
        font-family: 'Source Sans Pro', sans-serif;
    }
</style>
<h2 class="company-name-heading">
    {{t('What type of industries can register with Hungry For Jobs?')}}
</h2>
<div class="row">
    <div class="col-md-3">
        <div class="img-container" style="background-image: url('{{url()->asset('home_icons/food.jpg')}}')">
            <div class="caption">
                <h3 class="img-caption-heading pb-0">{{t('Food & Beverages')}}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="img-container" style="background-image: url('{{url()->asset('home_icons/travel.jpg')}}')">
            <div class="caption">
                <h3 class="img-caption-heading pb-0">{{t('Travel')}}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="img-container" style="background-image: url('{{url()->asset('home_icons/accommodation.jpg')}}')">
            <div class="caption">
                <h3 class="img-caption-heading pb-0">{{t('Accommodation')}}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="img-container" style="background-image: url('{{url()->asset('home_icons/entertainment.jpg')}}')">
            <div class="caption">
                <h3 class="img-caption-heading pb-0">{{t('Entertainment')}}</h3>
            </div>
        </div>
    </div>
</div>
