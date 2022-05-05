<div class="flexbox-annotated-section">
    <div class="flexbox-annotated-section-annotation">
        <div class="annotated-section-title pd-all-20">
            <h2>{{ trans('plugins/newsletter::newsletter.settings.title') }}</h2>
        </div>
        <div class="annotated-section-description pd-all-20 p-none-t">
            <p class="color-note">{{ trans('plugins/newsletter::newsletter.settings.description') }}</p>
        </div>
    </div>

    <div class="flexbox-annotated-section-content">
        <div class="wrapper-content pd-all-20">
            <div class="form-group mb-3">
                <label class="text-title-field"
                       for="newsletter_mailchimp_api_key">{{ trans('plugins/newsletter::newsletter.settings.mailchimp_api_key') }}</label>
                <input data-counter="120" type="text" class="next-input" name="newsletter_mailchimp_api_key"
                       id="newsletter_mailchimp_api_key"
                       value="{{ setting('newsletter_mailchimp_api_key') }}"
                       placeholder="{{ trans('plugins/newsletter::newsletter.settings.mailchimp_api_key') }}">
            </div>
            <div class="form-group mb-3">
                @if (empty($mailchimpContactList))
                    <label class="text-title-field"
                           for="newsletter_mailchimp_list_id">{{ trans('plugins/newsletter::newsletter.settings.mailchimp_list_id') }}</label>
                    <input data-counter="120" type="text" class="next-input" name="newsletter_mailchimp_list_id"
                           id="newsletter_mailchimp_list_id"
                           value="{{ setting('newsletter_mailchimp_list_id') }}"
                           placeholder="{{ trans('plugins/newsletter::newsletter.settings.mailchimp_list_id') }}">
                @else
                    <label class="text-title-field"
                           for="newsletter_mailchimp_list_id">{{ trans('plugins/newsletter::newsletter.settings.mailchimp_list') }}</label>
                    {!! Form::customSelect('newsletter_mailchimp_list_id', $mailchimpContactList, setting('newsletter_mailchimp_list_id')) !!}
                @endif
            </div>

            <div class="form-group mb-3">
                <label class="text-title-field"
                       for="newsletter_sendgrid_api_key">{{ trans('plugins/newsletter::newsletter.settings.sendgrid_api_key') }}</label>
                <input data-counter="120" type="text" class="next-input" name="newsletter_sendgrid_api_key"
                       id="newsletter_sendgrid_api_key"
                       value="{{ setting('newsletter_sendgrid_api_key') }}"
                       placeholder="{{ trans('plugins/newsletter::newsletter.settings.sendgrid_api_key') }}">
            </div>
            <div class="form-group mb-3">
                @if (empty($sendGridContactList))
                    <label class="text-title-field"
                           for="newsletter_sendgrid_list_id">{{ trans('plugins/newsletter::newsletter.settings.sendgrid_list_id') }}</label>
                    <input data-counter="120" type="text" class="next-input" name="newsletter_sendgrid_list_id"
                           id="newsletter_sendgrid_list_id"
                           value="{{ setting('newsletter_sendgrid_list_id') }}"
                           placeholder="{{ trans('plugins/newsletter::newsletter.settings.sendgrid_list_id') }}">
                @else
                    <label class="text-title-field"
                           for="newsletter_sendgrid_list_id">{{ trans('plugins/newsletter::newsletter.settings.sendgrid_list') }}</label>
                    {!! Form::customSelect('newsletter_sendgrid_list_id', $sendGridContactList, setting('newsletter_sendgrid_list_id')) !!}
                @endif
            </div>
        </div>
    </div>
</div>
