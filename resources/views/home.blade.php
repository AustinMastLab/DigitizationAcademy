<x-app-layout :title="'Home | ' . config('app.name', 'FSU Digitization Academy')">
    <!-- shape groups -->
    <ul class="shape-group-6 list-unstyled">
        <li class="shape shape-1">
            <img src="{{ asset('images/logo/watermarkApr2025.svg') }}" alt="Bubble">
        </li>
    </ul>
    <!--              Hero Banner            -->
    <section class="banner index">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="banner-content">
                        <h1 class="title mb-2">Explore. Discover. Connect.</h1>
                        <p class="text-light lead">
                            Catalyzing excellence in the creation of digital data about
                            biodiversity.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- graphic shapes -->
        <ul class="list-unstyled shape-group-home">
            <li class="shape shape-1">
                <img src="{{ asset('images/banner/banner-shape-1.png') }}" alt="Image of a scanned butterfly specimen.">
            </li>
            <li class="shape shape-2">
                <img src="{{ asset('images/banner/banner-shape-2.png') }}" alt="Graphical design element of a curved line">
            </li>
            <li class="shape shape-7" style="opacity:.7">
                <img src="{{ asset('images/others/bubble-26.png') }}" alt="Graphical design element of a curved line">
            </li>
        </ul>
    </section>

    <!--    accordion approach 3 Tiers       -->
    <section class="section section-padding-equal faq-area">
        <div class="col-10 m-auto mt-5">
            <div class="row">
                <div class="col-lg-5 col-xl-4">
                    <div class="section-heading">
                        <span class="subtitle">A strategy built for you.</span>
                        <h3 class="title">Our Approach</h3>
                        <p>Our offerings combine these three elements to level you up on a topic. We aim to provision
                            you with more than an academic understanding. Leave the course with new inspiration and
                            connections to a cohort of professionals from around the world who are tackling the same
                            challenges.</p>
                        <p>
                            These online learning opportunities are available to
                            everyone from the world’s biodiversity and research
                            communities. We admit about 25 students to each of
                            our 12-hour courses to ensure that everyone has a
                            chance to contribute. Our 2-hour courses are open to
                            everyone who registers.
                        </p>
                        <a href="{{ route('catalog.index') }}"
                           class="btn digi-btn btn-lg btn-fill-primary secondary mb-1 mt-3">
                            Course Catalog
                        </a>
                        <br class="clearfix"/>
                    </div>
                </div>

                <div class="col-lg-7 col-xl-8">
                    <div class="faq-accordion">
                        <div class="accordion" id="accordion">
                            <div class="accordion-item">
                                <h6 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#faq1" aria-expanded="false" aria-controls="faq1">
                                        <span class="acc_number"><img
                                                    src="{{ asset('images/logo/leaf-left.svg') }}"
                                                    width="100px" style="margin-top: -25px;" alt="IDig Bio Leaves"> </span>
                                        <h6>Our systematic overview builds the foundation.</h6>
                                    </button>
                                </h6>

                                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#accordion">
                                    <div class="accordion-body">
                                        <p>We deliver content featuring major dimensions of a topic in engaging ways. All content advances a core set of carefully curated learning objectives.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h6 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
                                        <span class="acc_number"><img
                                                    src="{{ asset('images/logo/leaf-yellow-orange-center.png') }}"
                                                    width="100px"
                                                    style="margin-top: -25px;" alt="IDig Bio Leaf"> </span>
                                        <h6>Your experience provides the nuance and motivation.</h6>
                                    </button>
                                </h6>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#accordion">
                                    <div class="accordion-body">
                                        <p>We aspire to create a learning community with each offering where you feel empowered to share your experience for everyone’s enrichment.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h6 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
                                        <span class="acc_number"><img
                                                    src="{{ asset('images/logo/leaf-right.svg') }}"
                                                    width="100px" style="margin-top: -25px;" alt="IDig Bio Leaf"> </span>
                                        <h6>And an expert panel identifies the leading edge of the possible.</h6>
                                    </button>
                                </h6>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#accordion">
                                    <div class="accordion-body">
                                        <p>We feature thought leaders or tool providers who share their latest developments on the topic and address questions from participants.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!--=        Experience Counts Banner   =-->
    <section class="section section-padding-equal mt-2">
        <div class="col-10 m-auto mt-5 mb--50">
            <div class="row align-items-center">
                <div class="section-heading heading-left mid-section">
                    <span class="subtitle mt-5">Built on iDigBio’s professional development experience.</span>
                    <h3 class="title">Deep experience with digitization and training.</h3>
                    <p class="text-black">Since its founding in 2012, iDigBio has offered over 100 training opportunities, and
                        many involved the Digitization Academy’s core staff. iDigBio is the US National Science Foundation’s
                        National Resource for Advancing Digitization of Biodiversity Collections and home to the
                        Digitization Academy. The Digitization Academy offerings from iDigBio represent a standardized
                        strategy for delivery of a subset of iDigBio’s training options to ensure that we have all
                        high-value topics covered for you in complementary ways.</p>
                    <img src="{{ asset('images/others/idig-bio-leaves.svg') }}" id="idigLeaf" alt="IDig Bio Leaf">
                </div>
            </div>
        </div>
    </section>

    <!-- Opportunities -->
    <section class="section section-padding-equal">
        <div class="col-10 m-auto mt-5 mb--50">
            <div class="section-heading mt-5">
                <span class="subtitle">Opportunities to connect.</span>
                <h2>Receive updates and announcements.</h2>
                <p class="text-black" style="width: 80%;">Subscribe to the <a href="#" class="text-rose">Natural History Collections Listserv</a> to receive notifications when we open applications or add courses to the schedule. It's a great way to stay connected with the Digitization Academy and the broader natural history collections community</p>
            </div>
        </div>
    </section>

    <section class="section section-padding-equal">
        <article class="col-10 m-auto mt-5 mb--50">
            <div class="section-heading mt-5">
                <span class="subtitle">What participants are saying.</span>
                <h2>Evaluations help us further refine our courses with each iteration.</h2>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="testimonial-grid">
                        <p class="text-justify fst-italic">Topics were discussed in great detail. The instructors were very helpful and enthusiastic in answering queries and sharing resources. The activities were practical in that the questions were centered around our individual research.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="testimonial-grid">
                        <p class="text-justify fst-italic">This was a really well-put-together class that took us through the various steps and details of a collection digitization project. Great use of technology, easy to follow, well documented, and well-thought exercises. I am a big fan of audience participation, and that was well done.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="testimonial-grid">
                        <p class="text-justify fst-italic">I have not worked with digitization before, and
                            this taught me so many things I needed to learn and things I didn't even know I needed to
                            think about.</p>
                    </div>
                </div>
            </div>
        </article>
    </section>

</x-app-layout>
