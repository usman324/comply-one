@extends('layout.master')

@section('style')
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" /> --}}
    <style>
        .card .card-body {
            padding: 25px !important;
        }
    </style>
@stop
@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col">
                <div class="card crm-widget">
                    <div class="card-body p-0">
                        <div class="row row-cols-md-3 mb-2 row-cols-1">
                            <div class="col col-lg border-end">
                                <div class="pt-3 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">Total Suppliers <i
                                            class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                    </h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-user-search-fill display-6 text-success"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-0"><span class="counter-value" data-target="197">197</span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end col -->
                            <div class="col col-lg border-end">
                                <div class="mt-3 mt-md-0 pt-3 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">Total Purchases <i
                                            class="ri-arrow-up-circle-line text-danger fs-18 float-end align-middle"></i>
                                    </h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-shopping-basket-2-line display-6 text-info"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-0">$<span class="counter-value"
                                                    data-target="489.4">489.4</span>k</h5>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end col -->
                            <div class="col col-lg border-end">
                                <div class="mt-3 mt-md-0 pt-3 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">
                                        Supplier Payments <i
                                            class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i>
                                    </h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-wallet-fill display-6 text-dark"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-0"><span class="counter-value" data-target="32.89">32.89</span>%
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end col -->
                            <div class="col col-lg border-end">
                                <div class="mt-3 mt-lg-0 pt-3 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">
                                        Total Due Payments <i
                                            class="ri-arrow-up-circle-line text-danger fs-18 float-end align-middle"></i>
                                    </h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-wallet-line display-6 text-danger"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-0">$<span class="counter-value"
                                                    data-target="1596.5">1,596.5</span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end col -->

                        </div><!-- end row -->
                        <div class="row row-cols-md-3 row-cols-1">
                            <div class="col col-lg border-end">
                                <div class="pt-3 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">Total Customers <i
                                            class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                    </h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-user-search-fill display-6 text-warning"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-0"><span class="counter-value" data-target="197">197</span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end col -->
                            <div class="col col-lg border-end">
                                <div class="mt-3 mt-md-0 pt-3 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">Total Sales <i
                                            class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                    </h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-shopping-bag-fill display-6 text-info-emphasis"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-0">$<span class="counter-value"
                                                    data-target="489.4">489.4</span>k</h5>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end col -->
                            <div class="col col-lg border-end">
                                <div class="mt-3 mt-md-0 pt-3 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">
                                        Customer Payments <i
                                            class="ri-arrow-down-circle-line text-success fs-18 float-end align-middle"></i>
                                    </h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-wallet-fill display-6 text-warning-emphasis"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-0"><span class="counter-value" data-target="32.89">32.89</span>%
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end col -->
                            <div class="col col-lg border-end">
                                <div class="mt-3 mt-lg-0 pt-3 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">
                                        Total Due Paymemts <i
                                            class="ri-arrow-up-circle-line text-danger fs-18 float-end align-middle"></i>
                                    </h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-wallet-line display-6 text-danger-emphasis"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-0">$<span class="counter-value"
                                                    data-target="1596.5">1,596.5</span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end col -->
                        </div>
                    </div><!-- end card body -->
                </div>

            </div> <!-- end col -->

        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="card card-height-100">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Recent Sales</h4>
                        <div class="flex-shrink-0">
                            <button type="button" class="btn btn-soft-info btn-sm">
                                <i class="ri-file-list-3-line align-middle"></i> Generate Report
                            </button>
                        </div>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                <thead class="text-muted table-light">
                                    <tr>
                                        <th scope="col">Order ID</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Product</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Vendor</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <a href="apps-ecommerce-order-details.html"
                                                class="fw-medium link-primary">#VZ2112</a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <img src="assets/images/users/avatar-1.jpg" alt=""
                                                        class="avatar-xs rounded-circle">
                                                </div>
                                                <div class="flex-grow-1">Alex Smith</div>
                                            </div>
                                        </td>
                                        <td>Clothes</td>
                                        <td>
                                            <span class="text-success">$109.00</span>
                                        </td>
                                        <td>Zoetic Fashion</td>
                                        <td>
                                            <span class="badge bg-success-subtle text-success">Paid</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 fw-medium mb-0">5.0<span class="text-muted fs-11 ms-1">(61
                                                    votes)</span></h5>
                                        </td>
                                    </tr><!-- end tr -->
                                    <tr>
                                        <td>
                                            <a href="apps-ecommerce-order-details.html"
                                                class="fw-medium link-primary">#VZ2111</a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <img src="assets/images/users/avatar-2.jpg" alt=""
                                                        class="avatar-xs rounded-circle">
                                                </div>
                                                <div class="flex-grow-1">Jansh Brown</div>
                                            </div>
                                        </td>
                                        <td>Kitchen Storage</td>
                                        <td>
                                            <span class="text-success">$149.00</span>
                                        </td>
                                        <td>Micro Design</td>
                                        <td>
                                            <span class="badge bg-warning-subtle text-warning">Pending</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 fw-medium mb-0">4.5<span class="text-muted fs-11 ms-1">(61
                                                    votes)</span></h5>
                                        </td>
                                    </tr><!-- end tr -->
                                    <tr>
                                        <td>
                                            <a href="apps-ecommerce-order-details.html"
                                                class="fw-medium link-primary">#VZ2109</a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <img src="assets/images/users/avatar-3.jpg" alt=""
                                                        class="avatar-xs rounded-circle">
                                                </div>
                                                <div class="flex-grow-1">Ayaan Bowen</div>
                                            </div>
                                        </td>
                                        <td>Bike Accessories</td>
                                        <td>
                                            <span class="text-success">$215.00</span>
                                        </td>
                                        <td>Nesta Technologies</td>
                                        <td>
                                            <span class="badge bg-success-subtle text-success">Paid</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 fw-medium mb-0">4.9<span class="text-muted fs-11 ms-1">(89
                                                    votes)</span></h5>
                                        </td>
                                    </tr><!-- end tr -->
                                    <tr>
                                        <td>
                                            <a href="apps-ecommerce-order-details.html"
                                                class="fw-medium link-primary">#VZ2108</a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <img src="assets/images/users/avatar-4.jpg" alt=""
                                                        class="avatar-xs rounded-circle">
                                                </div>
                                                <div class="flex-grow-1">Prezy Mark</div>
                                            </div>
                                        </td>
                                        <td>Furniture</td>
                                        <td>
                                            <span class="text-success">$199.00</span>
                                        </td>
                                        <td>Syntyce Solutions</td>
                                        <td>
                                            <span class="badge bg-danger-subtle text-danger">Unpaid</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 fw-medium mb-0">4.3<span class="text-muted fs-11 ms-1">(47
                                                    votes)</span></h5>
                                        </td>
                                    </tr><!-- end tr -->
                                    <tr>
                                        <td>
                                            <a href="apps-ecommerce-order-details.html"
                                                class="fw-medium link-primary">#VZ2107</a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <img src="assets/images/users/avatar-6.jpg" alt=""
                                                        class="avatar-xs rounded-circle">
                                                </div>
                                                <div class="flex-grow-1">Vihan Hudda</div>
                                            </div>
                                        </td>
                                        <td>Bags and Wallets</td>
                                        <td>
                                            <span class="text-success">$330.00</span>
                                        </td>
                                        <td>iTest Factory</td>
                                        <td>
                                            <span class="badge bg-success-subtle text-success">Paid</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 fw-medium mb-0">4.7<span class="text-muted fs-11 ms-1">(161
                                                    votes)</span></h5>
                                        </td>
                                    </tr><!-- end tr -->
                                </tbody><!-- end tbody -->
                            </table><!-- end table -->
                        </div>
                    </div>
                </div> <!-- .card-->
            </div>
            <div class="col-xl-6">
                <div class="card card-height-100">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Top Customers</h4>
                        <div class="flex-shrink-0">
                            <div class="dropdown card-header-dropdown">
                                <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <span class="text-muted">Report<i class="mdi mdi-chevron-down ms-1"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Download Report</a>
                                    <a class="dropdown-item" href="#">Export</a>
                                    <a class="dropdown-item" href="#">Import</a>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <img src="assets/images/companies/img-1.png" alt=""
                                                        class="avatar-sm p-2">
                                                </div>
                                                <div>
                                                    <h5 class="fs-14 my-1 fw-medium">
                                                        <a href="apps-ecommerce-seller-details.html"
                                                            class="text-reset">iTest Factory</a>
                                                    </h5>
                                                    <span class="text-muted">Oliver Tyler</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted">Bags and Wallets</span>
                                        </td>
                                        <td>
                                            <p class="mb-0">8547</p>
                                            <span class="text-muted">Stock</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">$541200</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 mb-0">32%<i
                                                    class="ri-bar-chart-fill text-success fs-16 align-middle ms-2"></i>
                                            </h5>
                                        </td>
                                    </tr><!-- end -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <img src="assets/images/companies/img-2.png" alt=""
                                                        class="avatar-sm p-2">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="fs-14 my-1 fw-medium"><a
                                                            href="apps-ecommerce-seller-details.html"
                                                            class="text-reset">Digitech
                                                            Galaxy</a></h5>
                                                    <span class="text-muted">John Roberts</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted">Watches</span>
                                        </td>
                                        <td>
                                            <p class="mb-0">895</p>
                                            <span class="text-muted">Stock</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">$75030</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 mb-0">79%<i
                                                    class="ri-bar-chart-fill text-success fs-16 align-middle ms-2"></i>
                                            </h5>
                                        </td>
                                    </tr><!-- end -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <img src="assets/images/companies/img-3.png" alt=""
                                                        class="avatar-sm p-2">
                                                </div>
                                                <div class="flex-gow-1">
                                                    <h5 class="fs-14 my-1 fw-medium"><a
                                                            href="apps-ecommerce-seller-details.html"
                                                            class="text-reset">Nesta
                                                            Technologies</a></h5>
                                                    <span class="text-muted">Harley
                                                        Fuller</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted">Bike Accessories</span>
                                        </td>
                                        <td>
                                            <p class="mb-0">3470</p>
                                            <span class="text-muted">Stock</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">$45600</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 mb-0">90%<i
                                                    class="ri-bar-chart-fill text-success fs-16 align-middle ms-2"></i>
                                            </h5>
                                        </td>
                                    </tr><!-- end -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <img src="assets/images/companies/img-8.png" alt=""
                                                        class="avatar-sm p-2">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="fs-14 my-1 fw-medium"><a
                                                            href="apps-ecommerce-seller-details.html"
                                                            class="text-reset">Zoetic
                                                            Fashion</a></h5>
                                                    <span class="text-muted">James Bowen</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted">Clothes</span>
                                        </td>
                                        <td>
                                            <p class="mb-0">5488</p>
                                            <span class="text-muted">Stock</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">$29456</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 mb-0">40%<i
                                                    class="ri-bar-chart-fill text-success fs-16 align-middle ms-2"></i>
                                            </h5>
                                        </td>
                                    </tr><!-- end -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <img src="assets/images/companies/img-5.png" alt=""
                                                        class="avatar-sm p-2">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="fs-14 my-1 fw-medium">
                                                        <a href="apps-ecommerce-seller-details.html"
                                                            class="text-reset">Meta4Systems</a>
                                                    </h5>
                                                    <span class="text-muted">Zoe Dennis</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted">Furniture</span>
                                        </td>
                                        <td>
                                            <p class="mb-0">4100</p>
                                            <span class="text-muted">Stock</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">$11260</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 mb-0">57%<i
                                                    class="ri-bar-chart-fill text-success fs-16 align-middle ms-2"></i>
                                            </h5>
                                        </td>
                                    </tr><!-- end -->
                                </tbody>
                            </table><!-- end table -->
                        </div>

                        <div class="align-items-center mt-4 pt-2 justify-content-between row text-center text-sm-start">
                            <div class="col-sm">
                                <div class="text-muted">
                                    Showing <span class="fw-semibold">5</span> of <span class="fw-semibold">25</span>
                                    Results
                                </div>
                            </div>
                            <div class="col-sm-auto  mt-3 mt-sm-0">
                                <ul class="pagination pagination-separated pagination-sm mb-0 justify-content-center">
                                    <li class="page-item disabled">
                                        <a href="#" class="page-link">←</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">1</a>
                                    </li>
                                    <li class="page-item active">
                                        <a href="#" class="page-link">2</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">3</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">→</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div> <!-- .card-body-->
                </div> <!-- .card-->
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Sales 2025</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div id="column_chart" data-colors='["--vz-danger", "--vz-primary", "--vz-success"]'
                            class="apex-charts" dir="ltr"></div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Purchases 2025</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div id="purchaseChart" data-colors='["--vz-danger", "--vz-primary", "--vz-success"]'
                            class="apex-charts" dir="ltr"></div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div>
        </div>

    </div>
@endsection
@section('script')
    {{-- <script src="assets/js/pages/apexcharts-column.init.js"></script> --}}

    {{-- <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script> --}}
    {{-- @include('admin.dashboard.include.script') --}}
    <script>
        // console.log($('#total-user'));
        $('.total-loading').html('<span class="fa fa-spinner fa-spin"></span>')
        $.ajax({
            url: '{{ url('/') }}',
            data: {
                dashbaord_meta: 1
            },
            success: function(r) {
                $('#total-user').html(r.total_user)
                $('#total-employees').html(r.total_employees)
                $('#total-accounts').html(r.total_accounts)
                $('#total-teams').html(r.total_teams)
            }
        })
        $(function() {
            var chartColumnColors = getChartColorsArray("column_chart"),
                chartColumnDatatalabelColors =
                (chartColumnColors &&
                    ((options = {
                            chart: {
                                height: 350,
                                type: "bar",
                                toolbar: {
                                    show: !1
                                }
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: !1,
                                    columnWidth: "45%",
                                    endingShape: "rounded"
                                },
                            },
                            dataLabels: {
                                enabled: !1
                            },
                            stroke: {
                                show: !0,
                                width: 2,
                                colors: ["transparent"]
                            },
                            series: [{
                                    name: "Sales",
                                    data: [46, 57, 59, 54, 62, 58, 64, 60, 66]
                                },
                                {
                                    name: "Payments",
                                    data: [74, 83, 102, 97, 86, 106, 93, 114, 94]
                                },
                                {
                                    name: "Due Payments",
                                    data: [37, 42, 38, 26, 47, 50, 54, 55, 43],
                                },
                            ],
                            colors: chartColumnColors,
                            xaxis: {
                                categories: [
                                    "Feb",
                                    "Mar",
                                    "Apr",
                                    "May",
                                    "Jun",
                                    "Jul",
                                    "Aug",
                                    "Sep",
                                    "Oct",
                                ],
                            },
                            yaxis: {
                                title: {
                                    text: "$ (thousands)"
                                }
                            },
                            grid: {
                                borderColor: "#f1f1f1"
                            },
                            fill: {
                                opacity: 1
                            },
                            tooltip: {
                                y: {
                                    formatter: function(e) {
                                        return "$ " + e + " thousands";
                                    },
                                },
                            },
                        }),
                        (chart = new ApexCharts(
                            document.querySelector("#column_chart"),
                            options
                        )).render()))

            var chartColumnColors = getChartColorsArray("purchaseChart"),
                chartColumnDatatalabelColors =
                (chartColumnColors &&
                    ((options = {
                            chart: {
                                height: 350,
                                type: "bar",
                                toolbar: {
                                    show: !1
                                }
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: !1,
                                    columnWidth: "45%",
                                    endingShape: "rounded"
                                },
                            },
                            dataLabels: {
                                enabled: !1
                            },
                            stroke: {
                                show: !0,
                                width: 2,
                                colors: ["transparent"]
                            },
                            series: [{
                                    name: "Sales",
                                    data: [46, 57, 59, 54, 62, 58, 64, 60, 66]
                                },
                                {
                                    name: "Payments",
                                    data: [74, 83, 102, 97, 86, 106, 93, 114, 94]
                                },
                                {
                                    name: "Due Payments",
                                    data: [37, 42, 38, 26, 47, 50, 54, 55, 43],
                                },
                            ],
                            colors: chartColumnColors,
                            xaxis: {
                                categories: [
                                    "Feb",
                                    "Mar",
                                    "Apr",
                                    "May",
                                    "Jun",
                                    "Jul",
                                    "Aug",
                                    "Sep",
                                    "Oct",
                                ],
                            },
                            yaxis: {
                                title: {
                                    text: "$ (thousands)"
                                }
                            },
                            grid: {
                                borderColor: "#f1f1f1"
                            },
                            fill: {
                                opacity: 1
                            },
                            tooltip: {
                                y: {
                                    formatter: function(e) {
                                        return "$ " + e + " thousands";
                                    },
                                },
                            },
                        }),
                        (chart = new ApexCharts(
                            document.querySelector("#purchaseChart"),
                            options
                        )).render()))
        });
    </script>
@stop
