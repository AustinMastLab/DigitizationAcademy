<x-app-layout>
    <section class="banner page">
        <div class="container-fluid">
            <div class="banner-content">
                <h1 class="page-title mt-3">Level up for life with courses</h1>
                <p class="page-lead">
                    We deliver a recurring set of courses on activities that add value to biodiversity
                    observations and specimens with a goal of making biodiversity collections even more relevant to
                    science, society, and Life.
                </p>
            </div>
            <!-- graphic shapes -->
            <ul class="list-unstyled shape-group-pages">
                <li class="shape shape-1">
                    <img class="paralax-image" src="{{ asset('images/banner/beetle-pic.png') }}" alt="Beetle Specimen Image">
                </li>
                <li class="shape shape-7">
                    <img src="{{ asset('images/others/bubble-teal.png') }}"
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
    <!--=       Course Cards  Start    =-->
    <section class="section-padding-equal">
        <div class="container">
            <div class="row">
                <!-- Nav pills -->
                <ul style="z-index: 2" class="nav nav-pills mb-5 justify-content-center courses-pills" id="coursesPills"
                    role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link course-tab digi-btn btn-fill-primary secondary"
                                id="all-tab" data-bs-toggle="pill"
                                data-url="{{ route('catalog.show', ['type' => 'all']) }}" type="button" role="tab"
                                aria-controls="courseContent" aria-selected="false">All
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link course-tab digi-btn btn-fill-primary secondary active"
                                id="upcoming-tab" data-bs-toggle="pill"
                                data-url="{{ route('catalog.show', ['type' => 'upcoming']) }}" type="button" role="tab"
                                aria-controls="courseContent" aria-selected="true">Upcoming
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link course-tab digi-btn btn-fill-primary secondary"
                                id="past-tab" data-bs-toggle="pill"
                                data-url="{{ route('catalog.show', ['type' => 'past']) }}" type="button" role="tab"
                                aria-controls="courseContent" aria-selected="false">Past
                        </button>
                    </li>
                </ul>

                <!-- Tab content container -->
                <div class="tab-content" id="courseTabContent">
                    <div class="tab-pane fade show active" id="courseContent" role="tabpanel" aria-labelledby="upcoming-tab">
                        <!-- Content will be loaded here -->
                        <div class="content-loader">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>