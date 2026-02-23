<x-app-layout>
    <section class="section section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3 col-sm-12">
                    <div class="contact-form-box shadow-box mb--30 px-sm-4">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <h1 id="page-title" class="visually-hidden">Forgot Password</h1>
                        <form method="post" action="{{ route('password.email') }}" aria-labelledby="page-title" novalidate>
                            @csrf
                            @honeypot
                            <div class="form-group mb-3">
                                <label for="forgot-email">Email</label>
                                <input
                                        id="forgot-email"
                                        type="email"
                                        class="form-control"
                                        name="email"
                                        value="{{ old('email') }}"
                                        autocomplete="email"
                                        required
                                >
                            </div>
                            <div class="form-group">
                                <button
                                        type="submit"
                                        class="digi-btn btn-fill-primary btn-fluid btn-primary secondary"
                                        name="submit-btn"
                                >
                                    Send Password Reset Link
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>