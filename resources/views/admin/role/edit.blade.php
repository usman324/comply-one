<style>
    .role table> :not(caption)>*>* {
        padding: 0.55rem 0.600rem !important;
    }

    .role .table th {
        text-transform: uppercase;
        font-size: 0.6000rem !important;
    }

    .role .table td {
        text-transform: uppercase;
        font-size: 0.7000rem !important;
    }
</style>
<div class="modal fade bd-example-modal-lg" id="editModel" tabindex="-{{ $record->id }}" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myExtraLargeModal">Update {{ $title }}</h4><button
                    class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body dark-modal">
                <form class="add-new-user pt-0" id="edit-user">
                    @method('Put')
                    <div class="row">
                        <div class="col-12 mb-4">
                        <label class="form-label">Role Name</label>
                        <input type="text" name="name" value="{{ $record->name }}" class="form-control" />
                    </div>
                    <div class="col-12">
                        <h5>Role Permissions</h5>
                        <!-- Permission table -->
                        <div class="table-responsive">
                            <table class="table table-flush-spacing">
                                <tbody>
                                    <tr>
                                        <td class="text-nowrap fw-medium">
                                            Administrator Access
                                            <i class="ti ti-info-circle" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Allows a full access to the system"></i>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label" for="edit_permissions">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="edit_permissions" />
                                                    Select All
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    @foreach ($permissions->groupBy('category') as $key => $pc)
                                        <tr>
                                            <td class="text-nowrap fw-medium text-capitalize">
                                                {{-- <input type="checkbox" class="permission"
                                                    onclick="permissionCategory('{{ $key }}',$(this)[0])"id="{{ $key }}"> --}}
                                                {{ str_replace('_', ' ', $key) }}
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @foreach ($pc as $permission)
                                                        <div class="form-check me-3 me-lg-5">
                                                            <label class="form-check-label"
                                                                for="p-edit-{{ $permission->id }}">
                                                                <input @if ($record->hasPermissionTo($permission->name)) checked @endif
                                                                    class="form-check-input edit_permission {{ $permission->category }}"
                                                                    type="checkbox" id="p-edit-{{ $permission->id }}"
                                                                    name="{{ $permission->id }}" />
                                                                {{ $permission->display_name }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Permission table -->
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