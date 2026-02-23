<x-app-layout>
    <!-- shape groups -->
    <ul class="shape-group-6 list-unstyled">
        <li class="shape shape-1">
            <img src="{{ asset('images/logo/watermarkApr2025.svg') }}" alt="Bubble">
        </li>
    </ul>
    <!-- Contact  Area Start     =-->
    <section class="section section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3 col-sm-12">
                    <div class="contact-form-box shadow-box mb--30 px-sm-4">
                        <h1 id="page-title" class="visually-hidden">Login</h1>
                        <form method="post" action="{{ route('login') }}" aria-labelledby="page-title" novalidate>
                            @csrf
                            @honeypot
                            <div class="form-group mb-3">
                                <label for="login-email">Email</label>
                                <input
                                        id="login-email"
                                        type="email"
                                        class="form-control"
                                        name="email"
                                        value="{{ old('email') }}"
                                        autocomplete="email"
                                        required
                                >
                            </div>

                            <div class="form-group mb-3">
                                <label for="login-password">Password</label>
                                <input
                                        id="login-password"
                                        type="password"
                                        class="form-control"
                                        name="password"
                                        autocomplete="current-password"
                                        required
                                >
                            </div>
                            <div class="form-group">
                                <button
                                        type="submit"
                                        class="digi-btn btn-fill-primary btn-fluid btn-primary secondary"
                                        name="submit-btn"
                                >
                                    Login
                                </button>
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>