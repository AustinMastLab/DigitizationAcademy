<aside class="offcanvas offcanvas-end header-offcanvasmenu" tabindex="-1" id="offcanvasMenuRight">
    <div class="offcanvas-header">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row ">
            <div class="col-lg-5 col-xl-6">
                <ul class="main-navigation list-unstyled">
                    <li><a href="/admin" id="admin">Admin</a></li>
                    <li><a href="{{ route('horizon.index') }}" target="_blank" id="horizon">Horizon</a></li>

                    <li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" id="logout" class="main-navigation-button">
                                Logout
                            </button>
                        </form>
                    </li>

                    @guest
                        <li><a href="{{ route('login') }}">Login</a></li>
                        @if(\Laravel\Fortify\Features::enabled('register'))
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @endif
                    @endguest
                </ul>
            </div>
        </div>
    </div>
</aside>
