<title>{{ucwords(str_replace('/',' ',request()->path()))}}</title>

<meta name="description" content="" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="{{ asset(getConfigTableData()?->favicon??'') }}" />

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
    rel="stylesheet" />

<!-- Icons -->
<link rel="stylesheet" href="{{ asset('/assets/vendor/fonts/fontawesome.css') }}" />
<link rel="stylesheet" href="{{ asset('/assets/vendor/fonts/tabler-icons.css') }}" />
<link rel="stylesheet" href="{{ asset('/assets/vendor/fonts/flag-icons.css') }}" />

<!-- Core CSS -->
<link rel="stylesheet" href="{{ asset('/assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
<link rel="stylesheet" href="{{ asset('/assets/vendor/css/rtl/theme-default.css') }}"
    class="template-customizer-theme-css" />
<link rel="stylesheet" href="{{ asset('/assets/css/demo.css') }}" />
<link rel="canonical" href="{{getCononicalUrl()}}">

<!-- Vendors CSS -->
<link rel="stylesheet" href="{{ asset('/assets/vendor/libs/node-waves/node-waves.css') }}" />
<link rel="stylesheet" href="{{ asset('/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
<link rel="stylesheet" href="{{ asset('/assets/vendor/libs/typeahead-js/typeahead.css') }}" />
<link rel="stylesheet" href="{{ asset('/assets/vendor/libs/apex-charts/apex-charts.css') }}" />
<link rel="stylesheet" href="{{ asset('/assets/vendor/libs/swiper/swiper.css') }}" />
<link rel="stylesheet" href="{{ asset('/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
<link rel="stylesheet"
    href="{{ asset('/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
<link rel="stylesheet" href="{{ asset('/assets/vendor/css/pages/cards-advance.css') }}" />
<script src="{{ asset('/assets/vendor/js/helpers.js') }}"></script>
<script src="{{ asset('/assets/vendor/js/template-customizer.js') }}"></script>
<script src="{{ asset('/assets/js/config.js') }}"></script>
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@yield('header')
<style>
    .cke_notifications_area{
        display: none;
    }
    .select2-container {
        box-sizing: border-box;
        display: inline-block;
        margin: 0;
        position: relative;
        vertical-align: middle;
        display: block;
        width: 100%;
        padding: 5px;
        font-size: 0.9375rem;
        font-weight: 400;
        line-height: 1.5;
        color: #6f6b7d;
        appearance: none;
        background-color: #fff;
        background-clip: padding-box;
        border: var(--bs-border-width) solid #dbdade;
        border-radius: var(--bs-border-radius);
        transition: border-color 0.15sease -in-out, box-shadow 0.15sease -in-out;
        color: gray !important;
    }

    .select2-container .select2-selection--single {
        box-sizing: border-box;
        cursor: pointer;
        display: block;
        height: 28px;
        user-select: none;
        -webkit-user-select: none;
        border: none !important;
        color: gray !important;
    }

</style>
@yield('css')
