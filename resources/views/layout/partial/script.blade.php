 <!-- JAVASCRIPT -->
 <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
 <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
 <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
 <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
 <script src="{{ asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
 <script src="{{ asset('assets/js/plugins.js') }}"></script>
 <script src="{{ asset('assets/js/jquery.js') }}"></script>

 <!-- apexcharts -->
 <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
 <script>
     const baseUrl = "{{ url('/') }}";
 </script>
 <!-- Vector map-->
 <script src="{{ asset('assets/libs/jsvectormap/jsvectormap.min.js') }}"></script>
 <script src="{{ asset('assets/libs/jsvectormap/maps/world-merc.js') }}"></script>
 <script src="{{ asset('assets/libs/select2/select2.js') }}"></script>
 <script src="{{ asset('assets/libs/toastr/toastr.js') }}"></script>
 <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
 <script src="{{ asset('assets/js/jquery-ui.js') }}"></script>
 <!--Swiper slider js-->
 <script src="{{ asset('assets/libs/swiper/swiper-bundle.min.js') }}"></script>

 <!-- Dashboard init -->
 <script src="{{ asset('assets/js/pages/dashboard-ecommerce.init.js') }}"></script>

 <!-- App js -->
 <script src="{{ asset('assets/js/app.js') }}"></script>
 <script src="{{ asset('assets/my-custom.js?v=2.123') }}"></script>

 @include('layout.partial.datatable_script')
 <script>
     $(function() {


         $('.select2').select2();
         //  $('.select2-selection__rendered').removeClass('w-px-150')
         var currentUrl = window.location.href.split(/[#]/)[0];

         $('#scrollbar a').each(function() {
             var linkUrl = this.href.split(/[#]/)[0];
             var href = $(this).attr('href');
             if (!href || href.startsWith('#')) {
                 return; // continue to next
             }
             //  console.log(href, currentUrl)
             if (linkUrl === currentUrl) {

                 console.log(linkUrl, currentUrl, linkUrl === currentUrl);
                 console.log($(this));
                 //  $(this).addClass('active');
                 $(this).closest('li.nav-item .nav-link').addClass('active');

                 // Expand and mark parent collapse
                 var parentCollapse = $(this).closest('.collapse');
                 if (parentCollapse.length) {
                     parentCollapse.addClass('show');
                     parentCollapse.prev('.nav-link').attr('aria-expanded', 'true');
                     parentCollapse.closest('.nav-item').addClass('active');
                 }
             }
         });
     })

     $(document).on('click', '.row-remove', function(e) {
         e.preventDefault()
         Swal.fire({
             title: "Are you sure?",
             text: "You won't be able to revert this!",
             icon: "warning",
             showCancelButton: true,
             confirmButtonText: "Yes, Remove it!",
             customClass: {
                 confirmButton: "btn btn-primary me-3",
                 cancelButton: "btn btn-label-secondary",
             },
             buttonsStyling: false,
         }).then((result) => {
             if (result.isConfirmed) {
                 $(this).closest('tr').remove()
                 calc()
             }
         })
     })

     function autoCompleteCustomOption(item) {
         return "<table style='width: 100%'><tr><th>" + item.label + "</th></tr></table>"
     }

     function getDependentRecord(event, url, id) {
         loadingStart();
         let select_id = event.target.value;
         $.ajax({
             url: url,
             method: "get",
             data: {
                 select_id: select_id,
             },
             success: function(response) {
                 loadingStop();
                 $("#" + id).html('');
                 $("#" + id).html(response);
             },
             error: function(xhr) {
                 loadingStop();
                 let data = "";
                 if (xhr.status == 400 || xhr.status == 422) {
                     $.each(xhr.responseJSON.errors, function(key, value) {
                         data += "</br>" + value;
                     });
                     showWarn(data);
                 }
                 if (xhr.status == 500) {
                     showWarn(xhr.responseJSON.message);
                 }
             },
         });
     }

     function getCheckedRows() {
         var result = [];
         $(".row-check:checked").each(function() {
             result.push($(this).val());
         });

         if (!result.length) {
             showWarn("Please select any record", "error");
         }

         return result;
     }
 </script>
 @yield('script')
