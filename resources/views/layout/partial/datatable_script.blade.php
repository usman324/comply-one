<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script>
    var myTable = '';
    var isLargerThanMobileScreen = ($(window).width() > 480) ? true : false;
    var datatable_setting = {
        // responsive: isLargerThanMobileScreen,
        searching: false,
        // dom: "Bfrtip",
        dom: '<"row mb-3"<"col-10"l><"col-2"B><"col-2"f>>t<"row"<"col-sm-6 col-md-6"i><"col-sm-6 col-md-6"p>>',
        responsive: true,
        // order: [
        //     [2, 'desc']
        // ],
        displayLength: 10,
        // autoWidth: false,
        lengthMenu: [
            [10, 25, 100, 500, 999, -1],
            [10, 25, 100, 500, 999, "@lang('All')"]
        ],
    };
    // $('.dt-column-order').hide()
   
</script>
<script>
    var datatable_buttons = [{
            extend: 'collection',
            className: 'btn btn-label-primary dropdown-toggle me-2',
            text: '<i class="ti ti-file-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
            buttons: [{
                    extend: 'print',
                    text: '<i class="ti ti-printer me-1" ></i>Print',
                    className: 'dropdown-item',

                    customize: function(win) {
                        //customize print view for dark
                        $(win.document.body)
                            .css('color', config.colors.headingColor)
                            .css('border-color', config.colors.borderColor)
                            .css('background-color', config.colors.bodyBg);
                        $(win.document.body)
                            .find('table')
                            .addClass('compact')
                            .css('color', 'inherit')
                            .css('border-color', 'inherit')
                            .css('background-color', 'inherit');
                    }
                },
                {
                    extend: 'csv',
                    text: '<i class="ti ti-file-text me-1" ></i>Csv',
                    className: 'dropdown-item',

                },
                {
                    extend: 'excel',
                    text: '<i class="ti ti-file-spreadsheet me-1"></i>Excel',
                    className: 'dropdown-item',

                },
                {
                    extend: 'pdf',
                    text: '<i class="ti ti-file-description me-1"></i>Pdf',
                    className: 'dropdown-item',

                },
                {
                    extend: 'copy',
                    text: '<i class="ti ti-copy me-1" ></i>Copy',
                    className: 'dropdown-item',

                }
            ]
        },
        // {
        //     text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New Record</span>',
        //     className: 'create-new btn btn-primary'
        // }
    ];
    //  var datatable_buttons =  ["copy", "csv", "excel", "print", "pdf"];
</script>
