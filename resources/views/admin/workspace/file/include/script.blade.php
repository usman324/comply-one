<script>
    // Configuration
    const WORKSPACE_ID = '{{ $workspaceId }}'; // Default workspace ID
    const USER_ID = '{{ getUser()->id }}'; // Default user ID

    // State
    let currentFolderId = null;
    let currentFolderPath = []; // Track folder hierarchy
    let currentFilter = 'all';
    let currentView = 'grid';
    let folders = [];
    let files = [];
    let allFolders = []; // Store all folders for hierarchy
    let deleteItemId = null;
    let deleteItemType = null;

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        initializeApp();
    });

    function initializeApp() {
        loadAllFolders(); // Load all folders for sidebar hierarchy
        loadFolders();
        loadFiles();
        loadStatistics();
        setupEventListeners();
    }

    // Event Listeners
    function setupEventListeners() {
        // Sidebar Toggle (Mobile)
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                document.getElementById('sidebar').classList.toggle('show');
            });
        }

        // Save Folder
        document.getElementById('saveFolderBtn').addEventListener('click', saveFolder);

        // Save File
        document.getElementById('saveFileBtn').addEventListener('click', saveFile);

        // Confirm Delete
        document.getElementById('confirmDeleteBtn').addEventListener('click', confirmDelete);

        // Search
        document.getElementById('searchInput').addEventListener('input', function(e) {
            searchFiles(e.target.value);
        });

        // Sort
        const sortSelect = document.getElementById('sortSelect');
        if (sortSelect) {
            sortSelect.addEventListener('change', function(e) {
                sortFiles(e.target.value);
            });
        }

        // View Switcher
        const gridViewBtn = document.getElementById('gridViewBtn');
        const listViewBtn = document.getElementById('listViewBtn');

        if (gridViewBtn) gridViewBtn.addEventListener('click', () => switchView('grid'));
        if (listViewBtn) listViewBtn.addEventListener('click', () => switchView('list'));

        // Filter Menu
        document.querySelectorAll('.sidebar-menu-item[data-filter]').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                filterFiles(this.getAttribute('data-filter'));

                // Update active state
                document.querySelectorAll('.sidebar-menu-item').forEach(link => {
                    link.classList.remove('active');
                });
                this.classList.add('active');
            });
        });

        // Modal Reset
        const createFolderModal = document.getElementById('createFolderModal');
        if (createFolderModal) {
            createFolderModal.addEventListener('hidden.bs.modal', function() {
                resetFolderForm();
            });
        }

        const createFileModal = document.getElementById('createFileModal');
        if (createFileModal) {
            createFileModal.addEventListener('hidden.bs.modal', function() {
                resetFileForm();
            });
        }
    }

    // API Functions

    // Load all folders for hierarchy
    async function loadAllFolders() {
        try {
            const response = await fetch(`folders?workspace_id=${WORKSPACE_ID}&all=true`);
            const data = await response.json();

            if (data.success) {
                allFolders = data.data;
                buildFolderHierarchy();
            }
        } catch (error) {
            console.error('Error loading all folders:', error);
        }
    }

    // Load current folder contents
    async function loadFolders() {
        try {
            const response = await fetch(
                `folders?workspace_id=${WORKSPACE_ID}&parent_folder_id=${currentFolderId || ''}`);
            const data = await response.json();

            if (data.success) {
                folders = data.data;
                renderFolders(folders);
                updateFolderSelect(folders);
                updateBreadcrumb();
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
                loadAllFolders(); // Reload hierarchy
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

    // Navigate to folder
    function openFolder(folderId, folderName = '') {
        // Update path
        if (folderId) {
            currentFolderPath.push({
                id: folderId,
                name: folderName
            });
        } else {
            currentFolderPath = [];
        }

        currentFolderId = folderId;
        loadFolders();
        loadFiles();
        updateBreadcrumb();

        // Highlight in sidebar
        highlightFolderInSidebar(folderId);
    }

    // Go back to parent folder
    function goBack() {
        if (currentFolderPath.length > 0) {
            currentFolderPath.pop();

            if (currentFolderPath.length > 0) {
                const parent = currentFolderPath[currentFolderPath.length - 1];
                currentFolderId = parent.id;
            } else {
                currentFolderId = null;
            }

            loadFolders();
            loadFiles();
            updateBreadcrumb();
            highlightFolderInSidebar(currentFolderId);
        }
    }

    // Navigate to specific folder in breadcrumb
    function navigateToFolder(index) {
        if (index === -1) {
            // Go to root
            currentFolderPath = [];
            currentFolderId = null;
        } else {
            // Go to specific folder
            currentFolderPath = currentFolderPath.slice(0, index + 1);
            currentFolderId = currentFolderPath[currentFolderPath.length - 1].id;
        }

        loadFolders();
        loadFiles();
        updateBreadcrumb();
        highlightFolderInSidebar(currentFolderId);
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
                updateItemCount();
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
        const fileInput = document.getElementById('fileInput');
        const file = fileInput?.files[0];

        if (!fileName || !fileType || !folderId) {
            showNotification('Please fill all required fields', 'warning');
            return;
        }

        const formData = new FormData();
        formData.append('workspace_id', WORKSPACE_ID);
        formData.append('folder_id', folderId);
        formData.append('display_name', fileName);
        formData.append('file_type', fileType);

        if (file) {
            formData.append('file', file);
        }

        try {
            const url = fileId ? `files/${fileId}` : `files`;
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
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

    async function deleteFile(fileId) {
        deleteItemId = fileId;
        deleteItemType = 'file';
        document.getElementById('deleteMessage').textContent = 'Are you sure you want to delete this file?';
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
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
            const url = deleteItemType === 'folder' ? `folders/${deleteItemId}` : `files/${deleteItemId}`;

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
                    loadAllFolders();
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
            const response = await fetch(`folders/statistics?workspace_id=${WORKSPACE_ID}`);
            const data = await response.json();

            if (data.success) {
                renderStatistics(data.data);
            }
        } catch (error) {
            console.error('Error loading statistics:', error);
        }
    }

    // Build folder hierarchy in sidebar
    function buildFolderHierarchy() {
        const hierarchyContainer = document.getElementById('folderHierarchy');
        if (!hierarchyContainer) return;

        // Build tree structure
        const rootFolders = allFolders.filter(f => !f.parent_folder_id);

        const html = rootFolders.map(folder => buildFolderTree(folder, 0)).join('');
        hierarchyContainer.innerHTML = html;

        // Add click handlers
        document.querySelectorAll('.hierarchy-folder-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const folderId = parseInt(this.dataset.folderId);
                const folderName = this.dataset.folderName;
                openFolder(folderId, folderName);
            });
        });

        // Add toggle handlers for expand/collapse
        document.querySelectorAll('.folder-toggle').forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const folderId = this.dataset.folderId;
                const subfolders = document.getElementById(`subfolders-${folderId}`);
                const icon = this.querySelector('i');

                if (subfolders) {
                    subfolders.classList.toggle('d-none');
                    icon.classList.toggle('ri-arrow-right-s-line');
                    icon.classList.toggle('ri-arrow-down-s-line');
                }
            });
        });
    }

    function buildFolderTree(folder, level) {
        const children = allFolders.filter(f => f.parent_folder_id === folder.id);
        const hasChildren = children.length > 0;
        const indent = level * 1.25;

        let html = `
        <div class="hierarchy-folder-wrapper">
            <div class="hierarchy-folder-item"
                 data-folder-id="${folder.id}"
                 data-folder-name="${folder.name}"
                 style="padding-left: ${indent}rem;">
                ${hasChildren ? `
                    <span class="folder-toggle" data-folder-id="${folder.id}">
                        <i class="ri-arrow-right-s-line"></i>
                    </span>
                ` : '<span class="folder-spacer"></span>'}
                <i class="ri-folder-line folder-icon"></i>
                <span class="folder-name">${folder.name}</span>
                <span class="folder-count">${folder.file_count || 0}</span>
            </div>
    `;

        if (hasChildren) {
            html += `<div class="subfolders d-none" id="subfolders-${folder.id}">`;
            children.forEach(child => {
                html += buildFolderTree(child, level + 1);
            });
            html += `</div>`;
        }

        html += `</div>`;

        return html;
    }

    function highlightFolderInSidebar(folderId) {
        document.querySelectorAll('.hierarchy-folder-item').forEach(item => {
            item.classList.remove('active');
        });

        if (folderId) {
            const activeItem = document.querySelector(`.hierarchy-folder-item[data-folder-id="${folderId}"]`);
            if (activeItem) {
                activeItem.classList.add('active');

                // Expand parent folders
                let parent = activeItem.closest('.subfolders');
                while (parent) {
                    parent.classList.remove('d-none');
                    const toggle = parent.previousElementSibling?.querySelector('.folder-toggle i');
                    if (toggle) {
                        toggle.classList.remove('ri-arrow-right-s-line');
                        toggle.classList.add('ri-arrow-down-s-line');
                    }
                    parent = parent.parentElement?.closest('.subfolders');
                }
            }
        }
    }

    // Update breadcrumb
    function updateBreadcrumb() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (!breadcrumb) return;

        let html = `
        <a href="javascript:void(0);" class="breadcrumb-item-modern" onclick="navigateToFolder(-1)">
            <i class="ri-home-4-line me-1"></i>
            My Drive
        </a>
    `;

        currentFolderPath.forEach((folder, index) => {
            html += `
            <i class="ri-arrow-right-s-line text-muted"></i>
            <a href="javascript:void(0);" class="breadcrumb-item-modern ${index === currentFolderPath.length - 1 ? 'active' : ''}"
               onclick="navigateToFolder(${index})">
                ${folder.name}
            </a>
        `;
        });

        breadcrumb.innerHTML = html;

        // Show/hide back button
        const backButton = document.getElementById('backButton');
        if (backButton) {
            if (currentFolderPath.length > 0) {
                backButton.style.display = 'inline-flex';
            } else {
                backButton.style.display = 'none';
            }
        }
    }

    // Update item count
    function updateItemCount() {
        const itemCount = document.getElementById('itemCount');
        if (itemCount) {
            const total = folders.length + files.length;
            itemCount.textContent = `${total} item${total !== 1 ? 's' : ''}`;
        }

        const sectionTitle = document.getElementById('sectionTitle');
        if (sectionTitle) {
            if (currentFolderPath.length > 0) {
                sectionTitle.textContent = currentFolderPath[currentFolderPath.length - 1].name;
            } else {
                sectionTitle.textContent = 'My Drive';
            }
        }
    }

    // Render Functions
    function renderFolders(folders) {
        const container = document.getElementById('foldersGrid');
        const section = document.getElementById('foldersSection');

        if (!container || !section) return;

        if (folders.length === 0) {
            section.style.display = 'none';
            return;
        }

        section.style.display = 'block';

        container.innerHTML = folders.map(folder => `
        <div class="file-card fade-in-up" ondblclick="openFolder(${folder.id}, '${folder.name}')">
            <div class="file-card-checkbox">
                <input type="checkbox" value="${folder.id}">
            </div>
            <div class="file-card-icon">
                <i class="ri-folder-2-fill text-warning"></i>
            </div>
            <div class="file-card-name">${folder.name}</div>
            <div class="file-card-meta">
                <span>${folder.file_count || 0} items</span>
                <span>${folder.folder_size || '0 B'}</span>
            </div>
            <div class="file-card-actions">
                <div class="dropdown">
                    <button class="btn btn-sm btn-link" data-bs-toggle="dropdown">
                        <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end context-menu-modern">
                        <li>
                            <a class="context-menu-item" href="javascript:void(0);" onclick="openFolder(${folder.id}, '${folder.name}')">
                                <i class="ri-folder-open-line"></i>
                                Open
                            </a>
                        </li>
                        <li>
                            <a class="context-menu-item" href="javascript:void(0);" onclick="editFolder(${folder.id})">
                                <i class="ri-edit-line"></i>
                                Rename
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="context-menu-item danger" href="javascript:void(0);" onclick="deleteFolder(${folder.id})">
                                <i class="ri-delete-bin-line"></i>
                                Delete
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    `).join('');

        updateItemCount();
    }

    function renderFiles(files) {
        const gridContainer = document.getElementById('filesGrid');
        const listContainer = document.getElementById('filesList');
        const section = document.getElementById('filesSection');
        const emptyState = document.getElementById('emptyState');

        if (!gridContainer || !section) return;

        if (files.length === 0 && folders.length === 0) {
            section.style.display = 'none';
            if (emptyState) emptyState.style.display = 'block';
            return;
        }

        section.style.display = 'block';
        if (emptyState) emptyState.style.display = 'none';

        if (files.length === 0) {
            gridContainer.innerHTML = '<div class="col-12 text-center text-muted py-4">No files in this folder</div>';
            return;
        }

        // Grid View
        gridContainer.innerHTML = files.map(file => `
        <div class="file-card fade-in-up">
            <div class="file-card-checkbox">
                <input type="checkbox" value="${file.id}">
            </div>
            <div class="file-card-icon">
                <i class="${file.file_icon} text-${getFileColor(file.extension)}"></i>
            </div>
            <div class="file-card-name">${file.display_name}</div>
            <div class="file-card-meta">
                <span>${file.file_size}</span>
                <span>${file.created_at}</span>
            </div>
            <div class="file-card-actions">
                <div class="dropdown">
                    <button class="btn btn-sm btn-link" data-bs-toggle="dropdown">
                        <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end context-menu-modern">

                        <li>
                            <a class="context-menu-item" href="/files/${file.id}/download">
                                <i class="ri-download-line"></i>
                                Download
                            </a>
                        </li>
                        <li>
                            <a class="context-menu-item" href="javascript:void(0);" onclick="toggleFileStar(${file.id})">
                                <i class="ri-star-line"></i>
                                ${file.is_starred ? 'Unstar' : 'Star'}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="context-menu-item danger" href="javascript:void(0);" onclick="deleteFile(${file.id})">
                                <i class="ri-delete-bin-line"></i>
                                Delete
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    `).join('');

        updateItemCount();
    }

    function renderStatistics(stats) {
        // Update storage bar
        const usedStorage = document.getElementById('usedStorage');
        const storageBar = document.getElementById('storageBar');
        const storagePercentage = document.getElementById('storagePercentage');

        if (usedStorage) usedStorage.textContent = stats.storage.used;
        if (storageBar) storageBar.style.width = stats.storage.percentage + '%';
        if (storagePercentage) storagePercentage.textContent = stats.storage.percentage + '%';

        // Update starred count
        const starredCount = document.getElementById('starredCount');
        if (starredCount) {
            // Count starred files
            const starred = files.filter(f => f.is_starred).length;
            if (starred > 0) {
                starredCount.textContent = starred;
                starredCount.style.display = 'inline-block';
            } else {
                starredCount.style.display = 'none';
            }
        }
    }

    // View Switching
    function switchView(view) {
        currentView = view;

        const gridView = document.getElementById('filesGrid');
        const listView = document.getElementById('filesList');
        const gridBtn = document.getElementById('gridViewBtn');
        const listBtn = document.getElementById('listViewBtn');

        if (view === 'grid') {
            if (gridView) gridView.style.display = 'grid';
            if (listView) listView.style.display = 'none';
            if (gridBtn) gridBtn.classList.add('active');
            if (listBtn) listBtn.classList.remove('active');
        } else {
            if (gridView) gridView.style.display = 'none';
            if (listView) listView.style.display = 'block';
            if (gridBtn) gridBtn.classList.remove('active');
            if (listBtn) listBtn.classList.add('active');
            renderFilesList();
        }
    }

    function renderFilesList() {
        const tbody = document.getElementById('filesListBody');
        if (!tbody) return;

        if (files.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted">No files</td></tr>';
            return;
        }

        tbody.innerHTML = files.map(file => `
        <tr>
            <td><input type="checkbox" value="${file.id}"></td>
            <td>
                <div class="file-name-cell">
                    <div class="file-icon-wrapper">
                        <i class="${file.file_icon} text-${getFileColor(file.extension)}"></i>
                    </div>
                    <span>${file.display_name}</span>
                </div>
            </td>
            <td>${file.file_size}</td>
            <td>${file.created_at}</td>
            <td>${file.mime_type || '-'}</td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-sm btn-link" data-bs-toggle="dropdown">
                        <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="javascript:void(0);" onclick="viewFile(${file.id})">View</a></li>
                        <li><a class="dropdown-item" href="/files/${file.id}/download">Download</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);" onclick="deleteFile(${file.id})">Delete</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    `).join('');
    }

    // Utility Functions
    function resetFolderForm() {
        document.getElementById('folderIdInput').value = '';
        document.getElementById('folderNameInput').value = '';
        document.getElementById('folderDescInput').value = '';
        document.getElementById('createFolderModalLabel').textContent = 'Create New Folder';
        document.getElementById('saveFolderBtn').innerHTML = '<i class="ri-folder-add-line"></i> Create Folder';
    }

    function resetFileForm() {
        const fileInput = document.getElementById('fileInput');
        if (fileInput) fileInput.value = '';

        const uploadQueue = document.getElementById('uploadQueue');
        if (uploadQueue) uploadQueue.style.display = 'none';
    }

    function searchFiles(term) {
        if (!term) {
            renderFiles(files);
            return;
        }

        const filtered = files.filter(file =>
            file.display_name.toLowerCase().includes(term.toLowerCase())
        );
        renderFiles(filtered);
    }

    function sortFiles(sortBy) {
        let sorted = [...files];

        switch (sortBy) {
            case 'name':
                sorted.sort((a, b) => a.display_name.localeCompare(b.display_name));
                break;
            case 'date':
                sorted.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                break;
            case 'size':
                sorted.sort((a, b) => b.file_size_bytes - a.file_size_bytes);
                break;
        }

        files = sorted;
        renderFiles(files);
    }

    function filterFiles(filter) {
        currentFilter = filter;
        loadFiles();
    }

    function updateFolderSelect(folders) {
        const select = document.getElementById('fileFolderInput');
        if (select) {
            let options = '<option value="">Root Folder</option>';
            options += allFolders.map(folder =>
                `<option value="${folder.id}" ${folder.id === currentFolderId ? 'selected' : ''}>${folder.name}</option>`
            ).join('');
            select.innerHTML = options;
        }
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

    function viewFile(fileId) {
        window.location.href = `/files/${fileId}`;
    }

    function showNotification(message, type = 'info') {
        const colors = {
            success: '#10B981',
            error: '#EF4444',
            warning: '#F59E0B',
            info: '#3B82F6'
        };

        const notification = document.createElement('div');
        notification.className = 'notification fade-in-up';
        notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${colors[type]};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        max-width: 400px;
        font-size: 0.875rem;
    `;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
</script>
