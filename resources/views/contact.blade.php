<x-app-layout>
    <section class="banner page">
        <div class="container-fluid">
            <div class="banner-content">
                <h1 class="page-title mt-5">We would love to hear from you</h1>
                <p class="page-lead">
                    Ask a question, share your thoughts, or suggest a topic you'd like to explore further.
                </p>
            </div>
            <!-- graphic shapes -->
            <ul class="list-unstyled shape-group-pages">
                <li class="shape shape-1">
                    <img class="paralax-image" src="{{ asset('images/banner/salmon-pic.png') }}" alt="Salmon Specimen Image">
                </li>
                <li class="shape shape-7">
                    <img
                            src="{{ asset('images/others/bubble-salmon.png') }}"
                            alt="Graphic of purple bubble"
                            style="opacity: 0.9;"
                    >
                </li>
            </ul>
        </div><!-- container -->
    </section>

    <!-- shape groups -->
    <ul class="shape-group-6 list-unstyled">
        <li class="shape shape-1">
            <img src="{{ asset('images/logo/watermarkApr2025.svg') }}" alt="Bubble">
        </li>
    </ul>

    <!-- Contact Area Start -->
    <section class="section section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2 col-sm-12">
                    <div class="contact-form-box shadow-box mb--30 px-sm-4">
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert" aria-live="polite" aria-atomic="true">
                                <p class="mb-2"><strong>Please correct the following:</strong></p>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="post" action="{{ route('contact.store') }}" class="recaptcha" novalidate>
                            @csrf

                            <div class="form-group mb-3">
                                <label for="contact-name">Name</label>
                                <input
                                        id="contact-name"
                                        type="text"
                                        class="form-control"
                                        name="name"
                                        value="{{ old('name') }}"
                                        autocomplete="name"
                                        required
                                >
                            </div>

                            <div class="form-group mb-3">
                                <label for="contact-email">Email</label>
                                <input
                                        id="contact-email"
                                        type="email"
                                        class="form-control"
                                        name="email"
                                        value="{{ old('email') }}"
                                        autocomplete="email"
                                        required
                                >
                            </div>

                            <div class="form-group mb--40">
                                <label for="contact-message">Message</label>
                                <textarea
                                        id="contact-message"
                                        class="form-control textarea"
                                        name="message"
                                        cols="30"
                                        rows="4"
                                        autocomplete="off"
                                        required
                                >{{ old('message') }}</textarea>
                            </div>

                            @include('partials.recaptcha')

                            <div class="form-group">
                                <button
                                        type="submit"
                                        class="digi-btn btn-fill-primary secondary btn-fluid btn-primary"
                                        name="submit-btn"
                                >
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>