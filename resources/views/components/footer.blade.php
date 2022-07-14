<footer class="position-relative">
    <div class="container">
        <div class="row">
            <?php
                $footer = Cache::get('footerContent');
                // dd($footer);
                // dd($footer['logo']->sectioncontent[0]->content);
                ?>
            <div class="col-lg-4">
                <img src="{{ asset('assets/frontend/sectioncontent/' . $footer['logo']->sectioncontent[0]->content) ?? '' }}" alt="">
                
                <p class="mt-md-4 mt-3">{{$footer['desc']->sectioncontent[0]->content ?? ''}}</p>
                <div class="social-links mt-4">
                    <a href="#">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="#">
                        <i class="fa-brands fa-twitter"></i>
                    </a>
                    <a href="#">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </a>
                    <a href="#">
                        <i class="fa-brands fa-youtube"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-2">
                <h6 class="text-white mt-4 mt-md-0">Company</h6>
                <ul class="mt-4">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Services</a></li>
                    <li><a href="#">Events</a></li>
                </ul>
            </div>
            <div class="col-lg-2">
                <h6 class="text-white mt-4 mt-md-0">Links</h6>
                <ul class="mt-4">
                    <li><a href="#">Testimonials</a></li>
                    <li><a href="#">Blogs</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6 class="text-white mt-4 mt-md-0">Get In Touch</h6>
                <ul class="mt-4">
                    <li>
                        <a href="#"> <i class="fa-solid fa-phone"></i>{{$footer['number']->sectioncontent[0]->content ?? ''}}</a>
                    </li>
                    <li>
                        <a href="#"> <i class="fa-solid fa-envelope"></i>{{$footer['email']->sectioncontent[0]->content ?? ''}}</a>
                    </li>
                    <li>
                        <a href="#"> <i class="fa-solid fa-location-dot"></i>{{$footer['Adress']->sectioncontent[0]->content ?? ''}}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row top-white position-relative mt-5">
            <div class="col-lg-12 text-center">
                <p class="mt-3">&copy; {{ date('Y', time()) }} - {{ config('app.name') }}. All Rights Reserved.
                </p>
            </div>
        </div>
    </div>
</footer>
