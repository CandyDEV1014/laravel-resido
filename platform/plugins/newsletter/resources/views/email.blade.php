@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')
<div class="widget meta-boxes">
  <div class="widget-title">
    <h4>&nbsp; {{ trans('plugins/newsletter::newsletter.email_for_sub') }}</h4>
  </div>
  <div class="widget-body box-translation">

    <form method="post" action="{{ route('newsletter.email.send') }}" id="frm">
      @csrf
      <div class="form-group mb-3">
        <label for="name" class="control-label required" aria-required="true">Subject</label>
        <input class="form-control" placeholder="Subject" name="subject" type="text" id="subject">
        <div id="subject_error" class="error" style="display: none;">Enter Subject</div>
      </div>

      <div class="form-group mb-3 message-editor">
        <label class="control-label required">Message</label>
        <textarea class="form-control editor-ckeditor" rows="4" placeholder="Message" name="message" cols="50" id="message"></textarea>
        <div id="message_error" class="error" style="display: none;">Enter Message</div>
      </div>

      <div class="form-group mb-3">
        <a href="javascript:;" class="btn btn-info button-save-theme-translations submit">{{ trans('plugins/newsletter::newsletter.email_send') }}</a>
      </div>
    </form>

  </div>
  <div class="clearfix"></div>
</div>
@stop

<style type="text/css">
  .error{
    color: tomato;
  }
  .note-editor.note-airframe.fullscreen, .note-editor.note-frame.fullscreen {
    z-index: 9996 !important;
  }
  
</style>

<script src="{{ asset('themes/resido/plugins/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/core/core/base/js/editor.js') }}"></script>
<script src="{{ asset('vendor/core/core/base/libraries/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $(document).on('click','.submit',function()
    {
      subject       = $("#subject").val();
      message       = $("#message").val();

      var error = false;

      if(subject.length == 0){
        $("#subject_error").show();
        error = true;
      } else{
        $("#subject_error").hide();
      }

      if(message.length == 0){
        $("#message_error").show();
        error = true;
      } else{
        $("#message_error").hide();
      }

      setTimeout(function(){
        if(error == true){
          return false;
        } else {
          $("#frm").submit();
          return true;
        }
      }, 1000);

    });
  });
  
</script>