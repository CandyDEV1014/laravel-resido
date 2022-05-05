@extends('plugins/real-estate::account.layouts.skeleton')
@section('content')
  <div class="settings crop-avatar">
    <div class="container">
      <div class="row">
        @include('plugins/real-estate::account.settings.sidebar')
        <div class="col-12 col-md-9">
            <div class="main-dashboard-form">
                <!-- Setting Title -->
                <div class="row">
                    <div class="col-12">
                        <h4 class="with-actions">{{ trans('plugins/real-estate::dashboard.account_field_title') }}</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 order-lg-12">
                        <form id="avatar-upload-form" enctype="multipart/form-data" action="javascript:void(0)" onsubmit="return false">
                            <div class="avatar-upload-container">
                                <div class="form-group">
                                    <label for="account-avatar">{{ trans('plugins/real-estate::dashboard.profile-picture') }}</label>
                                    <div id="account-avatar">
                                        <div class="profile-image">
                                            <div class="avatar-view mt-card-avatar">
                                                <img class="br2" src="{{ $user->avatar_url }}" style="width: 200px;">
                                                <div class="mt-overlay br2">
                                                    <span><i class="fa fa-edit"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Print messages -->
                                <div id="print-msg" class="alert dn"></div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-8 order-lg-0">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                </button>
                            </div>
                        @endif
                        <form action="{{ route('public.account.post.settings') }}" id="setting-form" method="POST">
                        @csrf
                        <!-- Name -->
                            <div class="form-group">
                                <label for="first_name">{{ trans('plugins/real-estate::dashboard.first_name') }}</label>
                                <input type="text" class="form-control" name="first_name" id="first_name" required value="{{ old('first_name') ?? $user->first_name }}">
                            </div>
                            <!-- Name -->
                            <div class="form-group">
                                <label for="last_name">{{ trans('plugins/real-estate::dashboard.last_name') }}</label>
                                <input type="text" class="form-control" name="last_name" id="last_name" required value="{{ old('last_name') ?? $user->last_name }}">
                            </div>
                            <div class="form-group">
                                <label for="username">{{ trans('plugins/real-estate::dashboard.username') }}</label>
                                <input type="text" class="form-control" name="username" id="username" required value="{{ old('username') ?? $user->username }}">
                            </div>
                            <!-- Phone -->
                            <div class="form-group">
                                <label for="phone">{{ trans('plugins/real-estate::dashboard.phone') }}</label>
                                <input type="text" class="form-control" name="phone" id="phone" required value="{{ old('phone') ?? $user->phone }}">
                            </div>
                            <!--Short description-->
                            <div class="form-group">
                                <label for="description">{{ trans('plugins/real-estate::dashboard.description') }}</label>
                                <textarea class="form-control" name="description" id="description" rows="3" maxlength="300" placeholder="{{ trans('plugins/real-estate::dashboard.description_placeholder') }}">{{ old('description') ?? $user->description }}</textarea>
                            </div>
                            <!-- Email -->
                            <div class="form-group">
                                <label for="email">{{ trans('plugins/real-estate::dashboard.email') }}</label>
                                <input type="email" class="form-control" name="email" id="email" disabled="disabled" placeholder="{{ trans('plugins/real-estate::dashboard.email_placeholder') }}" required value="{{ old('email') ?? $user->email }}">
                                @if ($user->confirmed_at)
                                    <small class="f7 green">{{ trans('plugins/real-estate::dashboard.verified') }}<i class="ml1 far fa-check-circle"></i></small>
                                @else
                                    <small class="f7">{{ trans('plugins/real-estate::dashboard.verify_require_desc') }}<a href="{{ route('public.account.resend_confirmation', ['email' => $user->email]) }}" class="ml1">{{ trans('plugins/real-estate::dashboard.resend') }}</a></small>
                                @endif
                            </div>
                            <!-- Birthday -->
                            <div class="form-group">
                                <label for="dob">{{ trans('plugins/real-estate::dashboard.birthday') }}</label>
                                <div class="birthday-box">
                                    <select id="year" name="year" class="form-control{{ $errors->has('year') ? ' is-invalid' : '' }}" style="width: 74px!important; display: inline-block!important;" onchange="changeYear(this)"></select>
                                    <select id="month" name="month" class="form-control{{ $errors->has('month') ? ' is-invalid' : '' }}" style="width: 90px!important; display: inline-block!important;" onchange="changeMonth(this)"></select>
                                    <select id="day" name="day" class="form-control{{ $errors->has('day') ? ' is-invalid' : '' }}" style="width: 74px!important; display: inline-block!important;"></select>
                                    <span class="invalid-feedback">
                    <strong>{{ $errors->has('dob') ? $errors->first('dob') : '' }}</strong>
                  </span>
                                </div>
                            </div>
                            <!-- Gender -->
                            <div class="form-group">
                                <label for="gender">{{ trans('plugins/real-estate::dashboard.gender') }}</label>
                                <select class="form-control" name="gender" id="gender">
                                    <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>{{ trans('plugins/real-estate::dashboard.gender_male') }}</option>
                                    <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>{{ trans('plugins/real-estate::dashboard.gender_female') }}</option>
                                    <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>{{ trans('plugins/real-estate::dashboard.gender_other') }}</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary fw6">{{ trans('plugins/real-estate::dashboard.save') }}</button>
                        </form>
                    </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @include('plugins/real-estate::account.modals.avatar')
  </div>
@endsection
@push('scripts')
  <!-- Laravel Javascript Validation -->
  <script type="text/javascript" src="{{ asset('vendor/core/core/js-validation/js/js-validation.js')}}"></script>
  {!! JsValidator::formRequest(\Botble\RealEstate\Http\Requests\SettingRequest::class); !!}
  <script type="text/javascript">
    "use strict";
    let numberDaysInMonth = [31,28,31,30,31,30,31,31,30,31,30,31];

    $(document).ready(function() {
      // Init form select
      initSelectBox();
    });

    function initSelectBox() {
      let oldBirthday = '{{ $user->dob }}';
      let selectedDay = '';
      let selectedMonth = '';
      let selectedYear = '';

      if (oldBirthday !== '') {
        selectedDay = parseInt(oldBirthday.substr(8, 2));
        selectedMonth = parseInt(oldBirthday.substr(5, 2));
        selectedYear = parseInt(oldBirthday.substr(0, 4));
      }

      let dayOption = `<option value="">{{ trans('plugins/real-estate::dashboard.day_lc') }}</option>`;
      for (let i = 1; i <= numberDaysInMonth[0]; i++) { //add option days
        if (i === selectedDay) {
          dayOption += `<option value="${i}" selected>${i}</option>`;
        } else {
          dayOption += `<option value="${i}">${i}</option>`;
        }
      }
      $('#day').append(dayOption);

      let monthOption = `<option value="">{{ trans('plugins/real-estate::dashboard.month_lc') }}</option>`;
      for (let j = 1; j <= 12; j++) {
        if (j === selectedMonth) {
          monthOption += `<option value="${j}" selected>${j}</option>`;
        } else {
          monthOption += `<option value="${j}">${j}</option>`;
        }
      }
      $('#month').append(monthOption);

      let d = new Date();
      let yearOption = `<option value="">{{ trans('plugins/real-estate::dashboard.year_lc') }}</option>`;
      for (let k = d.getFullYear(); k >= 1918; k--) {// years start k
        if (k === selectedYear) {
          yearOption += `<option value="${k}" selected>${k}</option>`;
        } else {
          yearOption += `<option value="${k}">${k}</option>`;
        }
      }
      $('#year').append(yearOption);
    }

    function isLeapYear(year) {
      year = parseInt(year);
      if (year % 4 !== 0) {
        return false;
      }
      if (year % 400 === 0) {
        return true;
      }
      if (year % 100 === 0) {
        return false;
      }
      return true;
    }

    function changeYear(select) {
      if (isLeapYear($(select).val())) {
        // Update day in month of leap year.
        numberDaysInMonth[1] = 29;
      } else {
        numberDaysInMonth[1] = 28;
      }

      // Update day of leap year.
      let monthSelectedValue = parseInt($("#month").val());
      if (monthSelectedValue === 2) {
        let day = $('#day');
        let daySelectedValue = parseInt($(day).val());
        if (daySelectedValue > numberDaysInMonth[1]) {
          daySelectedValue = null;
        }

        $(day).empty();

        let option = `<option value="">{{ trans('plugins/real-estate::dashboard.day_lc') }}</option>`;
        for (let i = 1; i <= numberDaysInMonth[1]; i++) { //add option days
          if (i === daySelectedValue) {
            option += `<option value="${i}" selected>${i}</option>`;
          } else {
            option += `<option value="${i}">${i}</option>`;
          }
        }

        $(day).append(option);
      }
    }

    function changeMonth(select) {
      let day = $('#day');
      let daySelectedValue = parseInt($(day).val());
      let month = 0;

      if ($(select).val() !== '') {
        month = parseInt($(select).val()) - 1;
      }

      if (daySelectedValue > numberDaysInMonth[month]) {
        daySelectedValue = null;
      }

      $(day).empty();

      let option = `<option value="">{{ trans('plugins/real-estate::dashboard.day_lc') }}</option>`;

      for (let i = 1; i <= numberDaysInMonth[month]; i++) { //add option days
        if (i === daySelectedValue) {
          option += `<option value="${i}" selected>${i}</option>`;
        } else {
          option += `<option value="${i}">${i}</option>`;
        }
      }

      $(day).append(option);
    }
  </script>
@endpush
