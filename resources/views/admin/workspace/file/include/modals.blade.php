<!-- Create/Edit Folder Modal -->
<div class="modal fade" id="createFolderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createFolderModalLabel">Create Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="folderIdInput">
                <div class="mb-3">
                    <label for="folderNameInput" class="form-label">Folder Name <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" id="folderNameInput" required>
                </div>
                <div class="mb-3">
                    <label for="folderDescInput" class="form-label">Description</label>
                    <textarea class="form-control form-control-sm" id="folderDescInput" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" id="saveFolderBtn">
                    <i class="ri-add-line"></i> Create Folder
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit File Modal -->
<div class="modal fade" id="createFileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createFileModalLabel">Create File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="fileIdInput">
                <div class="mb-3">
                    <label for="fileNameInput" class="form-label">File Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" id="fileNameInput" required>
                </div>
                <div class="mb-3">
                    <label for="fileTypeInput" class="form-label">File Type <span class="text-danger">*</span></label>
                    <select class="form-control form-control-sm " id="fileTypeInput" required>
                        <option value="">Select Type</option>
                        <option value="documents">Documents</option>
                        <option value="images">Images</option>
                        <option value="video">Video</option>
                        <option value="music">Music</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fileFolderInput" class="form-label">Folder <span class="text-danger">*</span></label>
                    <select class="form-control form-control-sm " id="fileFolderInput" required>
                        <option value="">Select Folder</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fileSizeInput" class="form-label">File <span class="text-danger">*</span></label>
                    <input type="file" class="form-control form-control-sm" id="file" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" id="saveFileBtn">
                    <i class="ri-add-line"></i> Create File
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="ri-delete-bin-line text-danger" style="font-size: 3rem;"></i>
                </div>
                <p class="text-center mb-0" id="deleteMessage">
                    Are you sure you want to delete this item?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="ri-delete-bin-line"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>
