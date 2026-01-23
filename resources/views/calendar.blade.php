<x-app-layout>
    <section class="banner page">
        <div class="container-fluid">
            <div class="banner-content">
                <h1 class="page-title mt-2">Put collaborative learning on your calendar</h1>
                <p class="page-lead">
                    Find out what's coming up and when to apply or register for each course.</p>
            </div>
            <!-- graphic shapes -->
            <ul class="list-unstyled shape-group-pages">
                <li class="shape shape-1">
                    <img class="paralax-image" src="{{ asset('images/banner/egg-pic.png') }}"
                         alt="Egg Specimen Image">
                </li>
                <li class="shape shape-7">
                    <!-- calendar-bubble.png -->
                    <img src="{{ asset('images/others/bubble-purple.png') }}"
                         alt="Graphic of purple bubble"
                         style="opacity: 0.9;">
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
    <!-- Calendar Start -->
    <section class="section mt-5">
        <div class="container">
            <div class="row justify-content-center">
                <iframe
                    class="mt-5"
                    src="https://calendar.google.com/calendar/embed?src={{ config('config.google_course_calender_id') }}&ctz=America%2FNew_York"
                    style="border: 0"
                    width="800"
                    height="600"
                    title="Digitization Academy course calendar"
                ></iframe>
            </div>
        </div>
    </section>
</x-app-layout>
