(function () {
    'use strict';

    let googleTranslateLoadPromise = null;

    function loadGoogleTranslate() {
        if (window.google && window.google.translate) {
            return Promise.resolve();
        }

        if (googleTranslateLoadPromise) {
            return googleTranslateLoadPromise;
        }

        googleTranslateLoadPromise = new Promise(function (resolve, reject) {
            window.googleTranslateElementInit = function () {
                try {
                    new window.google.translate.TranslateElement(
                        {
                            pageLanguage: 'en',
                            autoDisplay: false,
                            includedLanguages: '',
                            layout: window.google.translate.TranslateElement.FloatPosition.TOP_LEFT,
                        },
                        'google_translate_element'
                    );
                    resolve();
                } catch (e) {
                    reject(e);
                }
            };

            const s = document.createElement('script');
            s.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
            s.async = true;
            s.onerror = function () {
                reject(new Error('Google Translate script failed to load'));
            };
            document.body.appendChild(s);
        });

        return googleTranslateLoadPromise;
    }

    function changeLanguage(langCode) {
        if (!langCode) return;

        loadGoogleTranslate().then(function () {
            const combo = document.querySelector('.goog-te-combo');
            if (combo) {
                combo.value = langCode;
                combo.dispatchEvent(new Event('change'));
            }
        }).catch(function () {
            // Intentionally quiet (scan-friendly / no noisy console)
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Trigger lazy load when user shows intent around the Language menu item
        const languageMenu = document.querySelector('.google-menu');
        if (languageMenu) {
            const triggerOnce = function () {
                loadGoogleTranslate().catch(function () {});
            };

            // Hover (mouse/pen)
            languageMenu.addEventListener('pointerenter', triggerOnce, { once: true });

            // Keyboard focus
            languageMenu.addEventListener('focusin', triggerOnce, { once: true });

            // Touch / click
            languageMenu.addEventListener('click', function () {
                triggerOnce();
            }, { passive: true });
        }

        // Language item clicks
        document.querySelectorAll('.language-option').forEach(function (option) {
            option.addEventListener('click', function (e) {
                e.preventDefault();
                const langCode = this.getAttribute('data-lang');
                changeLanguage(langCode);
            });
        });
    });

    // Expose if something else calls it
    window.changeLanguage = changeLanguage;
})();
