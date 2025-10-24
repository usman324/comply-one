<div class="modal fade bd-example-modal-lg" id="addModel" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myExtraLargeModal">Add {{ $title }}</h4><button class="btn-close py-0"
                    type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body dark-modal">
                <form class="add-new-user pt-0" id="add-user">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-fullname">Image</label>
                            <input type="file" class="form-control form-control-sm " name="image" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-fullname">Name</label>
                            <input type="text" class="form-control form-control-sm " name="name" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-fullname">Email</label>
                            <input type="text" class="form-control form-control-sm " name="email" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-fullname">Phone</label>
                            <input type="text" class="form-control form-control-sm " name="phone" />
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="add-user-fullname">Address</label>
                            <input type="text" class="form-control form-control-sm " name="address" />
                        </div>
                    </div>
                    <hr>
                    <button type="button" class="btn btn-pill btn-outline-danger float-end btn-sm "
                        data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    <button type="button"
                        onclick="addFormData(event,'post','{{ $url }}','{{ $url }}','add-user','{{ $select_id }}','Customer')"
                        class="btn btn-primary btn-pill btn-sm me-sm-3 float-end me-1 data-submit">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $('.select2').select2()
    // each(function() {
    //     var $this = $(this);
    //     $this.wrap('<div class="position-relative"></div>').select2({
    //         // placeholder: 'Select value',
    //         dropdownParent: $this.parent()
    //     });
    // });
</script>
