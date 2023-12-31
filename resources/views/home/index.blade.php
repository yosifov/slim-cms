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
                <h6>{{ trans('home.subtitle') }}</h6>
                <h2>Slim <em>CMS</em></h2>
                <p>{{ trans('home.keyvisual_info') }}</p>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="right-image">
                <img src="/images/banner-right-image.png" alt="Build your website with Slim CMS" width="570" height="399">
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
            <h2>{{ trans('contact.heading') }}</h2>
            <p>{{ trans('contact.sub_heading') }}</p>
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