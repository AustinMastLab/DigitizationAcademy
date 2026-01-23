<div class="form-group mt-4">
    @if ($errors->has('g-recaptcha-response'))
        <div class="help-block color-action" role="alert" aria-live="assertive" aria-atomic="true">
            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
        </div>
    @endif
</div>

<noscript>
    <div class="alert alert-warning" role="alert">
        This form requires JavaScript to submit.
    </div>
</noscript>

@push('scripts')
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>

    <script type="text/javascript">
        $(function () {
            // Avoid binding twice if the partial is included multiple times
            $('.recaptcha').off('submit.recaptcha').on('submit.recaptcha', function (event) {
                event.preventDefault();

                const form = this;            // native form element
                const $form = $(form);
                const $submit = $form.find('button[type="submit"]');
                const siteKey = @json(config('services.recaptcha.site_key'));

                // prevent accidental double submits while we fetch a token
                $submit.prop('disabled', true);

                // If the script is blocked/unavailable, submit normally so server can respond
                if (typeof grecaptcha === 'undefined') {
                    $submit.prop('disabled', false);
                    $form.off('submit.recaptcha');
                    form.submit();
                    return;
                }

                grecaptcha.ready(function () {
                    grecaptcha.execute(siteKey, { action: 'submit' }).then(function (token) {
                        let $tokenInput = $form.find('input[name="g-recaptcha-response"]');
                        if ($tokenInput.length === 0) {
                            $tokenInput = $('<input>', { type: 'hidden', name: 'g-recaptcha-response' }).appendTo($form);
                        }
                        $tokenInput.val(token);

                        $submit.prop('disabled', false);

                        // Remove only our handler, then submit via native submit (won't re-trigger jQuery handlers)
                        $form.off('submit.recaptcha');
                        form.submit();
                    }).catch(function () {
                        // Token fetch failed; submit normally so user gets a server-side error/toast
                        $submit.prop('disabled', false);
                        $form.off('submit.recaptcha');
                        form.submit();
                    });
                });
            });
        });
    </script>
@endpush