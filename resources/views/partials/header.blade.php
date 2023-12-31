<!-- ***** Header Area Start ***** -->
<header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">

                    <a href="/@if (get_locale() !== config('default_locale')){{ get_locale() }}@endif" class="logo">
                        <img src="/images/slimcms-logo.png" alt="Slim CMS logo" width="264" height="264">
                        <h4>Slim<span>CMS</span></h4>
                    </a>

                    <ul class="nav">
                        <li class="scroll-to-section"><a href="#contact">{{ trans('header.contact_us') }}</a></li>
                        <li class="scroll-to-section"><div class="main-red-button"><a href="https://github.com/yosifov/slim-cms" target="_blank">{{ trans('header.download') }}</a></div></li>
                    </ul>

                    <a class='menu-trigger'>
                        <span>{{ trans('header.menu') }}</span>
                    </a>
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- ***** Header Area End ***** -->