<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ url('/') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ gs()->getLogo() }}" alt="" height="{{ gs()->logo_height ?? '60px' }}">
            </span>
            <span class="logo-lg">
                <img src="{{ gs()->getLogo() }}" alt="" height="{{ gs()->logo_height ?? '60px' }}">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ url('/') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ gs()->getLogo() }}" alt="" height="{{ gs()->logo_height ?? '60px' }}">
            </span>
            <span class="logo-lg">
                <img src="{{ gs()->getLogo() }}" alt="" height="{{ gs()->logo_height ?? '60px' }}">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ url('/') }}">
                        <i class="ri-honour-line"></i> <span data-key="t-dashboards">Dashboard</span>
                    </a>
                </li>
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                @can('list_workspace')
                    @if (getUser()->hasRole('admin'))
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ url('/workspaces') }}">
                                <i class="ri-user-search-fill"></i> <span data-key="t-users">Workspace</span>
                            </a>
                        </li>
                    @endif
                @endcanany
                @can('list_customer')
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ url('/file-managers') }}">
                                <i class="ri-file-3-fill"></i> <span data-key="t-users">File Managers</span>
                            </a>
                        </li>
                @endcanany
                @can('list_customer')
                    @if (getUser()->hasRole('workspace'))
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ url('/customers') }}">
                                <i class="ri-user-search-fill"></i> <span data-key="t-users">Vendors</span>
                            </a>
                        </li>
                    @endif
                @endcanany
                @canany(['list_user', 'list_role'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarUsers" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarUsers">
                            <i class="ri-user-settings-fill"></i>

                            <span data-key="t-users">Users Management</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarUsers">
                            <ul class="nav nav-sm flex-column">
                                @can('list_user')
                                    <li class="nav-item">
                                        <a href="{{ url('users') }}" class="nav-link" data-key="t-crm"> Users </a>
                                    </li>
                                @endcan
                                @can('list_role')
                                    <li class="nav-item">
                                        <a href="{{ url('roles') }}" class="nav-link" data-key="t-crm"> Roles </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                    <!-- Questionnaires Menu -->
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarQuestionnaires" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarQuestionnaires">
                            <i class="ri-questionnaire-line"></i>
                            <span data-key="t-questionnaires">Questionnaires</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarQuestionnaires">
                            <ul class="nav nav-sm flex-column">
                                @can('view_questionnaire')
                                    <li class="nav-item">
                                        <a href="{{ url('questionnaires') }}" class="nav-link"
                                            data-key="t-questionnaires-list">
                                            <i class="ri-list-check"></i> All Questionnaires
                                        </a>
                                    </li>
                                @endcan

                                @can('add_questionnaire')
                                    <li class="nav-item">
                                        <a href="{{ url('questionnaires/create') }}" class="nav-link"
                                            data-key="t-questionnaires-create">
                                            <i class="ri-add-circle-line"></i> Create Questionnaire
                                        </a>
                                    </li>
                                @endcan
                                @can('list_section')
                                    <li class="nav-item">
                                        <a href="{{ url('sections') }}" class="nav-link" data-key="t-questionnaires-create">
                                            <i class="ri-list-check"></i> Sections
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany
                @if (getUser()->hasRole('admin'))
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarSetting" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarSetting">
                            <i class="ri-dashboard-2-line"></i> <span data-key="t-users">Settings</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarSetting">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ url('general-settings') }}" class="nav-link" data-key="t-crm">
                                        General
                                        Setting
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
