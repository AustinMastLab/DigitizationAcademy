<x-app-layout>
    <section class="section section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3 col-sm-12">
                    <div class="contact-form-box shadow-box mb--30 px-sm-4">
                        @if (session('status') == 'verification-link-sent')
                            <div class="alert alert-success" role="alert">
                                A fresh verification link has been sent to your email address.
                            </div>
                        @endif

                        <p class="mb-3">
                            Before proceeding, please check your email for a verification link.
                            If you did not receive the email, you can request another one below.
                        </p>

                        <form method="post" action="{{ route('verification.send') }}" class="recaptcha" novalidate>
                            @csrf
                            @include('partials.recaptcha')
                            <div class="form-group">
                                <button type="submit" class="digi-btn btn-fill-primary btn-fluid btn-primary secondary"
                                        name="submit-btn">Click here to request another
                                </button>
                            </div>
                        </form>
                    </div>
                    <p class="mt-3 mb-0 text-center"><small>
                            Issues with the verification process or entered the wrong email?
                            <br>Please sign up with <a href="{{ route('register-retry') }}">another</a> email
                            address.</small></p>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>