@extends('layout.master')
@section('content')
    <div class="container-fluid ">
        <div class="profile-foreground position-relative mx-n4 mt-n4">
            <div class="profile-wid-bg">
                <img src="assets/images/profile-bg.jpg" alt="" class="profile-wid-img" />
            </div>
        </div>
        <div class="pt-4 mb-4 mb-lg-3 pb-lg-4 profile-wrapper">
            <div class="row g-4">
                <div class="col-auto">
                    <div class="avatar-lg">
                        <img src="{{ $record->getImage() }}" alt="user-img" class="img-thumbnail rounded-circle" />
                    </div>
                </div>
                <!--end col-->
                <div class="col">
                    <div class="p-2">
                        <h3 class="text-white mb-1">{{ $record->name }}</h3>
                        <p class="text-white text-opacity-75">Cusotmer</p>
                        <div class="hstack text-white-50 gap-1">
                            <div class="me-2"><i
                                    class="ri-map-pin-user-line me-1 text-white text-opacity-75 fs-16 align-middle"></i>{{ $record->address }}
                            </div>
                            <div>
                                <i
                                    class="ri-building-line me-1 text-white text-opacity-75 fs-16 align-middle"></i>{{ $record->phone }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div>
                    <div class="d-flex profile-wrapper">
                        <!-- Nav tabs -->
                        <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                                    <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span
                                        class="d-none d-md-inline-block">Overview</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-14" data-bs-toggle="tab" href="#activities" role="tab">
                                    <i class="ri-list-unordered d-inline-block d-md-none"></i> <span
                                        class="d-none d-md-inline-block">Sales</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-14" data-bs-toggle="tab" href="#projects" role="tab">
                                    <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span
                                        class="d-none d-md-inline-block">Payments</span>
                                </a>
                            </li>
                        </ul>
                        <div class="flex-shrink-0">
                            <a href="javascript:"
                                onclick="getEditRecord('{{ $url . '/' . $record->id . '/edit' }}','#editModel')"
                                class="btn btn-success"><i class="ri-edit-box-line align-bottom"></i> Edit Profile</a>
                        </div>
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content pt-4 text-muted">
                        <div class="tab-pane active" id="overview-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-xxl-3">
                                    {{-- <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-5">Complete Your Profile</h5>
                                            <div class="progress animated-progress custom-progress progress-label">
                                                <div class="progress-bar bg-danger" role="progressbar" style="width: 30%"
                                                    aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
                                                    <div class="label">30%</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">Info</h5>
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <th class="ps-0" scope="row">Full Name :</th>
                                                            <td class="text-muted">{{ $record->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="ps-0" scope="row">Mobile :</th>
                                                            <td class="text-muted">{{ $record->phone }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="ps-0" scope="row">E-mail :</th>
                                                            <td class="text-muted">{{ $record->email }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="ps-0" scope="row">Location :</th>
                                                            <td class="text-muted">{{ $record->address }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th class="ps-0" scope="row">Joining Date</th>
                                                            <td class="text-muted">{{ dateFormat($record->created_at) }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->

                                </div>
                                <!--end col-->
                                <div class="col-xxl-9">



                                    <div class="row">
                                        <div class="col-lg-4 col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm flex-shrink-0">
                                                            <span
                                                                class="avatar-title bg-light text-primary rounded-circle fs-3">
                                                                <i class="ri-money-dollar-circle-fill align-middle"></i>
                                                            </span>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">
                                                                Total Sales</p>
                                                            <h4 class=" mb-0">$<span class="counter-value"
                                                                    data-target="{{ $record->getTotalAmount() }}">{{ $record->getTotalAmount() }}</span>
                                                            </h4>
                                                        </div>
                                                        <div class="flex-shrink-0 align-self-end">

                                                        </div>
                                                    </div>
                                                </div><!-- end card body -->
                                            </div><!-- end card -->
                                        </div><!-- end col -->
                                        <div class="col-lg-4 col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm flex-shrink-0">
                                                            <span
                                                                class="avatar-title bg-light text-primary rounded-circle fs-3">
                                                                <i class="ri-arrow-up-circle-fill align-middle"></i>
                                                            </span>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">
                                                                Total Payments</p>
                                                            <h4 class=" mb-0">$<span class="counter-value"
                                                                    data-target="{{ $record->getPaidAmount() }}">{{ $record->getPaidAmount() }}</span>
                                                            </h4>
                                                        </div>
                                                        <div class="flex-shrink-0 align-self-end">

                                                        </div>
                                                    </div>
                                                </div><!-- end card body -->
                                            </div><!-- end card -->
                                        </div><!-- end col -->
                                        <div class="col-lg-4 col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm flex-shrink-0">
                                                            <span
                                                                class="avatar-title bg-light text-primary rounded-circle fs-3">
                                                                <i class="ri-arrow-down-circle-fill align-middle"></i>
                                                            </span>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">
                                                                Due Amount</p>
                                                            <h4 class=" mb-0">$<span class="counter-value"
                                                                    data-target="{{ $record->getDueAmount() }}">{{ $record->getDueAmount() }}</span>
                                                            </h4>
                                                        </div>
                                                        <div class="flex-shrink-0 align-self-end">
                                                        </div>
                                                    </div>
                                                </div><!-- end card body -->
                                            </div><!-- end card -->
                                        </div><!-- end col -->
                                        <div class="col-lg-12">
                                        </div><!-- end col -->
                                    </div><!-- end row -->
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <div class="tab-pane fade" id="activities" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="myTable" class="table table-bordered  nowrap table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Sale ID</th>
                                                    <th>Customer</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Total Amount</th>
                                                    <th>Paid</th>
                                                    <th>Due</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th><strong>Total</strong></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <!--end card-body-->
                            </div>
                            <!--end card-->
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane fade" id="projects" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="myTable2" class="table table-bordered  nowrap table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Date</th>
                                                    <th>Customer</th>
                                                    <th>Sale</th>
                                                    <th>Method</th>
                                                    <th>Status</th>
                                                    <th>Attchment</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th><strong>Total</strong></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <!--end row-->
                                </div>
                                <!--end card-body-->
                            </div>
                            <!--end card-->
                        </div>


                    </div>
                    <!--end tab-content-->
                </div>
            </div>
            <!--end col-->
        </div>
    </div>
    <div id="editRecord">

    </div>
    <div id="addRecord">

    </div>
@endsection
@section('script')
    @include('layout.partial.datatable_script')
    <script>
        $(function() {
            $('a[href="#activities"]').on('shown.bs.tab', function(e) {
                myTable = $('#myTable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('sales') }}?customer_id" + @json($record->id),
                        dataType: "json",
                        type: "get",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: function(d) {
                            d.table = 1,
                                d.name = $('#name').val(),
                                d.email = $('#email').val(),
                                d.phone = $('#phone').val(),
                                d.status = $('#status').val()
                        },
                    },
                    columns: [
                        // {
                        //     data: 'ck'
                        // },

                        {
                            data: 'reference'
                        }, {
                            data: 'customer'
                        }, {
                            data: 'date'
                        }, {
                            data: 'status'
                        }, {
                            data: 'total_amount'
                        }, {
                            data: 'paid'
                        }, {
                            data: 'due'
                        },

                    ],
                    order: [],
                    buttons: datatable_buttons,
                    ...datatable_setting,
                    createdRow: function(row, data, dataIndex) {
                        const totalCost = parseFloat(String(data.total_amount).replace(
                                /[^0-9.-]+/g, '')) ||
                            0;
                        const paid = parseFloat(String(data.paid).replace(/[^0-9.-]+/g, '')) ||
                            0;

                        const $dueCell = $('td', row).eq(
                            6); // Assuming 'due' column is at index 12

                        if (paid >= totalCost) {
                            $dueCell.css('background-color', '#d4edda'); // Green
                        } else if (paid > 0 && paid < totalCost) {
                            $dueCell.css('background-color', '#fff3cd'); // Orange
                        } else if (paid === 0) {
                            $dueCell.css('background-color', '#f8d7da'); // Red
                        }
                    },
                    footerCallback: function() {
                        const api = this.api();
                        const sumColumn = (index) =>
                            api.column(index, {
                                page: 'current'
                            }).data()
                            .reduce((total, val) => {
                                const num = parseFloat(String(val).replace(/[^0-9.-]+/g,
                                    ''));
                                return total + (isNaN(num) ? 0 : num);
                            }, 0).toFixed(2);

                        [4, 5, 6].forEach(i =>
                            $(api.column(i).footer()).html(
                                `<strong>${sumColumn(i)}</strong>`)
                        );
                    }

                });
                $('#myTable_info').addClass('float-right')
                $('#myTable_paginate').addClass('float-end')
            });
            $('a[href="#projects"]').on('shown.bs.tab', function(e) {
                myTable = $('#myTable2').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('payment-customers') }}?customer_id=" +
                            @json($record->id),
                        dataType: "json",
                        type: "get",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: function(d) {
                            d.table = 1,
                                d.name = $('#name').val(),
                                d.email = $('#email').val(),
                                d.phone = $('#phone').val(),
                                d.status = $('#status').val()
                        },
                    },
                    columns: [

                        {
                            data: 'reference'
                        }, {
                            data: 'date'
                        }, {
                            data: 'customer'
                        }, {
                            data: 'sale'
                        }, {
                            data: 'method'
                        }, {
                            data: 'status'
                        }, {
                            data: 'image'
                        }, {
                            data: 'amount'
                        },

                    ],
                    order: [],
                    buttons: datatable_buttons,
                    ...datatable_setting,
                    footerCallback: function() {
                        const api = this.api();
                        const sumColumn = (index) =>
                            api.column(index, {
                                page: 'current'
                            }).data()
                            .reduce((total, val) => {
                                const num = parseFloat(String(val).replace(/[^0-9.-]+/g,
                                    ''));
                                return total + (isNaN(num) ? 0 : num);
                            }, 0).toFixed(2);

                        [7].forEach(i =>
                            $(api.column(i).footer()).html(
                                `<strong>${sumColumn(i)}</strong>`)
                        );
                    }

                });
                $('#myTable2_info').addClass('float-right')
                $('#myTable2_paginate').addClass('float-end')
            });
        });
    </script>
@stop
