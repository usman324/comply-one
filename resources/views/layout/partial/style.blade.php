<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ gs()->title }}</title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
{{-- <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" /> --}}
<!-- App favicon -->
<link rel="shortcut icon" href="{{ gs()->getFavicon() }}">

<!-- jsvectormap css -->
<link href="{{ asset('assets/libs/jsvectormap/jsvectormap.min') }}.css" rel="stylesheet" type="text/css" />

<!--Swiper slider css-->
<link href="{{ asset('assets/libs/swiper/swiper-bundle') }}.min.css" rel="stylesheet" type="text/css" />

<!-- Layout config Js -->
<script src="assets/js/layout.js"></script>
<!-- Bootstrap Css -->
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
<!-- custom Css-->
<link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}" />
<link href="{{ asset('assets/libs/select2/select2.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/toastr/toastr.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">


<style>
    .ui-autocomplete {
        position: absolute;
        cursor: default;
        border-radius: 0.375rem;
        z-index: 10001 !important
    }

    .ui-front {
        z-index: 1500 !important;
    }

    .ui-autocomplete-loading {
        background: white url("{{ asset('assets/img/ui-anim_basic_16x16.gif') }}") right center no-repeat;
    }

    hr {
        margin: 0.3rem 0 !important;
        color: inherit;
        border: 0;
        border-top: var(--vz-border-width) solid;
        opacity: .25;
    }

    .h1,
    .h2,
    .h3,
    .h4,
    .h5,
    .h6,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        margin-top: 0;
        margin-bottom: .2rem;
        font-family: Poppins, sans-serif;
        font-weight: 500;
        line-height: 1.2;
        color: var(--vz-heading-color);
    }

    .ui-widget-content {
        height: 200px;
        text-transform: inherit;
        margin-bottom: 0.25rem;
        color: #5d596c;
        overflow-y: auto;
        text-transform: uppercase;
        font-size: 0.8125rem;
        letter-spacing: 1px;
        padding-top: 0.88rem;
        padding-bottom: 0.88rem;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {

        text-overflow: unset !important;
    }

    .notifyjs-bootstrap-error {
        background-color: #f44336 !important;
        /* Red */
        color: white !important;
        width: 400px;
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 16px;
        text-align: center;
    }

    /* .navbar-menu .navbar-nav .nav-link {
        color: #fff !important;
    } */
    .navbar-menu .navbar-nav .nav-link.active {
        color: #fff !important;
        border-radius: 5px;
        background: linear-gradient(
        135deg,
         #405189, /* Base dark blue */
    #5160A0, /* Slightly lighter blue */
    #6380B3, /* Medium blue */
    #7AA0C6   /* light teal */
    ) !important;

    /* Soft shadow for depth */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
    }
    .btn-soft-secondary{
        color: #405189 !important;
    }
    .btn-soft-secondary {
    --vz-btn-color: #405189
    --vz-btn-bg: var(--vz-secondary-bg-subtle);
    --vz-btn-border-color: transparent;
    --vz-btn-hover-bg: #405189;
    --vz-btn-hover-border-color: transparent;
    --vz-btn-focus-shadow-rgb: var(--vz-secondary-rgb);
    --vz-btn-active-bg: #405189;
    --vz-btn-active-border-color: transparent;
}
.btn-secondary{
    background-color: #405189 !important;
    border-color: #405189 !important;
}
</style>
@yield('css')
