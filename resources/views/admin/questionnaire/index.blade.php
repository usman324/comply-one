@extends('layout.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0 float-start">{{ $title }}s</h5>
                        {{-- @can('add_' . $permission) --}}
                        <a href="{{$url . '/create'}}" class="btn btn-primary btn-sm float-end"
                            >
                            <i class="ri-add-circle-line me-2"></i>Add Questionnaire
                        </a>
                        {{-- @endcan --}}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="myTable" class="table table-bordered nowrap table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 20px;"></th>
                                        <th>Title</th>
                                        <th>Section</th>
                                        <th>Questions</th>
                                        <th>Responses</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div>
    
    <div id="editRecord"></div>
    <div id="addRecord"></div>
@endsection

@section('script')
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
                        d.table = 1;
                        d.title = $('#title').val();
                        d.category = $('#category').val();
                        d.status = $('#status').val();
                    },
                },
                columns: [
                    { data: 'actions' },
                    { data: 'title' },
                    { data: 'section' },
                    { data: 'questions_count' },
                    { data: 'responses_count' },
                    { data: 'status' },
                    { data: 'created_at' }
                ],
                order: [[6, 'desc']],
                buttons: datatable_buttons,
                ...datatable_setting
            });

            $('#myTable_info').addClass('float-right');
            $('#myTable_paginate').addClass('float-end');
        });
    </script>
@stop