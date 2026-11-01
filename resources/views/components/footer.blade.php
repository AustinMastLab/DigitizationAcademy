<footer class="footer-area section-padding-equal mt-5 pb-0">
    <div class="container">
        <div class="footer-top">
            <div class="footer-social-link pt-3 mt-4">
                <!-- sponsor logos -->
                <ul class="list-unstyled">
                    <li><a href="https://digitizationacademy.org" target="_blank">
                            <img src="{{ Vite::asset('resources/images/logo/logo.svg') }}"
                                 alt="Digitization Academy"
                                 width="175px" style="filter:grayscale(99%);"></a></li>
                    <li><a href="https://www.idigbio.org/" target="_blank">
                            <img
                                    src="{{ Vite::asset('resources/images/logo/logo-idigbio.svg') }}"
                                    alt="IDigBio"
                                    style="z-index:1; display:block; position: relative;"
                                    width="175px"></a></li>
                    <li><a href="https://idiginfo.org/" target="_blank">
                            <img src="{{ Vite::asset('resources/images/logo/idiginfo.png') }}"
                                 style="z-index:1; display:block; position: relative;"
                                 alt="iDigInfo"
                                 width="175px"></a></li>
                    <li><a href="https://www.fsu.edu" target="_blank">
                            <img src="{{ Vite::asset('resources/images/logo/fsu-logo.svg') }}" alt="FSU"
                                 style="z-index:1; display:block; position: relative;"
                                 width="100px"></a>
                    </li>
                </ul>


            </div>
        </div>

        <hr class="mt-0 mb-5">

        <div class="footer-main">
            <div class="row">
                <div class="col-8 m-auto">
                    <div class="footer-widget">
                        <div class="footer-newsletter">
                            <h6 class="widget-title">Sponsorships</h6>
                            <p>iDigBio is funded by grants from the National Science Foundation [DBI-1115210
                                (2011-2018), DBI-1547229 (2016-2022), & DBI-2027654 (2021-2026)]. Any opinions,
                                findings, and conclusions or recommendations expressed in this material are those of
                                the author(s) and do not necessarily reflect the views of the National Science
                                Foundation.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="row">
                <div class="col-md-8">
                    <x-copyright />
                </div>
            </div>
        </div>
    </div><!-- container -->
</footer>

