<div class="js-cookie-consent cookie-consent">
    <div class="d-flex justify-content-center container mt-5">
        <div class=" d-flex flex-row justify-content-between align-items-center card cookie p-3">
            <div class="d-flex flex-row align-items-center">
                <span class="cookie-consent__message">
                    {!! trans('cookieConsent::texts.message') !!}
                </span>
            </div>
            <div>
                <button class="btn btn-dark js-cookie-consent-agree cookie-consent__agree">
                    {{ trans('cookieConsent::texts.agree') }}
                </button>
            </div>
        </div>
    </div>
</div>
