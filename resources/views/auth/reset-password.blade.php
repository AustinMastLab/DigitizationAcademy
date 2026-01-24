<x-app-layout>
    <section class="section section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3 col-sm-12">
                    <div class="contact-form-box shadow-box mb--30 px-sm-4">
                        <form method="post" action="{{ route('password.update') }}" novalidate>
                            @csrf
                            @honeypot
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">
                            <div class="form-group mb-3">
                                <label for="reset-email">Email</label>
                                <input
                                        id="reset-email"
                                        type="email"
                                        class="form-control"
                                        name="email"
                                        value="{{ old('email') }}"
                                        autocomplete="email"
                                        required
                                >
                            </div>
                            <div class="form-group mb-3">
                                <label for="reset-password">Password</label>
                                <input
                                        id="reset-password"
                                        type="password"
                                        class="form-control"
                                        name="password"
                                        autocomplete="new-password"
                                        required
                                >
                            </div>
                            <div class="form-group mb-3">
                                <label for="reset-password-confirmation">Confirm Password</label>
                                <input
                                        id="reset-password-confirmation"
                                        type="password"
                                        class="form-control"
                                        name="password_confirmation"
                                        autocomplete="new-password"
                                        required
                                >
                            </div>
                            <div class="form-group">
                                <button type="submit" class="digi-btn btn-fill-primary btn-fluid btn-primary secondary"
                                        name="submit-btn">Reset Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>