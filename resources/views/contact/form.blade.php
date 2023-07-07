<form action="/contact/submit" method="POST">
    <div id="name-group" class="form-group">
      <label for="name">{{ trans('forms.labels.name', $locale) }}</label>
      <input
        type="text"
        class="form-control"
        id="name"
        name="name"
        placeholder="{{ trans('forms.placeholders.name', $locale) }}"
      />
      <div class="error-block" style="display: none;"></div>
    </div>

    <div id="email-group" class="form-group">
      <label for="email">{{ trans('forms.labels.email', $locale) }}</label>
      <input
        type="text"
        class="form-control"
        id="email"
        name="email"
        placeholder="{{ trans('forms.placeholders.email', $locale) }}"
      />
      <div class="error-block" style="display: none;"></div>
    </div>

    <div id="subject-group" class="form-group">
      <label for="subject">{{ trans('forms.labels.subject', $locale) }}</label>
      <input
        type="text"
        class="form-control"
        id="subject"
        name="subject"
        placeholder="{{ trans('forms.placeholders.subject', $locale) }}"
      />
      <div class="error-block" style="display: none;"></div>
    </div>

    <div id="message-group" class="form-group">
      <label for="message">{{ trans('forms.labels.message', $locale) }}</label>
      <textarea class="form-control" id="message" name="message" placeholder="{{ trans('forms.placeholders.message', $locale) }}"></textarea>
      <div class="error-block" style="display: none;"></div>
    </div>

    <button type="submit" class="btn btn-success">
      {{ trans('forms.buttons.submit', $locale) }}
    </button>

    <div class="alert alert-success" style="display: none;"></div>
</form>