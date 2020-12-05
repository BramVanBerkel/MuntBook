<header class="header-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="logo">
                        <img src="{{ asset('images/logos/white.svg') }}" class="light-logo" alt="GuldenBook"/>
                        <img src="{{ asset('images/logos/black.svg') }}" class="dark-logo" alt="GuldenBook"/>
                    </a>

                    <!-- Lang -->
                {{--                    <div class="lang">--}}
                {{--                        <div class="selected">--}}
                {{--                            <img src="assets/images/flags/en.png" alt="">--}}
                {{--                            <i class="fa fa-angle-down"></i>--}}
                {{--                        </div>--}}
                {{--                        <ul class="flag-list">--}}
                {{--                            <li>--}}
                {{--                                <a href="#">--}}
                {{--                                    <img src="assets/images/flags/en.png" alt=""><span>EN</span>--}}
                {{--                                </a>--}}
                {{--                            </li>--}}
                {{--                            <li>--}}
                {{--                                <a href="#">--}}
                {{--                                    <img src="assets/images/flags/ru.png" alt=""><span>RU</span>--}}
                {{--                                </a>--}}
                {{--                            </li>--}}
                {{--                            <li>--}}
                {{--                                <a href="#">--}}
                {{--                                    <img src="assets/images/flags/br.png" alt=""><span>BR</span>--}}
                {{--                                </a>--}}
                {{--                            </li>--}}
                {{--                        </ul>--}}
                {{--                    </div>--}}
                <!-- ***** Lang End ***** -->

                    <!-- ***** Menu Start ***** -->
                    <ul class="nav">
                        <li><a href="{{ route('nonce-distribution') }}">Nonce distribution</a></li>
                    </ul>
                    <a class='menu-trigger'>
                        <span>Menu</span>
                    </a>
                    <!-- ***** Menu End ***** -->
                </nav>
            </div>
        </div>
    </div>
</header>

