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
                            <label class="form-label" for="add-user-fullname">First Name</label>
                            <input type="text" class="form-control form-control-sm " name="first_name" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-fullname">Last Name</label>
                            <input type="text" class="form-control form-control-sm " name="last_name" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-email">Email</label>
                            <input type="text" class="form-control form-control-sm " name="email" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-contact">Avatar</label>
                            <input type="file" class="form-control form-control-sm  phone-mask" name="avatar" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-contact">Phone</label>
                            <input type="text" class="form-control form-control-sm  phone-mask" name="phone" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-contact">Password</label>
                            <input type="password" class="form-control form-control-sm  phone-mask" name="password" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-contact">Password Confirmation</label>
                            <input type="password" class="form-control form-control-sm  phone-mask"
                                name="password_confirmation" />
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-company">Address</label>
                            <input type="text" class="form-control form-control-sm " name="address" />
                        </div>
                        @can('assign_role')
                            <div class="col-md-6 mb-3">
                                <label for="role"  class="form-label">Role</label>
                                <select id="role" name="role_id" class="select2 form-control ">
                                    <option value="">select role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endcan
                    </div>
                    <hr>
                    <button type="button" class="btn btn-pill btn-outline-danger float-end btn-sm "
                        data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    <button type="button"
                        onclick="addFormData(event,'post','{{ $url.'/'.$workspace->id.'/users' }}','{{ $url }}','add-user')"
                        class="btn btn-primary btn-pill btn-sm me-sm-3 float-end me-1 data-submit">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $('.select2').each(function() {
        var $this = $(this);
        $this.wrap('<div class="position-relative"></div>').select2({
            dropdownParent: $this.parent()
        });
    });
</script>
