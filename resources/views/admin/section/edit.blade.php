<div class="modal fade bd-example-modal-lg" id="editModel" tabindex="-{{ $record->id }}" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myExtraLargeModal">Update {{ $title }}</h4><button
                    class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body dark-modal">
                <form class="add-new-user pt-0" id="edit-user">
                    @method('Put')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-fullname">Name</label>
                            <input type="text" class="form-control form-control-sm " value="{{ $record->name }}"
                                name="name" />
                        </div>

                    </div>
                    <hr>
                    <button type="button" class="btn btn-pill float-end btn-outline-danger btn-sm "
                        data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    <button type="button"
                        onclick="addFormData(event,'post','{{ $url . '/' . $record->id }}','{{ $url }}','edit-user')"
                        class="btn btn-primary btn-pill btn-sm float-end me-sm-3 me-1 data-submit">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    $('.select2').each(function() {
        var $this = $(this);
        $this.wrap('<div class="position-relative"></div>').select2({
            // placeholder: 'Select value',
            dropdownParent: $this.parent()
        });
    });
</script>
