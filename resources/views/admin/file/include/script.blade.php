<script>
    // Configuration
    const WORKSPACE_ID = 1; // Default workspace ID
    const USER_ID = '{{ getUser()->id }}'; // Default user ID

    // State
    let currentFolderId = null;
    let currentFilter = 'all';
    let folders = [];
    let files = [];
    let deleteItemId = null;
    let deleteItemType = null;

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        initializeApp();
    });

    function initializeApp() {
        loadFolders();
        loadFiles();
        loadStatistics();
        setupEventListeners();
    }

    // Event Listeners
    function setupEventListeners() {
        // Save Folder Button
        document.getElementById('saveFolderBtn').addEventListener('click', saveFolder);

        // Save File Button
        document.getElementById('saveFileBtn').addEventListener('click', saveFile);

        // Confirm Delete Button
        document.getElementById('confirmDeleteBtn').addEventListener('click', confirmDelete);

        // Search Input
        document.getElementById('searchInput').addEventListener('input', function(e) {
            searchFiles(e.target.value);
        });

        // File Type Filter
        document.getElementById('file-type').addEventListener('change', function(e) {
            currentFilter = e.target.value;
            loadFiles();
        });

        // Filter Menu Items
        document.querySelectorAll('.file-manager-menu a[data-filter]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const filter = this.getAttribute('data-filter');
                filterFiles(filter);
            });
        });

        // Close Overview Buttons
        document.querySelectorAll('.close-btn-overview, .close-btn-file-overview').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('folder-overview').style.display = 'block';
                document.getElementById('file-overview').style.display = 'none';
            });
        });

        // Modal Reset
        const createFolderModal = document.getElementById('createFolderModal');
        createFolderModal.addEventListener('hidden.bs.modal', function() {
            resetFolderForm();
        });

        const createFileModal = document.getElementById('createFileModal');
        createFileModal.addEventListener('hidden.bs.modal', function() {
            resetFileForm();
        });
    }

    // API Functions

    // Folders
    async function loadFolders() {
        try {
            const response = await fetch(
                `folders?workspace_id=${WORKSPACE_ID}&parent_folder_id=${currentFolderId || ''}`);
            const data = await response.json();

            if (data.success) {
                folders = data.data;
                renderFolders(folders);
                updateFolderMenu(folders);
                updateFolderSelect(folders);
            }
        } catch (error) {
            console.error('Error loading folders:', error);
            showNotification('Failed to load folders', 'error');
        }
    }

    async function saveFolder() {
        const folderId = document.getElementById('folderIdInput').value;
        const folderName = document.getElementById('folderNameInput').value.trim();
        const folderDesc = document.getElementById('folderDescInput').value.trim();

        if (!folderName) {
            showNotification('Please enter folder name', 'warning');
            return;
        }

        const folderData = {
            workspace_id: WORKSPACE_ID,
            parent_folder_id: currentFolderId,
            name: folderName,
            description: folderDesc
        };

        try {
            const url = folderId ? `folders/${folderId}` : `folders`;
            const method = folderId ? 'PUT' : 'POST';

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(folderData)
            });

            const data = await response.json();

            if (data.success) {
                showNotification(data.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('createFolderModal')).hide();
                loadFolders();
                loadStatistics();
            } else {
                showNotification(data.message || 'Failed to save folder', 'error');
            }
        } catch (error) {
            console.error('Error saving folder:', error);
            showNotification('Failed to save folder', 'error');
        }
    }

    async function editFolder(folderId) {
        try {
            const response = await fetch(`folders/${folderId}`);
            const data = await response.json();

            if (data.success) {
                const folder = data.data;
                document.getElementById('folderIdInput').value = folder.id;
                document.getElementById('folderNameInput').value = folder.name;
                document.getElementById('folderDescInput').value = folder.description || '';
                document.getElementById('createFolderModalLabel').textContent = 'Edit Folder';
                document.getElementById('saveFolderBtn').innerHTML = '<i class="ri-save-line"></i> Update Folder';

                new bootstrap.Modal(document.getElementById('createFolderModal')).show();
            }
        } catch (error) {
            console.error('Error loading folder:', error);
            showNotification('Failed to load folder', 'error');
        }
    }

    async function deleteFolder(folderId) {
        deleteItemId = folderId;
        deleteItemType = 'folder';
        document.getElementById('deleteMessage').textContent =
            'Are you sure you want to delete this folder? All files inside will also be deleted.';
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    async function openFolder(folderId) {
        currentFolderId = folderId;
        loadFolders();
        loadFiles();
    }

    // Files
    async function loadFiles() {
        try {
            const params = new URLSearchParams({
                workspace_id: WORKSPACE_ID,
                folder_id: currentFolderId || '',
                file_type: currentFilter === 'all' ? '' : currentFilter,
                per_page: 20
            });

            const response = await fetch(`files?${params}`);
            const data = await response.json();

            if (data.success) {
                files = data.data;
                renderFiles(files);
                updatePagination(data.pagination);
            }
        } catch (error) {
            console.error('Error loading files:', error);
            showNotification('Failed to load files', 'error');
        }
    }

    async function saveFile() {
        const fileId = document.getElementById('fileIdInput').value;
        const fileName = document.getElementById('fileNameInput').value.trim();
        const fileType = document.getElementById('fileTypeInput').value;
        const folderId = document.getElementById('fileFolderInput').value;
        const fileInput = document.getElementById('file');
        const file = fileInput.files[0]; // ✅ actual file object

        if (!fileName || !fileType || !folderId || !file) {
            showNotification('Please fill all required fields', 'warning');
            return;
        }

        const formData = new FormData();
        formData.append('workspace_id', WORKSPACE_ID);
        formData.append('folder_id', folderId);
        formData.append('display_name', fileName);
        formData.append('file_type', fileType);
        formData.append('file_size', file.size);
        formData.append('description', '');
        formData.append('file', file); // ✅ include actual file

        try {
            const url = fileId ? `files/${fileId}` : `files`;
            const method = fileId ? 'POST' : 'POST'; // use POST for both if backend handles _method

            if (fileId) {
                formData.append('_method', 'PUT'); // Laravel expects this for PUT
            }

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData // ✅ send formData, not JSON
            });

            const data = await response.json();

            if (data.success) {
                showNotification(data.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('createFileModal')).hide();
                loadFiles();
                loadStatistics();
            } else {
                showNotification(data.message || 'Failed to save file', 'error');
            }
        } catch (error) {
            console.error('Error saving file:', error);
            showNotification('Failed to save file', 'error');
        }
    }

    async function editFile(fileId) {
        try {
            const response = await fetch(`files/${fileId}`);
            const data = await response.json();

            if (data.success) {
                const file = data.data;

                // Fill form inputs
                document.getElementById('fileIdInput').value = file.id;
                document.getElementById('fileNameInput').value = file.display_name || '';
                document.getElementById('fileTypeInput').value = getFileTypeFromMime(file.mime_type || '');
                document.getElementById('fileFolderInput').value = file.folder_id || '';

                // ⚠️ Important: Clear the file input (can't set a value directly for security reasons)
                const fileInput = document.getElementById('file');
                fileInput.value = ''; // must clear manually

                // Optionally display file info (so user knows what file is currently stored)
                const filePreview = document.getElementById('filePreviewInfo');
                if (filePreview) {
                    filePreview.innerHTML = `
                    <div class="mt-2">
                        <small class="text-muted">Current file: 
                            <a href="${file.file_url}" target="_blank">${file.display_name}</a>
                            (${(file.file_size_bytes / (1024 * 1024)).toFixed(2)} MB)
                        </small>
                    </div>
                `;
                }

                // Update modal title and button text
                document.getElementById('createFileModalLabel').textContent = 'Edit File';
                document.getElementById('saveFileBtn').innerHTML = '<i class="ri-save-line"></i> Update File';

                // Show the modal
                new bootstrap.Modal(document.getElementById('createFileModal')).show();
            } else {
                showNotification('File not found', 'error');
            }
        } catch (error) {
            console.error('Error loading file:', error);
            showNotification('Failed to load file', 'error');
        }
    }


    async function deleteFile(fileId) {
        deleteItemId = fileId;
        deleteItemType = 'file';
        document.getElementById('deleteMessage').textContent = 'Are you sure you want to delete this file?';
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    async function viewFile(fileId) {
        try {
            const response = await fetch(`files/${fileId}`);
            const data = await response.json();

            if (data.success) {
                const file = data.data;
                showFilePreview(file);
            }
        } catch (error) {
            console.error('Error loading file:', error);
            showNotification('Failed to load file', 'error');
        }
    }

    async function toggleFileStar(fileId) {
        try {
            const response = await fetch(`files/${fileId}/toggle-star`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (data.success) {
                showNotification(data.message, 'success');
                loadFiles();
            }
        } catch (error) {
            console.error('Error toggling star:', error);
            showNotification('Failed to update file', 'error');
        }
    }

    async function confirmDelete() {
        if (!deleteItemId || !deleteItemType) return;

        try {
            const url = deleteItemType === 'folder' ?
                `folders/${deleteItemId}` :
                `files/${deleteItemId}`;

            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (data.success) {
                showNotification(data.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();

                if (deleteItemType === 'folder') {
                    loadFolders();
                } else {
                    loadFiles();
                }
                loadStatistics();
            } else {
                showNotification(data.message || 'Failed to delete', 'error');
            }
        } catch (error) {
            console.error('Error deleting:', error);
            showNotification('Failed to delete', 'error');
        } finally {
            deleteItemId = null;
            deleteItemType = null;
        }
    }

    // Statistics
    async function loadStatistics() {
        try {
            const response = await fetch(`statistics?workspace_id=${WORKSPACE_ID}`);
            const data = await response.json();

            if (data.success) {
                renderStatistics(data.data);
            }
        } catch (error) {
            console.error('Error loading statistics:', error);
        }
    }

    // Render Functions
    function renderFolders(folders) {
        const container = document.getElementById('folderlist-data');

        if (folders.length === 0) {
            container.innerHTML = `
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="ri-folder-line" style="font-size: 48px; color: #ccc;"></i>
                    <p class="text-muted mt-3">No folders found</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                        <i class="ri-add-line"></i> Create Your First Folder
                    </button>
                </div>
            </div>
        `;
            return;
        }

        container.innerHTML = folders.map(folder => `
        <div class="col-xxl-3 col-md-4 col-sm-6 folder-card">
            <div class="card bg-light shadow-none">
                <div class="card-body">
                    <div class="d-flex mb-1">
                        <div class="form-check form-check-danger mb-3 fs-15 flex-grow-1">
                            <input class="form-check-input" type="checkbox" value="${folder.id}" id="folder_${folder.id}">
                            <label class="form-check-label" for="folder_${folder.id}"></label>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-ghost-primary btn-icon btn-sm dropdown" type="button" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill fs-16 align-bottom"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="openFolder(${folder.id})">Open</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="editFolder(${folder.id})">Rename</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="deleteFolder(${folder.id})">Delete</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="text-center" onclick="openFolder(${folder.id})" style="cursor: pointer;">
                        <div class="mb-2">
                            <i class="ri-folder-2-fill align-bottom text-warning display-5"></i>
                        </div>
                        <h6 class="fs-15 folder-name">${folder.name}</h6>
                    </div>
                    <div class="hstack mt-4 text-muted">
                        <span class="me-auto"><b>${folder.file_count}</b> Files</span>
                        <span><b>${folder.folder_size}</b></span>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
    }

    function renderFiles(files) {
        const tbody = document.getElementById('file-list');

        if (files.length === 0) {
            tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-5">
                    <i class="ri-file-line" style="font-size: 48px; color: #ccc;"></i>
                    <p class="text-muted mt-3 mb-0">No files found</p>
                </td>
            </tr>
        `;
            return;
        }

        tbody.innerHTML = files.map(file => `
        <tr>
            <td>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 fs-24 file-icon-cell me-2">
                        <i class="${file.file_icon} text-${getFileColor(file.extension)}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <a href="javascript:void(0);" onclick="viewFile(${file.id})" class="file-name-link">
                            ${file.display_name}
                        </a>
                        ${file.is_starred ? '<i class="ri-star-fill text-warning ms-1"></i>' : ''}
                    </div>
                </div>
            </td>
            <td>${file.folder_name}</td>
            <td>${file.file_size}</td>
            <td>${file.created_at}</td>
            <td class="text-center">
                <div class="dropdown">
                    <button class="btn btn-sm btn-soft-secondary" type="button" data-bs-toggle="dropdown">
                        <i class="ri-more-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="javascript:void(0);" onclick="viewFile(${file.id})"><i class="ri-eye-line me-2"></i>View</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);" onclick="toggleFileStar(${file.id})"><i class="ri-star-line me-2"></i>${file.is_starred ? 'Unstar' : 'Star'}</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);" onclick="editFile(${file.id})"><i class="ri-edit-line me-2"></i>Edit</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="deleteFile(${file.id})"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    `).join('');
    }

    function renderStatistics(stats) {
        const overviewStats = document.getElementById('overview-stats');

        overviewStats.innerHTML = `
        <li>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-xs">
                        <div class="avatar-title rounded bg-secondary-subtle text-secondary">
                            <i class="ri-file-text-line fs-17"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="mb-1 fs-15">Documents</h5>
                    <p class="mb-0 fs-12 text-muted">${stats.documents.count} files</p>
                </div>
                <b>${stats.documents.size}</b>
            </div>
        </li>
        <li>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-xs">
                        <div class="avatar-title rounded bg-success-subtle text-success">
                            <i class="ri-gallery-line fs-17"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="mb-1 fs-15">Media</h5>
                    <p class="mb-0 fs-12 text-muted">${stats.media.count} files</p>
                </div>
                <b>${stats.media.size}</b>
            </div>
        </li>
        <li>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-xs">
                        <div class="avatar-title rounded bg-warning-subtle text-warning">
                            <i class="ri-folder-2-line fs-17"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="mb-1 fs-15">Projects</h5>
                    <p class="mb-0 fs-12 text-muted">${stats.projects.count} folders</p>
                </div>
                <b>${stats.projects.size}</b>
            </div>
        </li>
        <li>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-xs">
                        <div class="avatar-title rounded bg-primary-subtle text-primary">
                            <i class="ri-error-warning-line fs-17"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="mb-1 fs-15">Others</h5>
                    <p class="mb-0 fs-12 text-muted">${stats.others.count} files</p>
                </div>
                <b>${stats.others.size}</b>
            </div>
        </li>
    `;

        // Update storage
        const usedGB = parseFloat(stats.storage.used);
        document.getElementById('usedStorage').textContent = usedGB.toFixed(2);
        document.getElementById('storageBar').style.width = stats.storage.percentage + '%';
    }

    function showFilePreview(file) {
        document.getElementById('folder-overview').style.display = 'none';
        document.getElementById('file-overview').style.display = 'block';

        document.getElementById('preview-file-icon').innerHTML =
            `<i class="${file.file_icon} text-${getFileColor(file.extension)}"></i>`;
        document.getElementById('preview-file-name').textContent = file.display_name;
        document.getElementById('preview-file-size').textContent = file.file_size;
        document.getElementById('preview-create-date').textContent = file.created_at;

        document.getElementById('file-description-table').innerHTML = `
        <tr>
            <th scope="row" style="width: 35%;">File Name:</th>
            <td>${file.display_name}</td>
        </tr>
        <tr>
            <th scope="row">File Type:</th>
            <td>${file.mime_type || 'Unknown'}</td>
        </tr>
        <tr>
            <th scope="row">Size:</th>
            <td>${file.file_size}</td>
        </tr>
        <tr>
            <th scope="row">Created:</th>
            <td>${file.created_at}</td>
        </tr>
        <tr>
            <th scope="row">Folder:</th>
            <td>${file.folder_name}</td>
        </tr>
        <tr>
            <th scope="row">Uploaded By:</th>
            <td>${file.uploaded_by}</td>
        </tr>
    `;

        // Setup delete button
        document.getElementById('delete-file-preview-btn').onclick = function() {
            deleteFile(file.id);
        };
    }

    function updateFolderMenu(folders) {
        const menu = document.getElementById('dynamicFolderMenu');
        if (menu) {
            menu.innerHTML = folders.slice(0, 5).map(folder => `
            <li><a href="javascript:void(0);" onclick="openFolder(${folder.id})">${folder.name}</a></li>
        `).join('');
        }
    }

    function updateFolderSelect(folders) {
        const select = document.getElementById('fileFolderInput');
        if (select) {
            select.innerHTML = '<option value="">Select Folder</option>' +
                folders.map(folder => `<option value="${folder.id}">${folder.name}</option>`).join('');
        }
    }

    function updatePagination(pagination) {
        const showingCount = document.getElementById('showing-count');
        const totalCount = document.getElementById('total-count');

        if (showingCount && totalCount) {
            showingCount.textContent = Math.min(pagination.current_page * pagination.per_page, pagination.total);
            totalCount.textContent = pagination.total;
        }
    }

    // Utility Functions
    function resetFolderForm() {
        document.getElementById('folderIdInput').value = '';
        document.getElementById('folderNameInput').value = '';
        document.getElementById('folderDescInput').value = '';
        document.getElementById('createFolderModalLabel').textContent = 'Create Folder';
        document.getElementById('saveFolderBtn').innerHTML = '<i class="ri-add-line"></i> Create Folder';
    }

    function resetFileForm() {
        document.getElementById('fileIdInput').value = '';
        document.getElementById('fileNameInput').value = '';
        document.getElementById('fileTypeInput').value = '';
        document.getElementById('fileFolderInput').value = '';
        document.getElementById('fileSizeInput').value = '';
        document.getElementById('createFileModalLabel').textContent = 'Create File';
        document.getElementById('saveFileBtn').innerHTML = '<i class="ri-add-line"></i> Create File';
    }

    function searchFiles(term) {
        if (!term) {
            renderFiles(files);
            return;
        }

        const filtered = files.filter(file =>
            file.display_name.toLowerCase().includes(term.toLowerCase()) ||
            file.original_name.toLowerCase().includes(term.toLowerCase())
        );
        renderFiles(filtered);
    }

    function filterFiles(filter) {
        // Update active state
        document.querySelectorAll('.file-manager-menu a').forEach(link => {
            link.classList.remove('active');
        });
        event.target.closest('a').classList.add('active');

        // Load filtered files
        currentFilter = filter;
        loadFiles();
    }

    function getFileColor(extension) {
        const colors = {
            pdf: 'danger',
            doc: 'primary',
            docx: 'primary',
            xls: 'success',
            xlsx: 'success',
            ppt: 'warning',
            pptx: 'warning',
            jpg: 'info',
            jpeg: 'info',
            png: 'info',
            gif: 'info',
            mp4: 'danger',
            avi: 'danger',
            mp3: 'success',
            wav: 'success',
            zip: 'secondary',
            rar: 'secondary',
        };
        return colors[extension] || 'secondary';
    }

    function getFileTypeFromMime(mimeType) {
        if (!mimeType) return 'documents';
        if (mimeType.includes('image')) return 'images';
        if (mimeType.includes('video')) return 'video';
        if (mimeType.includes('audio')) return 'music';
        return 'documents';
    }

    function showNotification(message, type = 'info') {
        const colors = {
            success: '#0ab39c',
            error: '#f06548',
            warning: '#f7b84b',
            info: '#299cdb'
        };

        const notification = document.createElement('div');
        notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${colors[type] || colors.info};
        color: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        animation: slideIn 0.3s ease;
        max-width: 400px;
    `;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Add animation styles
    const style = document.createElement('style');
    style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(400px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(400px); opacity: 0; }
    }
`;
    document.head.appendChild(style);
</script>
