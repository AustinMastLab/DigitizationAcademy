<x-app-layout>
    <!--              Hero Banner            -->
    <section class="banner course bg-dark">
        <div class="container-fluid">
            <div class="breadcrumb">
                <ul class="list-unstyled">
                    <li class="course"><a href="{{ route('home') }}">Home</a></li>
                    <li class="course"><a href="{{ route('catalog.index') }}">Course Catalog</a></li>
                    <li class="active">{{ $course->title }}</li>
                </ul>
            </div>

            <div class="banner-content">
                <h1 class="course-title mb-4">{{ $course->title }}</h1>
                <p class="course-lead">{{ $course->description }}</p>
                <p>Led by {{ $course->instructor }}</p>
                <p class="mt-5">{{ $nextOffering }}</p>
                <p class="course-info mt-5">{{ $course->courseType->type }} course | Online | Free
                    <span class="info-tooltip">
                        <i class="far fa-info-circle"></i>
                        <span class="tooltip-text">
                            Course fees underwritten by the National Science Foundation.
                        </span>
                    </span> | Offered in {{ $course->language }}
                    @if($buttonDate && !is_null($course->events->first()->form_link))
                        | <span class="info-tooltip">
                            <a href="{{ $course->events->first()->form_link }}"
                             class="digi-btn btn-fill-primary course d-inline-flex align-items-center justify-content-center">
                            {{ $course->present()->registrationButtonText() }}
                            </a>
                            <span class="tooltip-text button-tooltip">
                                Application closes: {{ $course->events->first()->form_end_date->format('F j, Y') }}
                            </span>
                        </span>
                    @endif
                </p>
            </div>

            <!-- graphic shapes -->
            <ul class="list-unstyled shape-group-18">
                <li class="shape shape-1" style="z-index: -1;">
                    <img src="{{ $course->present()->pageImage() }}" style="z-index: -1" alt="Header image for course">
                </li>
            </ul>

        </div><!-- container -->
    </section>

    <!--    Tab Nav       -->
    <section class="section section-padding-equal faq-area">
        <div class="container">
            <div class="row text-center">
                <ul class="nav nav-pills mb-5 justify-content-center" id="course-nav" role="tablist">
                    @if($course->course_type_id === config('config.course_type.12_hour'))
                        <x-course-button class="active" value="objectives"/>
                        <x-course-button value="syllabus"/>
                    @endif

                    @if($showOfferingsPane)
                        <x-course-button class="{{ $course->course_type_id === 1 ? 'active' : '' }}" value="offerings"/>
                    @endif

                    <x-course-button class="{{ $course->course_type_id === 1 && !$showOfferingsPane ? 'active' : '' }}" value="expert-panel"/>

                    @if($course->present()->hasAssets())
                        <x-course-button value="resources"/>
                    @endif

                    @if($course->present()->hasVideo())
                        <x-course-button value="video"/>
                    @endif
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    @if($course->course_type_id === 2)
                        <div class="tab-pane fade show active" id="objectives" role="tabpanel"
                             aria-labelledby="objectives-tab">
                            <div class="section-heading course col-10 m-auto text-start">
                                {{ $course->objectives }}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="syllabus" role="tabpanel" aria-labelledby="syllabus-tab">
                            <div class="section-heading course" style="margin-bottom: 10px;">
                                <p id="syllabus-heading" class="subtitle text-rose text-center">Sample Syllabus</p>
                                <object
                                    data="{{ $course->present()->syllabus() }}"
                                    type="application/pdf"
                                    width="100%"
                                    height="600px"
                                    aria-labelledby="syllabus-heading"
                                    title="Sample syllabus PDF"
                                >
                                    <p>
                                        Your browser does not support embedded PDFs.
                                        <a href="{{ $course->present()->syllabus() }}">
                                            Download the sample syllabus PDF
                                        </a>
                                    </p>
                                </object>
                            </div>
                        </div>
                    @endif

                    @if($showOfferingsPane)
                        <div class="tab-pane fade {{ $offeringsActive ? 'show active' : '' }}"
                             id="offerings"
                             role="tabpanel"
                             aria-labelledby="offerings-tab">
                            <div class="section-heading course">
                                @if($hasEvents)
                                    @foreach($course->events as $event)
                                        <x-course-offerings :course="$course" :event="$event"/>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="tab-pane fade {{ $course->course_type_id === 1 && !$showOfferingsPane ? 'show active' : '' }}"
                         id="expert-panel"
                         role="tabpanel"
                         aria-labelledby="expert-panel-tab">
                        <div class="section-heading course text-start">
                            <span class="subtitle text-rose">
                                {{ $course->expert_panel_headline }}
                            </span>
                            <p>{{ $course->expert_panel_copy }}</p>
                            <div class="expert-logos">
                                <img src="{{ $course->present()->expertPanelImage() }}" style="z-index: -1" alt="Header image for course">
                            </div>
                            <span>{{ $course->expert_panelist_copy }}</span>
                        </div>
                    </div>

                    @if($course->present()->hasAssets())
                        <div class="tab-pane fade" id="resources" role="tabpanel" aria-labelledby="resources-tab">
                            <div class="col-10 m-auto">
                                <div class="row course-resources">
                                    <div class="col-md-6 ps-5 m-auto">
                                        <img src="{{ asset('images/logo/dk-logo.svg') }}"
                                             alt="Digitization Knowledgebase Logo"
                                             class="img-fluid d-block"/>
                                        <p class="text-start mt-5">
                                            Explore topics covered in this course through the Digitization
                                            Knowledgebase, a curated navigation tool for digitization resources from
                                            across the world. The Digitization Knowledgebase provides links to relevant
                                            content, organized by topic for easy exploration.
                                        </p>
                                    </div>
                                    <div class="col-md-6 d-flex">
                                        <ul class="nav nav-pills flex-column w-100">
                                            @foreach($course->assets as $asset)
                                                <x-assets :asset="$asset"/>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($course->present()->hasVideo())
                        <div class="tab-pane fade" id="video" role="tabpanel" aria-labelledby="video-tab">
                            <span class="subtitle text-rose mb-5">
                                Recordings of this and all our 2-hour courses are available on
                                <a href="{{ $course->video }}" target="_blank">Vimeo</a>
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</x-app-layout>