<!-- ***** Header Area Start ***** -->
<header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">

                    <a href="/@if ($locale !== 'bg'){{ $locale }}@endif" class="logo">
                    <h4>Slim<span>CMS</span></h4>
                    </a>

                    <ul class="nav">
                        <li class="scroll-to-section"><a href="#contact">{{ trans('header.contact_us', $locale) }}</a></li>
                        <li class="scroll-to-section"><div class="main-red-button"><a href="https://github.com/yosifov/slim-cms" target="_blank">{{ trans('header.download', $locale) }}</a></div></li>
                    </ul>

                    <a class='menu-trigger'>
                        <span>{{ trans('header.menu', $locale) }}</span>
                    </a>
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- ***** Header Area End ***** -->