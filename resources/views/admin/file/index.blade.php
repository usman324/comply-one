@extends('layout.master')

@section('css')
    <style>
        /* VueFileManager Inspired Design */
        :root {
            --primary-color: #3B82F6;
            --secondary-color: #10B981;
            --danger-color: #EF4444;
            --warning-color: #F59E0B;
            --dark-bg: #1F2937;
            --light-bg: #F9FAFB;
            --border-color: #E5E7EB;
            --text-primary: #111827;
            --text-secondary: #6B7280;
            --sidebar-width: 260px;
        }

        body {
            background: var(--light-bg);
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        /* Top Navigation Bar */
        .file-manager-navbar {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .search-bar-modern {
            max-width: 600px;
            position: relative;
        }

        .search-bar-modern input {
            border-radius: 12px;
            border: 1px solid var(--border-color);
            padding: 0.75rem 1rem 0.75rem 3rem;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .search-bar-modern input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-bar-modern .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }

        /* Main Container */
        .file-manager-container {
            display: flex;
            height: calc(80vh - 80px);
            background: var(--light-bg);
        }

        /* Sidebar */
        .file-manager-sidebar-modern {
            width: var(--sidebar-width);
            background: white;
            border-right: 1px solid var(--border-color);
            padding: 1.5rem 1rem;
            overflow-y: auto;
        }

        .sidebar-section {
            margin-bottom: 2rem;
        }

        .sidebar-section-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-secondary);
            letter-spacing: 0.05em;
            margin-bottom: 0.75rem;
            padding: 0 0.75rem;
        }

        .sidebar-menu-item {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            border-radius: 10px;
            margin-bottom: 0.25rem;
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
        }

        .sidebar-menu-item:hover {
            background: var(--light-bg);
            color: var(--primary-color);
        }

        .sidebar-menu-item.active {
            background: linear-gradient(135deg, var(--primary-color), #405189);
            color: white;
        }

        .sidebar-menu-item i {
            font-size: 1.25rem;
            margin-right: 0.75rem;
            width: 24px;
        }

        .sidebar-menu-item .badge {
            margin-left: auto;
        }

        /* Storage Progress */
        .storage-card {
            background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
            border-radius: 12px;
            padding: 1.25rem;
            color: white;
            margin-top: 1.5rem;
        }

        .storage-progress-modern {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            height: 8px;
            overflow: hidden;
            margin: 1rem 0;
        }

        .storage-progress-modern .progress-bar {
            background: white;
            height: 100%;
            border-radius: 10px;
            transition: width 0.3s;
        }

        /* Main Content Area */
        .file-manager-content-modern {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        /* Breadcrumb */
        .breadcrumb-modern {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .breadcrumb-item-modern {
            display: flex;
            align-items: center;
            color: var(--text-secondary);
            font-size: 0.875rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb-item-modern:hover {
            color: var(--primary-color);
        }

        .breadcrumb-item-modern.active {
            color: var(--text-primary);
            font-weight: 600;
        }

        /* Action Bar */
        .action-bar {
            background: white;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .view-switcher {
            display: flex;
            gap: 0.5rem;
        }

        .view-btn {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border-color);
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .view-btn.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .view-btn:hover:not(.active) {
            background: var(--light-bg);
        }

        /* Files Grid View */
        .files-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .file-card {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            border: 1px solid var(--border-color);
            transition: all 0.3s;
            cursor: pointer;
            position: relative;
        }

        .file-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-color);
        }

        .file-card-checkbox {
            position: absolute;
            top: 1rem;
            left: 1rem;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .file-card:hover .file-card-checkbox {
            opacity: 1;
        }

        .file-card-icon {
            width: 100%;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--light-bg);
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .file-card-icon i {
            font-size: 3rem;
        }

        .file-card-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .file-card-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .file-card-actions {
            position: absolute;
            top: 1rem;
            right: 1rem;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .file-card:hover .file-card-actions {
            opacity: 1;
        }

        /* List View */
        .files-list-view {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 1.5rem;
        }

        .files-table-modern {
            width: 100%;
            border-collapse: collapse;
        }

        .files-table-modern thead {
            background: var(--light-bg);
            border-bottom: 1px solid var(--border-color);
        }

        .files-table-modern th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-secondary);
            letter-spacing: 0.05em;
        }

        .files-table-modern td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .files-table-modern tr:hover {
            background: var(--light-bg);
        }

        .file-name-cell {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .file-icon-wrapper {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--light-bg);
            border-radius: 8px;
        }

        /* Upload Zone */
        .upload-zone {
            background: white;
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 3rem;
            text-align: center;
            margin-bottom: 2rem;
            transition: all 0.3s;
        }

        .upload-zone:hover,
        .upload-zone.drag-over {
            border-color: var(--primary-color);
            background: rgba(59, 130, 246, 0.05);
        }

        .upload-zone i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        /* Buttons */
        .btn-modern {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, var(--primary-color), #405189);
            color: white;
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
        }

        .btn-primary btn-sm {
            background: var(--light-bg);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .btn-primary btn-sm:hover {
            background: white;
        }

        /* Context Menu */
        .context-menu-modern {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            padding: 0.5rem;
            min-width: 200px;
        }

        .context-menu-item {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: background 0.2s;
        }

        .context-menu-item:hover {
            background: var(--light-bg);
        }

        .context-menu-item.danger {
            color: var(--danger-color);
        }

        /* Modals */
        .modal-modern .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .modal-modern .modal-header {
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem 2rem;
        }

        .modal-modern .modal-body {
            padding: 2rem;
        }

        .modal-modern .modal-footer {
            border-top: 1px solid var(--border-color);
            padding: 1.5rem 2rem;
        }

        /* Form Elements */
        .form-control-modern {
            border-radius: 10px;
            border: 1px solid var(--border-color);
            padding: 0.75rem 1rem;
            transition: all 0.2s;
        }

        .form-control-modern:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--text-secondary);
            opacity: 0.5;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.25rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.3s ease-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .file-manager-sidebar-modern {
                position: fixed;
                left: -260px;
                top: 0;
                height: 80vh;
                z-index: 1000;
                transition: left 0.3s;
            }

            .file-manager-sidebar-modern.show {
                left: 0;
            }

            .files-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 1rem;
            }

            .action-bar {
                flex-direction: column;
                gap: 1rem;
            }
        }

        /* Folder Hierarchy */
        .folder-hierarchy {
            max-height: 400px;
            overflow-y: auto;
        }

        .hierarchy-folder-wrapper {
            margin-bottom: 2px;
        }

        .hierarchy-folder-item {
            display: flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.875rem;
            position: relative;
        }

        .hierarchy-folder-item:hover {
            background: var(--light-bg);
        }

        .hierarchy-folder-item.active {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
            font-weight: 600;
        }

        .hierarchy-folder-item .folder-toggle {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.25rem;
            cursor: pointer;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .hierarchy-folder-item .folder-toggle:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        .hierarchy-folder-item .folder-toggle i {
            font-size: 1rem;
            color: var(--text-secondary);
        }

        .hierarchy-folder-item .folder-spacer {
            width: 20px;
            margin-right: 0.25rem;
        }

        .hierarchy-folder-item .folder-icon {
            margin-right: 0.5rem;
            color: #F59E0B;
            font-size: 1.1rem;
        }

        .hierarchy-folder-item .folder-name {
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .hierarchy-folder-item .folder-count {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-left: auto;
            padding-left: 0.5rem;
        }

        .subfolders {
            margin-left: 0;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }
    </style>
@stop
@section('content')
    <div class="container-fluid">
        <div class="">

            <div class="file-manager-navbar">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3 flex-grow-1">
                        <button class="btn btn-link d-md-none" id="sidebarToggle">
                            <i class="ri-menu-line fs-4"></i>
                        </button>

                        <h4 class="mb-0 fw-bold">File Manager</h4>

                        <div class="search-bar-modern flex-grow-1">
                            <i class="ri-search-line search-icon"></i>
                            <input type="text" class="form-control" placeholder="Search files and folders..."
                                id="searchInput">
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                            <i class="ri-folder-add-line"></i>
                            New Folder
                        </button>
                       
                        <div class="dropdown">
                            <button class="btn btn-link" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill fs-4"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                {{-- <li><a class="dropdown-item" href="/file-manager/statistics"><i
                                            class="ri-bar-chart-line me-2"></i> Statistics</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ri-settings-3-line me-2"></i>
                                        Settings</a></li> --}}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Container -->
            <div class="file-manager-container">
                <!-- Sidebar -->
                <div class="file-manager-sidebar-modern" id="sidebar">
                    <div class="sidebar-section">
                        <div class="sidebar-section-title">Quick Access</div>
                        <a href="javascript:void(0);" class="sidebar-menu-item active" onclick="navigateToFolder(-1)"
                            data-filter="all">
                            <i class="ri-home-5-line"></i>
                            <span>My Drive</span>
                        </a>
                        <a href="#" class="sidebar-menu-item" data-filter="recent">
                            <i class="ri-time-line"></i>
                            <span>Recent</span>
                        </a>
                        <a href="#" class="sidebar-menu-item" data-filter="starred">
                            <i class="ri-star-line"></i>
                            <span>Starred</span>
                            <span class="badge bg-warning rounded-pill" id="starredCount" style="display: none;">0</span>
                        </a>
                        <a href="#" class="sidebar-menu-item" data-filter="shared">
                            <i class="ri-group-line"></i>
                            <span>Shared</span>
                        </a>
                    </div>

                    <!-- Folder Hierarchy -->
                    <div class="sidebar-section">
                        <div class="sidebar-section-title">My Drive</div>
                        <div id="folderHierarchy" class="folder-hierarchy">
                            <!-- Dynamic folder hierarchy -->
                        </div>
                    </div>

                    <div class="sidebar-section">
                        <div class="sidebar-section-title">File Types</div>
                        <a href="#" class="sidebar-menu-item" data-filter="documents">
                            <i class="ri-file-text-line"></i>
                            <span>Documents</span>
                        </a>
                    </div>

                    <div class="sidebar-section">
                        <div class="sidebar-section-title">More</div>
                        <a href="#" class="sidebar-menu-item" data-filter="deleted">
                            <i class="ri-delete-bin-line"></i>
                            <span>Trash</span>
                        </a>
                    </div>

                    <!-- Storage Card -->
                </div>

                <!-- Main Content -->
                <div class="file-manager-content-modern">
                    <!-- Breadcrumb -->
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <button class="btn btn-primary btn-sm" id="backButton" onclick="goBack()" style="display: none;">
                            <i class="ri-arrow-left-line"></i>
                        </button>
                        <div class="breadcrumb-modern flex-grow-1" id="breadcrumb">
                            <a href="javascript:void(0);" class="breadcrumb-item-modern" onclick="navigateToFolder(-1)">
                                <i class="ri-home-4-line me-1"></i>
                                My Drive
                            </a>
                            <i class="ri-arrow-right-s-line text-muted"></i>
                            <span class="breadcrumb-item-modern active">All Files</span>
                        </div>
                    </div>

                    <!-- Action Bar -->
                    <div class="action-bar">
                        <div class="d-flex align-items-center gap-3">
                            <h5 class="mb-0" id="sectionTitle">All Files</h5>
                            <span class="badge bg-light text-dark" id="itemCount">0 items</span>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <select class="form-select form-select-sm" id="sortSelect" style="width: auto;">
                                <option value="name">Name</option>
                                <option value="date">Date Modified</option>
                                <option value="size">Size</option>
                                <option value="type">Type</option>
                            </select>

                            <div class="view-switcher">
                                <button class="view-btn active" data-view="grid" id="gridViewBtn">
                                    <i class="ri-grid-line"></i>
                                </button>
                                <button class="view-btn" data-view="list" id="listViewBtn">
                                    <i class="ri-list-check"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Folders Section -->
                    <div id="foldersSection">
                        <h6 class="text-muted text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                            Folders
                        </h6>
                        <div class="files-grid" id="foldersGrid">
                            <!-- Dynamic folders -->
                        </div>
                    </div>

                    <!-- Files Section -->
                    <div id="filesSection" class="mt-4">
                        <h6 class="text-muted text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                            Files
                        </h6>

                        <!-- Grid View -->
                        <div class="files-grid" id="filesGrid" style="display: grid;">
                            <!-- Dynamic files -->
                        </div>

                        <!-- List View -->
                        <div class="files-list-view" id="filesList" style="display: none;">
                            <table class="files-table-modern">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Name</th>
                                        <th>Size</th>
                                        <th>Modified</th>
                                        <th>Type</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="filesListBody">
                                    <!-- Dynamic files -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div class="empty-state" id="emptyState" style="display: none;">
                        <i class="ri-folder-open-line"></i>
                        <h3>No files yet</h3>
                        <p>Upload your first file to get started</p>
                        <button class="btn-primary btn-sm btn-modern" data-bs-toggle="modal"
                            data-bs-target="#uploadFileModal">
                            <i class="ri-upload-cloud-line"></i>
                            Upload Files
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>


    @include('admin.file.include.modals')
@endsection

@section('script')
    @include('admin.file.include.script')
    {{-- <script src="{{ asset('js/file-manager.js') }}"></script> --}}
@endsection
