<div class="dropdown d-inline-block">
    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class="ri-more-fill align-middle"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        @can('view_' . $permission)
        <li><a class="dropdown-item edit-item-btn" href="{{ $url . '/' . $record->id . '?assign=1' }}"><i
                    class="ri-eye-fill align-bottom me-2 text-muted"></i>
                Assign Questions</a>
        </li>
        <li><a class="dropdown-item edit-item-btn" href="{{ $url . '/' . $record->id }}"><i
                    class="ri-eye-fill align-bottom me-2 text-muted"></i>
                View</a>
        </li>
        @endcan
        @can('edit_' . $permission)
        <li><a class="dropdown-item edit-item-btn" href="{{ $url . '/' . $record->id . '/edit' }}"><i
                    class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                Edit</a>
        </li>
        @endcan
        @can('delete_' . $permission)
        <li>
            <button type="button" class="dropdown-item remove-item-btn"
                onclick="deleteRecordAjax('{{ $url . '/' . $record->workspace_id . '/users/' . $record->id }}')">
                <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                Delete
            </button>
        </li>
        @endcan

    </ul>
</div>
