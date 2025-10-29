{{-- <ul class="action"> --}}
{{-- @can('edit_' . $permission) --}}
{{-- <li class="edit"> <a href="javascript:"
                onclick="getEditRecord('{{ $url . '/' . $record->id . '/edit' }}','#editModel')"><i
                    class="fa-regular fa-pen-to-square"></i></a></li> --}}
{{-- @endcan
    @can('delete_' . $permission) --}}
{{-- <li class="delete"><a href='javascript:void(0)' onclick="deleteRecordAjax('{{ $url . '/' . $record->id }}')"><i
                    class="fa-solid fa-trash-can"></i></a></li> --}}
@endcan
{{-- </ul> --}}
<div class="dropdown d-inline-block">
    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ri-more-fill align-middle"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        @can('edit_' . $permission)
            <li><a class="dropdown-item edit-item-btn" href="javascript:"
                    onclick="getEditRecord('{{ $url . '/' . $record->id . '/edit' }}','#editModel')"><i
                        class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                    Edit</a>
            </li>
        @endcan
        @can('delete_' . $permission)
            <li>
                <a class="dropdown-item remove-item-btn" href='javascript:void(0)'
                    onclick="deleteRecordAjax('{{ $url . '/' . $record->id }}')">
                    <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                    Delete
                </a>
            </li>
        @endcan

    </ul>
</div>
