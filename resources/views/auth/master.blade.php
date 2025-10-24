<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="sm-hover"
    data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>{{ gs()->title }} - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ gs()->getFavicon() }}">

    <!-- Layout config Js -->
    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/toastr/toastr.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

</head>

<body>
    <!-- Content -->

    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card overflow-hidden">
                            <div class="row g-0">
                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4 auth-one-bg h-100">
                                        <div class="bg-overlay"></div>
                                        <div class="position-relative h-100 d-flex flex-column">
                                            <div class="mb-4">
                                                <a href="{{url('/')}}" class="d-block">
                                                    <img src="{{ gs()->getLogo() }}" alt="" height="{{ gs()->logo_height?? '60px' }}">
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->

                                <div class="col-lg-6">
                                    @yield('content')
                                </div>
                                <!-- end col -->
                            </div>
                            <!-- end row -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->

                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->

        <!-- end Footer -->
    </div>
    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
     <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('assets/js/plugins.js') }}"></script>
    @yield('js')

    <!-- password-addon init -->
    <script src="assets/js/pages/password-addon.init.js"></script>
    <script>
        function loadingStart(title = null) {
            // Swal.showLoading()
            Swal.fire({
                title: "Loading...",
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                    $(".swal2-confirm").removeClass("btn");
                    $(".swal2-deny").removeClass("btn");
                    $(".swal2-cancel").removeClass("btn");
                },
            });
            // return new swal("Loading", "!", "success");
        }

        function loadingStop() {
            Swal.close();
        }
        $(document).on('submit', '#login-form', function(e) {
            e.preventDefault();
            loadingStart();
            $('.invalid-feedback').remove()
            $('.is-invalid').removeClass('is-invalid')
            var this_form = $(this);
            var formdata = new FormData(this);

            $.ajax({
                method: this_form.prop('method'),
                url: this_form.prop('action'),
                data: formdata,
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    loadingStop();
                    if (data.type == 'otp_send') {
                        $('#email').attr('readonly', true)
                        $('#password').attr('readonly', true)
                        $('#otp').show()
                        $('#otp').attr('required', true)

                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500,
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                            buttonsStyling: false,
                        });
                    }
                    if (data.success == true) {
                        window.location.href = data.data
                    }
                },
                error: function(data) {
                    loadingStop();
                    var responseJSON = data.responseJSON
                    if (responseJSON) {

                        toastr.error(responseJSON.message);
                        var errors = responseJSON.errors;
                        if (errors) {
                            for (var error in errors) {
                                var input_field_e = $('#' + error)
                                input_field_e.addClass('is-invalid')
                                input_field_e.after('<span  class="error invalid-feedback text-left">' +
                                    errors[error][0] + '</span>')
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>
