<?php $bannerImage = isset($bannerTitle) ? (isset($bannerTitle->image) ? asset('assets/frontend/images/banners/' . $bannerTitle->image) : '') : ''; ?>
<section class="home-banner inner-page" style="background: url({{$bannerImage}}) center center no-repeat;">
    <div class="container center-container">
        <div class="row align-items-center">
            <div class="col-lg-12 text-center">
                <div class="section-heading">
                    <h1>{{ isset($bannerTitle) ? (isset($bannerTitle->headings) ? json_decode($bannerTitle->headings)->one : '') : '' }}
                    </h1>
                </div>
            </div>
        </div>
    </div>
</section>
