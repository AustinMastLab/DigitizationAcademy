(function (window, document, $, undefined) {
    'use strict';

    let axilInit = {
        i: function (e) {
            axilInit.s();
            axilInit.methods();
        },

        s: function (e) {
            this._window = $(window),
                this._document = $(document),
                this._body = $('body'),
                this._html = $('html')
        },

        methods: function (e) {
            axilInit.w();
            axilInit.axilMasonary();
            axilInit.contactForm();
            axilInit.axilBackToTop();
            axilInit.stickyHeaderMenu();
            axilInit.mobileMenuActivation();
            axilInit.counterUp();
            axilInit.axilSlickActivation();
            axilInit.magnificPopupActivation();
            axilInit.countdownInit('.countdown', '2022/12/01');
            axilInit.tiltAnimation();
            axilInit.menuLinkActive();
            axilInit.onePageNav();
            axilInit.pricingPlan();
            axilInit.marqueImages();
            axilInit.axilHover();
            axilInit.onePageTopFixed();
            axilInit.blogModalActivation();
            axilInit.portfolioModalActivation();
            axilInit.caseModalActivation();
            axilInit.courseCatalog();
            axilInit.logout();

        },

        w: function (e) {
            this._window.on('load', axilInit.l).on('scroll', axilInit.res)
        },

        axilMasonary: function () {
            $('.axil-isotope-wrapper').imagesLoaded(function () {
                // filter items on button click
                $('.isotope-button').on('click', 'button', function () {
                    let filterValue = $(this).attr('data-filter');
                    $grid.isotope({
                        filter: filterValue
                    });
                });

                // init Isotope
                let $grid = $('.isotope-list').isotope({
                    itemSelector: '.project',
                    filter: '.future',
                    percentPosition: true,
                    transitionDuration: '0.7s',
                    layoutMode: 'fitRows',
                    masonry: {
                        // use outer width of grid-sizer for columnWidth
                        columnWidth: 1,
                    }
                });
            });

            $('.isotope-button button').on('click', function (event) {
                $(this).siblings('.is-checked').removeClass('is-checked');
                $(this).addClass('is-checked');
                event.preventDefault();
            });

            // Masonry
            let galleryIsoContainer = $('#no-equal-gallery');
            if (galleryIsoContainer.length) {
                let blogGallerIso = galleryIsoContainer.imagesLoaded(function () {
                    blogGallerIso.isotope({
                        itemSelector: '.no-equal-item',
                        masonry: {
                            columnWidth: '.no-equal-item'
                        }
                    });
                });
            }
        },


        contactForm: function () {
            $('.digi-contact-form').on('submit', function (e) {
                e.preventDefault();
                let _self = $(this);
                let _selector = _self.closest('input,textarea');
                _self.closest('div').find('input,textarea').removeAttr('style');
                _self.find('.error-msg').remove();
                _self.closest('div').find('button[type="submit"]').attr('disabled', 'disabled');
                let data = $(this).serialize();
                $.ajax({
                    url: 'mail.php',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    success: function (data) {
                        _self.closest('div').find('button[type="submit"]').removeAttr('disabled');
                        if (data.code === false) {
                            _self.closest('div').find('[name="' + data.field + '"]');
                            _self.find('.btn-primary').after('<div class="error-msg"><p>*' + data.err + '</p></div>');
                        } else {
                            $('.error-msg').hide();
                            $('.form-group').removeClass('focused');
                            _self.find('.btn-primary').after('<div class="success-msg"><p>' + data.success + '</p></div>');
                            _self.closest('div').find('input,textarea').val('');

                            setTimeout(function () {
                                $('.success-msg').fadeOut('slow');
                            }, 5000);
                        }
                    }
                });
            });
        },

        axilBackToTop: function () {
            let btn = $('#backto-top');

            function setBackToTopFocusable(isFocusable) {
                if (isFocusable) {
                    btn.attr('aria-hidden', 'false');
                    btn.attr('tabindex', '0');
                } else {
                    btn.attr('aria-hidden', 'true');
                    btn.attr('tabindex', '-1');
                }
            }

            // Ensure correct initial state (in case markup differs per page)
            setBackToTopFocusable(btn.hasClass('show'));

            $(window).on('scroll', function () {
                if ($(window).scrollTop() > 300) {
                    btn.addClass('show');
                    setBackToTopFocusable(true);
                } else {
                    btn.removeClass('show');
                    setBackToTopFocusable(false);
                }
            });

            btn.on('click', function (e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, '300');
            });
        },

        stickyHeaderMenu: function () {

            $(window).on('scroll', function () {
                // Sticky Class Add
                if ($('body').hasClass('sticky-header')) {
                    let stickyPlaceHolder = $('#digi-sticky-placeholder'),
                        menu = $('.digi-mainmenu'),
                        menuH = menu.outerHeight(),
                        topHeaderH = $('.digi-header-top').outerHeight() || 0,
                        targrtScroll = topHeaderH + 200;
                    if ($(window).scrollTop() > targrtScroll) {
                        menu.addClass('digi-sticky');
                        stickyPlaceHolder.height(menuH);
                    } else {
                        menu.removeClass('digi-sticky');
                        stickyPlaceHolder.height(0);
                    }
                }
            });
        },

        mobileMenuActivation: function (e) {

            $('.menu-item-has-children > a').on('click', function (e) {

                let targetParent = $(this).parents('.mainmenu-nav'),
                    target = $(this).siblings('.digi-submenu'),
                    targetSiblings = $(this).parent('.menu-item-has-children').siblings().find('.digi-submenu');

                if (targetParent.hasClass('offcanvas')) {
                    $(target).slideToggle(400);
                    $(targetSiblings).slideUp(400);
                    $(this).parent('.menu-item-has-children').toggleClass('open');
                    $(this).parent('.menu-item-has-children').siblings().removeClass('open');
                }

            });

            function resizeClassAdd() {
                if (window.matchMedia('(min-width: 992px)').matches) {
                    $('body').removeClass('mobilemenu-active');
                    $('#mobilemenu-popup').removeClass('offcanvas show').removeAttr('style');
                    $('.digi-mainmenu .offcanvas-backdrop').remove();
                    $('.digi-submenu').removeAttr('style');
                } else {
                    $('body').addClass('mobilemenu-active');
                    $('#mobilemenu-popup').addClass('offcanvas');
                    $('.menu-item-has-children > a').on('click', function (e) {
                        e.preventDefault();
                    });
                }
            }

            $(window).on('resize', function () {
                resizeClassAdd();
            });

            resizeClassAdd();
        },

        counterUp: function () {

            let elementSelector = $('.count');
            elementSelector.each(function () {
                elementSelector.appear(function (e) {
                    let el = this;
                    let updateData = $(el).attr("data-count");
                    let od = new Odometer({
                        el: el,
                        format: 'd',
                        duration: 2000
                    });
                    od.update(updateData);
                });
            });
        },

        axilSlickActivation: function (e) {
            $('.slick-slider').slick();
        },

        magnificPopupActivation: function () {

            let yPopup = $('.popup-youtube');
            if (yPopup.length) {
                yPopup.magnificPopup({
                    disableOn: 300,
                    type: 'iframe',
                    mainClass: 'mfp-fade',
                    removalDelay: 160,
                    preloader: false,
                    fixedContentPos: false
                });
            }
        },

        countdownInit: function (countdownSelector, countdownTime) {
            let eventCounter = $(countdownSelector);
            if (eventCounter.length) {
                eventCounter.countdown(countdownTime, function (e) {
                    $(this).html(
                        e.strftime(
                            "<div class='countdown-section'><div><div class='countdown-number'>%D</div> <div class='countdown-unit'>Day%!D</div> </div></div><div class='countdown-section'><div><div class='countdown-number'>%H</div> <div class='countdown-unit'>Hour%!H</div> </div></div><div class='countdown-section'><div><div class='countdown-number'>%M</div> <div class='countdown-unit'>Minutes</div> </div></div><div class='countdown-section'><div><div class='countdown-number'>%S</div> <div class='countdown-unit'>Seconds</div> </div></div>"
                        )
                    );
                });
            }
        },

        tiltAnimation: function () {
            let _tiltAnimation = $('.paralax-image');
            if (_tiltAnimation.length) {
                _tiltAnimation.tilt({
                    max: 12,
                    speed: 1e3,
                    easing: 'cubic-bezier(.03,.98,.52,.99)',
                    transition: !1,
                    perspective: 1e3,
                    scale: 1
                })
            }
        },

        menuLinkActive: function () {
            let currentPage = location.pathname.split("/"),
                current = currentPage[currentPage.length - 1];

            $('.mainmenu li a, .main-navigation li a').each(function () {
                let $this = $(this);
                if ($this.attr('id') === current) {
                    $this.addClass('active');
                    $this.parents('.menu-item-has-children').addClass('menu-item-open')
                }
            });
        },

        onePageNav: function () {
            $('#onepagenav').onePageNav({
                currentClass: 'current',
                changeHash: false,
                scrollSpeed: 500,
                scrollThreshold: 0.2,
                filter: '',
                easing: 'swing',
            });
        },

        pricingPlan: function () {
            let yearlySelectBtn = $('#yearly-plan-btn'),
                monthlySelectBtn = $('#monthly-plan-btn'),
                monthlyPrice = $('.monthly-pricing'),
                yearlyPrice = $('.yearly-pricing'),
                buttonSlide = $('#pricing-checkbox');

            $(monthlySelectBtn).on('click', function () {
                buttonSlide.prop('checked', true);
                $(this).addClass('active').parent('.nav-item').siblings().children().removeClass('active');
                monthlyPrice.css('display', 'block');
                yearlyPrice.css('display', 'none');

            });

            $(yearlySelectBtn).on('click', function () {
                buttonSlide.prop('checked', false);
                $(this).addClass('active').parent('.nav-item').siblings().children().removeClass('active');
                monthlyPrice.css('display', 'none');
                yearlyPrice.css('display', 'block');
            });

            $(buttonSlide).change(function () {
                if ($('input[id="pricing-checkbox"]:checked').length > 0) {
                    monthlySelectBtn.addClass('active');
                    yearlySelectBtn.removeClass('active');
                    monthlyPrice.css('display', 'block');
                    yearlyPrice.css('display', 'none');

                } else {
                    yearlySelectBtn.addClass('active');
                    monthlySelectBtn.removeClass('active');
                    monthlyPrice.css('display', 'none');
                    yearlyPrice.css('display', 'block');

                }
            });
        },

        marqueImages: function () {
            $('.marque-images').each(function () {
                let t = 0;
                let i = 1;
                let $this = $(this);
                setInterval(function () {
                    t += i;
                    $this.css('background-position-x', -t + 'px');
                }, 10);
            });
        },

        axilHover: function () {
            $('.services-grid, .counterup-progress, .testimonial-grid, .pricing-table, .brand-grid, .blog-list, .about-quality, .team-grid, .splash-hover-control').mouseenter(function () {
                let self = this;
                setTimeout(function () {
                    $('.services-grid.active, .counterup-progress.active, .testimonial-grid.active, .pricing-table.active, .brand-grid.active, .blog-list.active, .about-quality.active, .team-grid.active, .splash-hover-control.active').removeClass('active');
                    $(self).addClass('active');
                }, 0);

            });
        },

        onePageTopFixed: function () {
            if ($('.onepagefixed').length) {
                let fixedElem = $('.onepagefixed'),
                    distance = fixedElem.offset().top - 100,
                    $window = $(window),
                    totalDistance = $('.service-scroll-navigation-area').outerHeight() + distance;

                $(window).on('scroll', function () {
                    if ($window.scrollTop() >= distance) {
                        fixedElem.css({
                            position: 'fixed',
                            left: '0',
                            right: '0',
                            top: '0',
                            zIndex: '5'
                        });
                    } else {
                        fixedElem.removeAttr('style');
                    }

                    if ($window.scrollTop() >= totalDistance) {
                        fixedElem.removeAttr('style');
                    }
                });
            }
        },

        blogModalActivation: function () {

            let modalBox = $('.op-blog-modal');
            let blogList = $('.blog-list');
            let modalClose = modalBox.find('.close');

            if ($('body').hasClass('onepage-template')) {

                blogList.each(function () {

                    let $this = $(this);
                    let buttons = $this.find('.post-thumbnail a, .title a, .more-btn');
                    let mainImg = $this.find('.modal-thumb');
                    let title = $this.find('.title');
                    let paragraph = $this.find('.post-content p');
                    let socialShare = $this.find('.blog-share');

                    buttons.on('click', function (e) {
                        $('body').addClass('op-modal-open');
                        modalBox.addClass('open');
                        mainImg.clone().appendTo('.op-modal-content .post-thumbnail');
                        title.clone().appendTo('.op-modal-content .post-content');
                        paragraph.clone().appendTo('.op-modal-content .post-content');
                        socialShare.clone().appendTo('.op-modal-content .post-content');
                        e.preventDefault();

                    })

                });

                modalClose.on('click', function (e) {
                    $('body').removeClass('op-modal-open');
                    modalBox.removeClass('open');
                    modalBox.find('.op-modal-content .post-content').html('');
                    modalBox.find('.op-modal-content .post-thumbnail').html('');
                    e.preventDefault();
                });

                $('#onepagenav li a').on('click', function () {
                    let popupMenuWrap = $('#mobilemenu-popup .mobile-menu-close, .header-offcanvasmenu .btn-close');
                    if ($('#mobilemenu-popup, .header-offcanvasmenu').hasClass('offcanvas')) {
                        popupMenuWrap.trigger('click');

                    }
                });
            }
        },

        portfolioModalActivation: function () {

            let modalBox = $('.op-portfolio-modal');
            let projectList = $('.project-grid');
            let modalClose = modalBox.find('.close');

            if ($('body').hasClass('onepage-template')) {

                projectList.each(function () {

                    let $this = $(this);
                    let buttons = $this.find('.thumbnail a, .title a');
                    let mainImg = $this.find('.thumbnail .modal-thumb');
                    let title = $this.find('.title');
                    let paragraph = $this.find('.content p');
                    let socialShare = $this.find('.project-share');
                    buttons.on('click', function (e) {
                        $('body').addClass('op-modal-open');
                        modalBox.addClass('open');
                        mainImg.clone().appendTo('.op-modal-content .portfolio-thumbnail');
                        title.clone().appendTo('.op-modal-content .portfolio-content');
                        paragraph.clone().appendTo('.op-modal-content .portfolio-content');
                        socialShare.clone().appendTo('.op-modal-content .portfolio-content');
                        e.preventDefault();

                    })

                });

                modalClose.on('click', function (e) {
                    $('body').removeClass('op-modal-open');
                    modalBox.removeClass('open');
                    modalBox.find('.op-modal-content .portfolio-content').html('');
                    modalBox.find('.op-modal-content .portfolio-thumbnail').html('');
                    e.preventDefault();
                });
            }
        },

        caseModalActivation: function () {

            let modalBox = $('.op-case-modal');
            let caseList = $('.case-study-featured');
            let modalClose = modalBox.find('.close');

            if ($('body').hasClass('onepage-template')) {

                caseList.each(function () {

                    let $this = $(this);
                    let buttons = $this.find('.digi-btn');
                    let title = $this.find('.title');
                    let paragraph = $this.find('.section-heading p');

                    buttons.on('click', function (e) {
                        $('body').addClass('op-modal-open');
                        modalBox.addClass('open');
                        title.clone().appendTo('.op-modal-content .case-content');
                        paragraph.clone().appendTo('.op-modal-content .case-content');
                        e.preventDefault();

                    })

                });

                modalClose.on('click', function (e) {
                    $('body').removeClass('op-modal-open');
                    modalBox.removeClass('open');
                    modalBox.find('.op-modal-content .case-content').html('');
                    e.preventDefault();
                });
            }
        },

        courseCatalog: function () {
            // Load initial content when page loads
            let initialUrl = $('#upcoming-tab').data('url');
            loadTabContent(initialUrl);

            // Ensure initial ARIA labeling matches the initially active tab
            $('#courseContent').attr('aria-labelledby', 'upcoming-tab');

            // Handle pill click events
            $('.course-tab').on('click', function (e) {
                e.preventDefault();

                // Keep ARIA in sync with the selected tab (screen reader correctness)
                const $tabs = $('.course-tab[role="tab"]');
                $tabs.attr('aria-selected', 'false');
                $(this).attr('aria-selected', 'true');

                // This page uses one shared tabpanel; ensure it is labelled by the active tab
                $('#courseContent').attr('aria-labelledby', this.id);

                // Load content based on data-url attribute
                let url = $(this).data('url');
                loadTabContent(url);
            });

            function loadTabContent(url) {
                $('#courseContent').html('<div class="content-loader text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (response) {
                        $('#courseContent').html(response);
                    },
                    error: function (xhr, status, error) {
                        $('#courseContent').html('<div class="alert alert-danger">Error loading content. Please try again.</div>');
                        console.error("Error loading content:", error);
                    }
                });
            }
        },
        logout: function () {
            $('#logout').click(function(e){
                e.preventDefault();
                $('#logout-form').submit();
            });
        }
    }
    axilInit.i();

})(window, document, jQuery);
