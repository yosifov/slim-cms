@extends('layouts.master')

@section('content')
    <div class="col-sm-6 col-sm-offset-3">
        <h1>{{ trans('contact.heading', $locale) }}</h1>
    
        @include('contact.form')
    </div>
@endsection

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
            }).done(function (data) {
                $("form").find('.alert-success').hide();
                $("form").find('.form-group').removeClass('has-error')
                         .find('.error-block').hide();

                for (const field in data.errors) {
                    if (Object.hasOwnProperty.call(data.errors, field)) {
                        const error = data.errors[field];
                        
                        $(`#${field}-group`).addClass("has-error");
                        $(`#${field}-group`).find('.error-block').show().html(error);
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