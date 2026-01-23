<x-app-layout>
    <section class="section section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3 col-sm-12">
                    <div class="contact-form-box shadow-box mb--30 px-sm-4">
                        <form method="post" action="{{ route('register') }}" class="recaptcha" novalidate>
                            @csrf
                            <div class="form-group mb-3">
                                <label for="register-name">Name</label>
                                <input
                                        id="register-name"
                                        type="text"
                                        class="form-control"
                                        name="name"
                                        value="{{ old('name') }}"
                                        autocomplete="name"
                                        required
                                >
                            </div>
                            <div class="form-group mb-3">
                                <label for="register-email">Email</label>
                                <input
                                        id="register-email"
                                        type="email"
                                        class="form-control"
                                        name="email"
                                        value="{{ old('email') }}"
                                        autocomplete="email"
                                        required
                                >
                            </div>
                            <div class="form-group mb-3">
                                <label for="register-password">Password</label>
                                <input
                                        id="register-password"
                                        type="password"
                                        class="form-control"
                                        name="password"
                                        autocomplete="new-password"
                                        required
                                >
                            </div>
                            <div class="form-group mb-3">
                                <label for="register-password-confirmation">Confirm Password</label>
                                <input
                                        id="register-password-confirmation"
                                        type="password"
                                        class="form-control"
                                        name="password_confirmation"
                                        autocomplete="new-password"
                                        required
                                >
                            </div>
                            @include('partials.recaptcha')
                            <div class="form-group">
                                <button type="submit" class="digi-btn btn-fill-primary btn-fluid btn-primary secondary"
                                        name="submit-btn">Register
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>