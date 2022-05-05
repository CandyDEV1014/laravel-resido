{!! Form::open(['route' => 'public.send.consult', 'method' => 'POST', 'class' => 'contact-form', 'id' => 'contactForm']) !!}
<div class="row">
    <input type="hidden" name="data_id" value="{{ $data->id }}">
    <div class="form-group">
        <input class="form-control" name="name" id="name" type="text" placeholder="{{ __('Name') }} *" required @if (!auth('account')->check()) disabled @endif>
    </div>
    <div class="form-group">
        <input type="text" name="phone" class="form-control" placeholder="{{ __('Phone') }} *"
            data-validation-engine="validate[required]"
            data-errormessage-value-missing="{{ __('Please enter phone number') }}!" @if (!auth('account')->check()) disabled @endif>
    </div>
    <div class="form-group">
        <input class="form-control" name="email" id="email" type="email" placeholder="{{ __('Email') }}" @if (!auth('account')->check()) disabled @endif>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" placeholder="{{ __('Subject') }} *" value="{{ $data->name }}"
            readonly>
    </div>
    <div class="form-group">
        <textarea name="content" class="form-control" rows="5" placeholder="{{ __('Message') }}" @if (!auth('account')->check()) disabled @endif></textarea>
    </div>
    @if (setting('enable_captcha') && is_plugin_active('captcha'))
        <div class="form-group">
            {!! Captcha::display() !!}
        </div>
    @endif
    @if (!auth('account')->check()) 
        <p class="property_block_title text-danger text-center">Only logged users can send message.</p>
    @endif
    <div class="form-group">
        <button class="btn btn-black btn-md rounded full-width" type="submit" @if (!auth('account')->check()) disabled @endif>{{ __('Send Message') }}</button>
    </div>
    <div class="clearfix"></div>
    <br>
    <div class="alert alert-success text-success text-left" style="display: none;">
        <span></span>
    </div>
    <div class="alert alert-danger text-danger text-left" style="display: none;">
        <span></span>
    </div>
</div>


{!! Form::close() !!}
