<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="FSU Digitization Academy">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ mix('images/logo/favicon.png') }}">

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XBKJG01XTP"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-XBKJG01XTP');
    </script>
</head>
<body id="main-wrapper" class="sticky-header">
<!-- BACK TO TOP -->
<a href="#main-wrapper" id="backto-top" class="back-to-top" type="button" aria-label="back to top">
    <i class="far fa-angle-double-up"></i></a>
@include('partials.notices')
    <!--=====================================-->
    <!--=             header               	=-->
    <!--=====================================-->
    <x-header-navbar/>
    <!--=====================================-->
    <!--=             Content             	=-->
    <!--=====================================-->
    {{ $slot }}

    <!--=====================================-->
    <!--=        Footer                  	=-->
    <!--=====================================-->
    <x-footer />

    <!--=====================================-->
    <!--=       Right slider mega menu      =-->
    <!--=====================================-->
    @auth
        <x-aside-menu />
    @endauth
@include('sweetalert::alert')
<script src="{{ mix('js/app.js') }}"></script>
@stack('scripts')
<!-- Language change does not work when compiled with Laravel-Mix scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load Google Translate API
        const googleTranslateScript = document.createElement('script');
        googleTranslateScript.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
        document.body.appendChild(googleTranslateScript);

        // Add event listeners to all language options
        document.querySelectorAll('.language-option').forEach(function(option) {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                const langCode = this.getAttribute('data-lang');
                changeLanguage(langCode);
            });
        });
    });

    // Google Translate languages mapping
    const googleTranslateLanguages = {
        'af': 'Afrikaans',
        'sq': 'Albanian',
        'am': 'Amharic',
        'ar': 'Arabic',
        'hy': 'Armenian',
        'as': 'Assamese',
        'ay': 'Aymara',
        'az': 'Azerbaijani',
        'bm': 'Bambara',
        'eu': 'Basque',
        'be': 'Belarusian',
        'bn': 'Bengali',
        'bho': 'Bhojpuri',
        'bs': 'Bosnian',
        'bg': 'Bulgarian',
        'ca': 'Catalan',
        'ceb': 'Cebuano',
        'zh-CN': 'Chinese (Simplified)',
        'zh-TW': 'Chinese (Traditional)',
        'co': 'Corsican',
        'hr': 'Croatian',
        'cs': 'Czech',
        'da': 'Danish',
        'dv': 'Dhivehi',
        'doi': 'Dogri',
        'nl': 'Dutch',
        'en': 'English',
        'eo': 'Esperanto',
        'et': 'Estonian',
        'ee': 'Ewe',
        'fil': 'Filipino (Tagalog)',
        'fi': 'Finnish',
        'fr': 'French',
        'fy': 'Frisian',
        'gl': 'Galician',
        'ka': 'Georgian',
        'de': 'German',
        'el': 'Greek',
        'gn': 'Guarani',
        'gu': 'Gujarati',
        'ht': 'Haitian Creole',
        'ha': 'Hausa',
        'haw': 'Hawaiian',
        'he': 'Hebrew',
        'hi': 'Hindi',
        'hmn': 'Hmong',
        'hu': 'Hungarian',
        'is': 'Icelandic',
        'ig': 'Igbo',
        'ilo': 'Ilocano',
        'id': 'Indonesian',
        'ga': 'Irish',
        'it': 'Italian',
        'ja': 'Japanese',
        'jv': 'Javanese',
        'kn': 'Kannada',
        'kk': 'Kazakh',
        'km': 'Khmer',
        'rw': 'Kinyarwanda',
        'ko': 'Korean',
        'kri': 'Krio',
        'ku': 'Kurdish',
        'ckb': 'Kurdish (Sorani)',
        'ky': 'Kyrgyz',
        'lo': 'Lao',
        'la': 'Latin',
        'lv': 'Latvian',
        'ln': 'Lingala',
        'lt': 'Lithuanian',
        'lg': 'Luganda',
        'lb': 'Luxembourgish',
        'mk': 'Macedonian',
        'mai': 'Maithili',
        'mg': 'Malagasy',
        'ms': 'Malay',
        'ml': 'Malayalam',
        'mt': 'Maltese',
        'mi': 'Maori',
        'mr': 'Marathi',
        'mni-Mtei': 'Meiteilon (Manipuri)',
        'lus': 'Mizo',
        'mn': 'Mongolian',
        'my': 'Myanmar (Burmese)',
        'ne': 'Nepali',
        'no': 'Norwegian',
        'ny': 'Nyanja (Chichewa)',
        'or': 'Odia (Oriya)',
        'om': 'Oromo',
        'ps': 'Pashto',
        'fa': 'Persian',
        'pl': 'Polish',
        'pt': 'Portuguese',
        'pa': 'Punjabi',
        'qu': 'Quechua',
        'ro': 'Romanian',
        'ru': 'Russian',
        'sm': 'Samoan',
        'sa': 'Sanskrit',
        'gd': 'Scots Gaelic',
        'nso': 'Sepedi',
        'sr': 'Serbian',
        'st': 'Sesotho',
        'sn': 'Shona',
        'sd': 'Sindhi',
        'si': 'Sinhala',
        'sk': 'Slovak',
        'sl': 'Slovenian',
        'so': 'Somali',
        'es': 'Spanish',
        'su': 'Sundanese',
        'sw': 'Swahili',
        'sv': 'Swedish',
        'tg': 'Tajik',
        'ta': 'Tamil',
        'tt': 'Tatar',
        'te': 'Telugu',
        'th': 'Thai',
        'ti': 'Tigrinya',
        'ts': 'Tsonga',
        'tr': 'Turkish',
        'tk': 'Turkmen',
        'ak': 'Twi',
        'uk': 'Ukrainian',
        'ur': 'Urdu',
        'ug': 'Uyghur',
        'uz': 'Uzbek',
        'vi': 'Vietnamese',
        'cy': 'Welsh',
        'xh': 'Xhosa',
        'yi': 'Yiddish',
        'yo': 'Yoruba',
        'zu': 'Zulu'

    };

    // Google Translate initialization function
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            autoDisplay: false,
            includedLanguages: '', // Include all languages
            layout: google.translate.TranslateElement.FloatPosition.TOP_LEFT
        }, 'google_translate_element');
    }

    // Function to change the language using modern Google Translate
    function changeLanguage(languageCode) {
        if (!languageCode) return;

        // Wait for Google Translate to load
        if (typeof google === 'undefined' || typeof google.translate === 'undefined') {
            setTimeout(function() { changeLanguage(languageCode); }, 1000);
            return;
        }

        // First approach: Try the modern selector structure
        const iframe = document.querySelector('.goog-te-menu-frame');

        if (iframe) {
            // Try to access the iframe content
            try {
                const innerDoc = iframe.contentDocument || iframe.contentWindow.document;
                const translateLinks = innerDoc.querySelectorAll('.goog-te-menu2-item span.text');

                // Find and click the link with our language
                translateLinks.forEach(function(link) {
                    if (link.innerText.includes(googleTranslateLanguages[languageCode])) {
                        link.click();
                        return;
                    }
                });
            } catch (e) {
                console.log('Error accessing iframe content:', e);
            }
        }

        // Second approach: Try direct selector click
        let selectField = document.querySelector('.goog-te-combo');
        if (!selectField) {
            selectField = document.querySelector('.VIpgJd-ZVi9od-xl07Ob-lTBxed');
        }

        if (selectField) {
            selectField.value = languageCode;
            selectField.dispatchEvent(new Event('change'));
        }

        // Third approach: Use the Google Translate cookie method
        try {
            // Set Google Translate cookies
            document.cookie = `googtrans=/en/${languageCode}; path=/; domain=${window.location.hostname}`;
            document.cookie = `googtrans=/en/${languageCode}; path=/;`;

            // Force page reload to apply cookie-based translation
            if (localStorage.getItem('googleTranslateForceReload') !== 'done') {
                localStorage.setItem('googleTranslateForceReload', 'done');
                localStorage.setItem('preferredLanguage', languageCode);
                location.reload();
            } else {
                localStorage.removeItem('googleTranslateForceReload');
            }
        } catch (e) {
            console.log('Cookie method failed:', e);
        }

        localStorage.setItem('preferredLanguage', languageCode);
    }

    // Initialize with saved preference after page load
    window.addEventListener('load', function() {
        setTimeout(function() {
            const savedLanguage = localStorage.getItem('preferredLanguage');
            if (savedLanguage && savedLanguage !== 'en') {
                changeLanguage(savedLanguage);
            }
        }, 1500);
    });

    // Add a utility function to trigger Google Translate manually (can be helpful for debugging)
    function doGTranslate(lang_pair) {
        if (GoogleTranslate && GoogleTranslate.getInstance) {
            var gt = GoogleTranslate.getInstance();
            if (gt) gt.translate(null, lang_pair);
        }
    }

</script>
</body>
</html>
