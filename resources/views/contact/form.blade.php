<form id="contact" action="/contact/submit" method="POST">
  <div class="alert alert-success" style="display: none;"></div>
  <div id="general-error" class="alert alert-danger" style="display: none;"></div>

  <div class="row">
    <div id="name-group" class="form-group col-lg-6">
      <input
        type="text"
        class="form-control"
        id="name"
        name="name"
        placeholder="{{ trans('forms.placeholders.name') }}"
      />
      <div class="error-block invalid-feedback" style="display: none;"></div>
    </div>

    <div id="email-group" class="form-group col-lg-6">
      <input
        type="text"
        class="form-control"
        id="email"
        name="email"
        placeholder="{{ trans('forms.placeholders.email') }}"
      />
      <div class="error-block invalid-feedback" style="display: none;"></div>
    </div>

    <div id="subject-group" class="form-group col-lg-12">
      <input
        type="text"
        class="form-control"
        id="subject"
        name="subject"
        placeholder="{{ trans('forms.placeholders.subject') }}"
      />
      <div class="error-block invalid-feedback" style="display: none;"></div>
    </div>

    <div id="message-group" class="form-group">
      <textarea class="form-control" id="message" name="message" placeholder="{{ trans('forms.placeholders.message') }}"></textarea>
      <div class="error-block invalid-feedback" style="display: none;"></div>
    </div>

    <div class="col-lg-12">
      <button type="submit" class="btn btn-success">
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
        <span class="sr-only">{{ trans('forms.buttons.submit') }}</span>
      </button>
    </div>

  </div>

  <div class="contact-dec">
    <img src="images/contact-decoration.png" alt="">
  </div>
    
</form>

@push('scripts')
<script>
    $(document).ready(function () {

        $("form").submit(function (event) {
            $.ajax({
                type: "POST",
                url: "/contact/submit",
                data: $('form').serialize(),
                dataType: "json",
                encode: true,
                beforeSend: function() {
                  $("form").find("button[type=submit]").prop('disabled', true).find(".spinner-border").show();
                }
            }).done(function (data) {
                $("form").find('.alert-success').hide();
                $("form").find('#general-error').hide();
                $("form").find('.form-group').removeClass('has-error').find('.error-block').hide();
                $("form").find("button[type=submit]").prop('disabled', false).find(".spinner-border").hide();

                for (const field in data.errors) {
                    if (Object.hasOwnProperty.call(data.errors, field)) {
                        const error = data.errors[field];
                        
                        $(`#${field}-group`).addClass("has-error");
                        $(`#${field}-group`).find('.error-block').show().html(error);
                        $(`#${field}-error`).show().html(error);
                    }
                }

                if (data.success) {
                    $("form").trigger("reset").find('.alert-success').show().html(data.message);
                }
            });

            event.preventDefault();
        });
    });
</script>
@endpush