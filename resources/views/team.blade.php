<x-app-layout :title="'Team | ' . config('app.name', 'FSU Digitization Academy')">
    <section class="banner page">
        <div class="container-fluid">
            <div class="banner-content">
                <h1 class="page-title mt-5">Meet the Digitization Academy team</h1>
                <p class="page-lead">
                    We love to share our experience with you and love to learn from your experience.<br>
                    Let’s talk!</p>
            </div>
            <!-- graphic shapes -->
            <ul class="list-unstyled shape-group-pages">
                <li class="shape shape-1">
                    <img class="paralax-image" src="{{ asset('images/banner/fish-pic.png') }}" alt="Fish Specimen Image">
                </li>
                <li class="shape shape-7">
                    <img src="{{ asset('images/others/bubble-blue.png') }}"
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
    <!--=====================================-->
    <!--=    Team section Start             =-->
    <!--=====================================-->
    <section class="section section-padding mt-0">
        <div class="container">
            <div class="row px-md-5">
                @foreach ($currentTeam as $team)
                    @if($loop->odd)
                        <x-team-member :team="$team"/>
                    @else
                        <x-team-member :team="$team" class="content-reverse" data-sal="slide-left"/>
                    @endif

                    @if($loop->last)
                        @php $isOdd = $loop->count % 2 !== 0 @endphp
                    @endif
                @endforeach
            </div>
        </div>

        <div class="container">
            <div class="row px-md-5">
                <p class="lead text-center">
                    We have had an ongoing team of experts and enthusiasts that make
                    the Digitization Academy work for you.<br>
                    <button type="button" class="btn digi-btn btn-lg btn-fill-primary mt-4" data-bs-toggle="collapse"
                            data-bs-target="#pastMembers">View past members
                    </button>
                </p>
                <div class="collapse" id="pastMembers">
                    <!-- start here -->
                    @foreach($formerTeam as $team)
                        @if($isOdd)
                            @if($loop->odd)
                                <x-team-member :team="$team" class="content-reverse"/>
                            @else
                                <x-team-member :team="$team"/>
                            @endif
                        @else
                            @if($loop->odd)
                                <x-team-member :team="$team"/>
                            @else
                                <x-team-member :team="$team" class="content-reverse"/>
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
