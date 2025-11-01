<div class="dropdown d-inline-block">
    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class="ri-more-fill align-middle"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        @can('edit_' . $permission)
        <li>
            <button type="button" class="dropdown-item edit-item-btn"
                onclick="getEditRecord('{{ $url . '/' . $record->workspace_id . '/users/' . $record->id . '/edit' }}', '#editModel')">
                <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                Edit
            </button>
        </li>
        @endcan
        @can('delete_' . $permission)
        <li>
            <button type="button" class="dropdown-item edit-item-btn"
                onclick="deleteRecordAjax('{{ $url . '/' . $record->workspace_id . '/users/' . $record->id  }}', '#editModel')">
                <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                Delete
            </button>
        </li>
        @endcan

    </ul>
</div>
