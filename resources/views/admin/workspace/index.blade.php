@extends('layout.master')
@section('content')
    <div class="container-fluid ">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0 float-start">{{ $title }}s</h5>
                        {{-- @can('add_' . $permission) getAddRecord('{{ $url . '/create' }}','#addModel') --}}

                        <button
                            type="button"
                            class="btn btn-primary btn-sm float-end"
                            onclick="window.location.href='{{ $url }}/create'">
                            <i class="ri-add-circle-line me-2"></i>Add
                        </button>

                        {{-- @endcan --}}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="myTable" class="table table-bordered  nowrap table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 20px;"></th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div>
    <div id="editRecord">

    </div>
    <div id="addRecord">

    </div>
@endsection
@section('script')

<script src="{{ URL::asset('assets/js/pages/form-wizard.init.js') }}"></script>
    {{-- @include('layout.partial.datatable_script') --}}
    <script>
        $(function() {
            myTable = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ $url }}",
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
                        data: 'actions'
                    },

                    {
                        data: 'name'
                    },  {
                        data: 'type'
                    },  {
                        data: 'status'
                    },

                ],
                order: [],
                buttons: datatable_buttons,
                ...datatable_setting

            });
            $('#filterApply').on('click', function() {
                myTable.ajax.reload();
            });
            $('#clearApply').on('click', function() {

                $('#name').val('');
                $('#email').val('');
                $('#phone').val('');
                $('#status').val('').trigger('change');
                myTable.ajax.reload();
            });
            $('#myTable_info').addClass('float-right')
            $('#myTable_paginate').addClass('float-end')
            // $('div.head-label').html('<h5 class="card-title mb-0">Users List</h5>');
        });
    </script>
@stop
