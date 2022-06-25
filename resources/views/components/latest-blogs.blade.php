<section class="blogs section-padding">
    <div class="container">
        <div class="row">
            @for ($i = 1; $i <= 12; $i++)
                <div class="col-lg-3 mb-4">
                    <div class="blog-card">
                        <div class="blog-card-upper">
                            <div class="img-div">
                                <a href="{{route('front.blog.detail', 'test-blog')}}"><img src="{{asset('assets/frontend/images/Rectangle_686.png')}}" alt=""></a>
                            </div>
                        </div>
                        <div class="blog-card-lower">
                            <div class="blog-category">
                                <p class="text-white">Community</p>
                            </div>
                            <div class="blog-desc">
                                <h6 class="text-white"><a href="{{route('front.blog.detail', 'test-blog')}}">Your Wellness Expert On Social Media - Why Is Online Grooming A Hot Trend?</a></h6>
                                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Architecto rerum sapiente
                                    maxime magni</p>
                            </div>
                            <div class="blog-details">
                                <p>By <span class="text-white">Admin</span> - April 27, 2022 </p>
                            </div>
                            <div class="blog-comments mt-2 d-flex">
                                <p><i class="fa-solid fa-message"></i> 0</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</section>
