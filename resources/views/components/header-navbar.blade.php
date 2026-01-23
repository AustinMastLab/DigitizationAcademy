<header class="header digi-header header-style-1">
    <div id="digi-sticky-placeholder"></div>
    <div class="digi-mainmenu">
        <div class="container-fluid">
            <div class="header-navbar">
                <div class="header-logo">
                    <a href="{{ route('home') }}">
                        <img class="light-version-logo" src="{{ mix('images/logo/logo.svg') }}" alt="logo" style="width:170px;">
                    </a>
                </div>

                <div class="header-main-nav">
                    <nav class="mainmenu-nav" id="mobilemenu-popup">
                        <div class="d-block d-lg-none">
                            <div class="mobile-nav-header">
                                <div class="mobile-nav-logo">
                                    <a href="{{ route('home') }}" aria-label="Logo links to home page" role="button"><img
                                                class="light-mode" src="{{ mix('images/logo/logo.svg') }}" style="width:150px"
                                                alt="Digitization Academy home"></a>
                                </div>

                                <button class="mobile-menu-close" data-bs-dismiss="offcanvas"
                                        aria-label="Mobile menu close" name="button" type="button"><i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!--= header menu =-->
                        <ul class="mainmenu">
                            <li class="menu-item-has-children"><a href="#">Explore Courses</a>
                                <x-course-menu />
                            </li>
                            <li><a href="{{ route('calendar.index') }}" id="calendar">Calendar</a></li>
                            <li><a href="{{ route('team.index') }}" id="team">Team</a></li>
                            <li><a href="{{ route('contact.index') }}" id="contact">Contact</a></li>
                            <!-- Replace the existing language menu item with this -->
                            <li class="menu-item-has-children google-menu">
                                <a href="#">Language<i class="fas fa-globe ms-2"></i></a>
                                <x-language />
                            </li>
                            <!-- Hidden Google Translate Element -->
                            <div id="google_translate_element" style="display: none;"></div>
                        </ul>
                    </nav>
                </div>

                <div class="header-action">
                    <ul class="list-unstyled">
                        @role('Super Admin')
                            <!-- button associated with large screen aside -->
                            <li class="sidemenu-btn d-lg-block d-none">
                                <button class="btn-wrap"
                                        type="button"
                                        data-bs-toggle="offcanvas"
                                        data-bs-target="#offcanvasMenuRight"
                                        aria-label="Open side menu"
                                >
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </button>
                            </li>
                        @endrole
                        <li class="mobile-menu-btn sidemenu-btn d-lg-none d-block">
                            <button
                                class="btn-wrap"
                                type="button"
                                data-bs-toggle="offcanvas"
                                data-bs-target="#mobilemenu-popup"
                                aria-label="Open main menu"
                            >
                                <span></span>
                                <span></span>
                                <span></span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
