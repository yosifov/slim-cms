@extends("layouts.master", [
    'title' => $title ?? ''
])

@section("content")

  <div class="main-banner" id="top">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="row">
            <div class="col-lg-6 align-self-center">
              <div class="left-content header-text">
                <h6>{{ trans('home.subtitle', $locale) }}</h6>
                <h2>Slim <em>CMS</em></h2>
                <p>{{ trans('home.keyvisual_info', $locale) }}</p>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="right-image">
                <img src="images/banner-right-image.png" alt="Build your website with Slim CMS">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="contact" class="contact-us section">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 align-self-center">
          <div class="section-heading">
            <h2>{{ trans('contact.heading', $locale) }}</h2>
            <p>{{ trans('contact.sub_heading', $locale) }}</p>
            <div class="phone-info">
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          @include('contact.form')
        </div>
      </div>
    </div>
  </div>

@endsection