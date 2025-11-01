<div class="modal fade bd-example-modal-lg" id="editModel" tabindex="-{{ $record->id }}" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myExtraLargeModal">Update {{ $title }}</h4><button class="btn-close py-0"
                    type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body dark-modal">
                <form class="add-new-user pt-0" id="edit-user">
                    @method('Put')
                    <div class="row">
                        @if (getUser()->hasRole('admin'))
                        <div class="col-md-6 mb-3">
                            <label for="workspace_id" class="form-label">Workspace</label>
                            <select id="workspace_id" name="workspace_id" class="select2 form-control ">
                                <option value="">select workspace</option>
                                @foreach (workspaces() as $workspace)
                                <option value="{{ $workspace->id }}" @selected($record->workspace_id == $workspace->id)>
                                    {{ $workspace->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <input type="text" hidden name="workspace_id" value="{{ getUser()->workspace_id }}">
                        @endif
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-fullname">First Name</label>
                            <input type="text" class="form-control form-control-sm " value="{{ $record->first_name }}"
                                name="first_name" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-fullname">Last Name</label>
                            <input type="text" class="form-control form-control-sm " value="{{ $record->last_name }}"
                                name="last_name" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-email">Email</label>
                            <input type="text" class="form-control form-control-sm " value="{{ $record->email }}"
                                name="email" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-contact">Avatar</label>
                            <input type="file" class="form-control form-control-sm  " name="avatar" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-contact">Phone</label>
                            <input type="text" value="{{ $record->phone }}" class="form-control form-control-sm  "
                                name="phone" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-contact">Password</label>
                            <input type="password" class="form-control form-control-sm  " name="password" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-contact">Password Confirmation</label>
                            <input type="password" class="form-control form-control-sm  "
                                name="password_confirmation" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-company">Address</label>
                            <input type="text" value="{{ $record->address }}" class="form-control form-control-sm "
                                name="address" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-email">Status</label>
                            <select id="status" name="status" class="form-control form-control-sm  select2 ">
                                <option value="active" @if ($record->status === 'active') selected @endif>Active
                                </option>
                                <option value="inactive" @if ($record->status === 'inactive') selected @endif>In-Active
                                </option>
                            </select>
                        </div>
                        @can('assign_role')
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="add-user-email">Role</label>
                            <select id="role" name="role_id" class="form-control form-control-sm  select2">
                                <option value="">select role</option>
                                @foreach ($roles as $role)
                                <option value="{{ $role->name }}" @if ($role->name ===
                                    $record->getRoleNames()?->first()) selected @endif>
                                    {{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endcan

                    </div>
                    <hr>
                    <button type="button" class="btn btn-pill float-end btn-outline-danger btn-sm "
                        data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    <button type="button"
                        onclick="addFormData(event,'post','{{ $url . '/' .$record->workspace_id .'/users/'. $record->id }}','{{ $url }}','edit-user')"
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
