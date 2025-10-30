@extends('layout.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-4 p-1">
                    <!-- Sidebar -->
                    <div class="file-manager-sidebar">
                        <div class="p-3 d-flex flex-column h-100">
                            <div class="mb-3">
                                <h5 class="mb-0 fw-semibold">My Drive</h5>
                            </div>
                            <div class="search-box">
                                <input type="text" class="form-control form-control-sm bg-light border-light" placeholder="Search here..."
                                    id="searchInput">
                                <i class="ri-search-2-line search-icon"></i>
                            </div>
                            <div class="mt-3 mx-n4 px-4 file-menu-sidebar-scroll" data-simplebar>
                                <ul class="list-unstyled file-manager-menu">
                                    <li>
                                        <a data-bs-toggle="collapse" href="#collapseExample" role="button"
                                            aria-expanded="true" aria-controls="collapseExample">
                                            <i class="ri-folder-2-line align-bottom me-2"></i>
                                            <span class="file-list-link">My Drive</span>
                                        </a>
                                        <div class="collapse show" id="collapseExample">
                                            <ul class="sub-menu list-unstyled" id="dynamicFolderMenu">
                                                <!-- Dynamic folders will be loaded here -->
                                            </ul>
                                        </div>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" data-filter="documents">
                                            <i class="ri-file-list-2-line align-bottom me-2"></i>
                                            <span class="file-list-link">Documents</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" data-filter="images">
                                            <i class="ri-image-2-line align-bottom me-2"></i>
                                            <span class="file-list-link">Media</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" data-filter="recent">
                                            <i class="ri-history-line align-bottom me-2"></i>
                                            <span class="file-list-link">Recent</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" data-filter="important">
                                            <i class="ri-star-line align-bottom me-2"></i>
                                            <span class="file-list-link">Important</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" data-filter="deleted">
                                            <i class="ri-delete-bin-line align-bottom me-2"></i>
                                            <span class="file-list-link">Deleted</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="mt-auto">
                                <h6 class="fs-11 text-muted text-uppercase mb-3">Storage Status</h6>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ri-database-2-line fs-17"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3 overflow-hidden">
                                        <div class="progress mb-2 progress-sm">
                                            <div class="progress-bar bg-success" role="progressbar" id="storageBar"
                                                style="width: 25%">
                                            </div>
                                        </div>
                                        <span class="text-muted fs-12 d-block text-truncate">
                                            <b id="usedStorage">47.52</b> GB used of <b>119</b> GB
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="file-manager-content w-100 p-3 py-0">
                        <div class="mx-n3 pt-4 px-4 file-manager-content-scroll" data-simplebar>
                            <div id="folder-list" class="mb-2">
                                <div class="row justify-content-between g-2 mb-3">
                                    <div class="col">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-2 d-block d-lg-none">
                                                <button type="button"
                                                    class="btn btn-soft-success btn-icon btn-sm fs-16 file-menu-btn">
                                                    <i class="ri-menu-2-fill align-bottom"></i>
                                                </button>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fs-16 mb-0">Folders</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="d-flex gap-2 align-items-start">
                                            <select class="form-control form-control-sm" data-choices data-choices-search-false
                                                id="file-type">
                                                <option value="all">All</option>
                                                <option value="documents">Documents</option>
                                                <option value="images">Images</option>
                                                <option value="video">Video</option>
                                                <option value="music">Music</option>
                                            </select>
                                            <button class="btn btn-primary btn-sm w-sm create-folder-modal flex-shrink-0"
                                                data-bs-toggle="modal" data-bs-target="#createFolderModal">
                                                <i class="ri-add-line align-bottom me-1"></i> Create Folders
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Folders Grid -->
                                <div class="row" id="folderlist-data">
                                    <!-- Dynamic folders will be loaded here -->
                                </div>
                            </div>

                            <!-- Files Section -->
                            <div>
                                <div class="d-flex align-items-center mb-3">
                                    <h5 class="flex-grow-1 fs-16 mb-0">Recent Files</h5>
                                    <div class="flex-shrink-0">
                                        <button class="btn btn-primary btn-sm createFile-modal" data-bs-toggle="modal"
                                            data-bs-target="#createFileModal">
                                            <i class="ri-add-line align-bottom me-1"></i> Create File
                                        </button>
                                    </div>
                                </div>
                                <div class="">
                                    <table class="table align-middle table-nowrap mb-0">
                                        <thead class="table-active">
                                            <tr>
                                                <th scope="col">Name</th>
                                                <th scope="col">Folder</th>
                                                <th scope="col">File Size</th>
                                                <th scope="col">Recent Date</th>
                                                <th scope="col" class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="file-list">
                                            <!-- Dynamic files will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="align-items-center mt-4 row g-3 text-center text-sm-start">
                                    <div class="col-sm">
                                        <div class="text-muted">
                                            Showing <span class="fw-semibold" id="showing-count">0</span> of
                                            <span class="fw-semibold" id="total-count">0</span> Results
                                        </div>
                                    </div>
                                    <div class="col-sm-auto">
                                        <ul
                                            class="pagination pagination-separated pagination-sm justify-content-center justify-content-sm-start mb-0">
                                            <li class="page-item disabled">
                                                <a href="#" class="page-link">←</a>
                                            </li>
                                            <li class="page-item active">
                                                <a href="#" class="page-link">1</a>
                                            </li>
                                            <li class="page-item">
                                                <a href="#" class="page-link">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a href="#" class="page-link">3</a>
                                            </li>
                                            <li class="page-item">
                                                <a href="#" class="page-link">→</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Sidebar -->
                    <div class="file-manager-detail-content p-3 py-0">
                        <div class="mx-n3 pt-3 px-3 file-detail-content-scroll" data-simplebar>
                            <!-- Overview -->
                            <div id="folder-overview">
                                <div class="d-flex align-items-center pb-3 border-bottom border-bottom-dashed">
                                    <h5 class="flex-grow-1 fw-semibold mb-0">Overview</h5>
                                    <div>
                                        <button type="button"
                                            class="btn btn-soft-danger btn-icon btn-sm fs-16 close-btn-overview">
                                            <i class="ri-close-fill align-bottom"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <ul class="list-unstyled vstack gap-4" id="overview-stats">
                                        <!-- Dynamic statistics will be loaded here -->
                                    </ul>
                                </div>
                            </div>

                            <!-- File Preview -->
                            <div id="file-overview" class="h-100" style="display:none;">
                                <div class="d-flex h-100 flex-column">
                                    <div
                                        class="d-flex align-items-center pb-3 border-bottom border-bottom-dashed mb-3 gap-2">
                                        <h5 class="flex-grow-1 fw-semibold mb-0">File Preview</h5>
                                        <div>
                                            <button type="button"
                                                class="btn btn-ghost-primary btn-icon btn-sm fs-16 favourite-btn">
                                                <i class="ri-star-fill align-bottom"></i>
                                            </button>
                                            <button type="button"
                                                class="btn btn-soft-danger btn-icon btn-sm fs-16 close-btn-file-overview">
                                                <i class="ri-close-fill align-bottom"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="pb-3 border-bottom border-bottom-dashed mb-3">
                                        <div
                                            class="file-details-box bg-light p-3 text-center rounded-3 border border-light mb-3">
                                            <div class="display-4 file-icon" id="preview-file-icon">
                                                <i class="ri-file-text-fill text-secondary"></i>
                                            </div>
                                        </div>
                                        <button type="button"
                                            class="btn btn-icon btn-sm btn-ghost-success float-end fs-16">
                                            <i class="ri-share-forward-line"></i>
                                        </button>
                                        <h5 class="fs-16 mb-1" id="preview-file-name">-</h5>
                                        <p class="text-muted mb-0 fs-12">
                                            <span id="preview-file-size">-</span>,
                                            <span id="preview-create-date">-</span>
                                        </p>
                                    </div>

                                    <div>
                                        <h5 class="fs-12 text-uppercase text-muted mb-3">File Description :</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless table-nowrap table-sm">
                                                <tbody id="file-description-table">
                                                    <!-- Dynamic content -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="mt-auto border-top border-top-dashed py-3">
                                        <div class="hstack gap-2">
                                            <button type="button" class="btn btn-soft-primary w-100">
                                                <i class="ri-download-2-line align-bottom me-1"></i> Download
                                            </button>
                                            <button type="button" class="btn btn-soft-danger w-100"
                                                id="delete-file-preview-btn">
                                                <i class="ri-close-fill align-bottom me-1"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
