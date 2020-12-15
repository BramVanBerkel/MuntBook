{{--<header class="header-area">--}}
{{--    <div class="container">--}}
{{--        <div class="row">--}}
{{--            <div class="col-12">--}}
{{--                <nav class="main-nav">--}}
{{--                    <!-- Logo -->--}}
{{--                    <a href="{{ route('home') }}" class="logo">--}}
{{--                        <img src="{{ asset('images/logos/white.svg') }}" class="light-logo" alt="GuldenBook"/>--}}
{{--                        <img src="{{ asset('images/logos/black.svg') }}" class="dark-logo" alt="GuldenBook"/>--}}
{{--                    </a>--}}

<!-- Lang -->
{{--                                    <div class="lang">--}}
{{--                                        <div class="selected">--}}
{{--                                            <img src="assets/images/flags/en.png" alt="">--}}
{{--                                            <i class="fa fa-angle-down"></i>--}}
{{--                                        </div>--}}
{{--                                        <ul class="flag-list">--}}
{{--                                            <li>--}}
{{--                                                <a href="#">--}}
{{--                                                    <img src="assets/images/flags/en.png" alt=""><span>EN</span>--}}
{{--                                                </a>--}}
{{--                                            </li>--}}
{{--                                            <li>--}}
{{--                                                <a href="#">--}}
{{--                                                    <img src="assets/images/flags/ru.png" alt=""><span>RU</span>--}}
{{--                                                </a>--}}
{{--                                            </li>--}}
{{--                                            <li>--}}
{{--                                                <a href="#">--}}
{{--                                                    <img src="assets/images/flags/br.png" alt=""><span>BR</span>--}}
{{--                                                </a>--}}
{{--                                            </li>--}}
{{--                                        </ul>--}}
{{--                                    </div>--}}
<!-- ***** Lang End ***** -->

<!-- ***** Menu Start ***** -->
{{--                    <li class="nav nav-dropdown">--}}
{{--                        <a class="sub-menu-icon">Blog</a>--}}
{{--                        <ul class="sub-menu list-unstyled box rounded-bottom">--}}
{{--                            <li class="sub-menu-item">--}}
{{--                                <a href="../blog/card-style.html" class="sub-menu-link">Card Style</a>--}}
{{--                            </li>--}}
{{--                            <li class="sub-menu-item">--}}
{{--                                <a href="../blog/list-style.html" class="sub-menu-link">List Style</a>--}}
{{--                            </li>--}}
{{--                            <li class="sub-menu-item">--}}
{{--                                <a href="../blog/profile.html" class="sub-menu-link">Blog Profile</a>--}}
{{--                            </li>--}}
{{--                            <li class="sub-menu-item">--}}
{{--                                <a href="../blog/single-article.html" class="sub-menu-link">Single Article</a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}

{{--                    <ul class="nav">--}}
{{--                        <li><a href="{{ route('nonce-distribution') }}">Nonce distribution</a></li>--}}
{{--                    </ul>--}}
{{--                    <a class='menu-trigger'>--}}
{{--                        <span>Menu</span>--}}
{{--                    </a>--}}
{{--                    <!-- ***** Menu End ***** -->--}}
{{--                </nav>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</header>--}}

<header class="header-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <a href="{{ route('home') }}" class="logo">
                        <img src="{{ asset('images/logos/white.svg') }}" class="light-logo" alt="GuldenBook"/>
                        <img src="{{ asset('images/logos/black.svg') }}" class="dark-logo" alt="GuldenBook"/>
                    </a>

                    <ul class="nav" style="display: none;">
                        <li><a>item</a></li>
                        <li class="sub-menu">
                            <a>dropdown</a>
                            <ul class="">
                                <li><a href="#">item</a></li>
                                <li><a href="#">item</a></li>
                                <li><a href="#">item</a></li>
                                <li><a href="#">item</a></li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a>dropdown</a>
                            <ul class="">
                                <li><a href="#">item</a></li>
                                <li><a href="#">item</a></li>
                                <li><a href="#">item</a></li>
                                <li><a href="#">item</a></li>
                            </ul>
                        </li>
                        <li><a>item</a></li>
                    </ul>
                    <a class="menu- trigger"></a>
                    <!-- ***** Menu End ***** -->

                    <!-- ***** Header Buttons Start ***** -->
{{--                    <ul class="header-buttons">--}}
{{--                        <li><a class="btn-nav-line" href="#">SIGN IN</a></li>--}}
{{--                        <li><a class="btn-nav-primary" href="#">SIGN UP</a></li>--}}
{{--                    </ul>--}}
                    <!-- ***** Header Buttons End ***** -->
                </nav>
            </div>
        </div>
    </div>
</header>
