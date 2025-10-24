@extends('layout.master')
@section('content')
    <div class="m-4 flex-grow-1 container-p-y">

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0 float-start">{{ $title }}</h5>
            </div>
            <div class="card-datatable table-responsive pt-0">
                <table id="myTable" class="table nowrap">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>User</th>
                            <th>Subject</th>
                            <th>Action</th>
                            <th>Ip</th>

                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
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
                            d.type = 'supplier',
                            d.name = $('#name').val(),
                            d.email = $('#email').val(),
                            d.phone = $('#phone').val(),
                            d.address = $('#address').val()
                    },
                },
                columns: [{
                    data: 'created_at'
                }, {
                    data: 'user'
                }, {
                    data: 'type'
                }, {
                    data: 'title'
                }, {
                    data: 'ip'
                }, ],
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
                $('#address').val('');
                myTable.ajax.reload();
            });
        });
    </script>
@stop
