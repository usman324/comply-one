@extends('layout.master')

@section('css')
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" /> --}}
    <style>
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-left {
            flex: 1;
            min-width: 300px;
        }

        .company-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
            width: fit-content;
        }

        .company-badge-icon {
            font-size: 14px;
        }

        .header-title {
            margin-bottom: 8px;
        }

        .header-title h1 {
            font-size: 32px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.5px;
        }

        .header-subtitle {
            font-size: 14px;
            color: var(--text-secondary);
            font-weight: 400;
        }

        .header-right {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .btn-primary {
            background: var(--primary-light);
            color: white;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
        }

        .btn-primary:hover {
            background: var(--primary);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: white;
            color: var(--text-primary);
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .btn-secondary:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%);
        }

        .stat-card:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transform: translateY(-4px);
            border-color: var(--primary-light);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .stat-label {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-icon {
            font-size: 24px;
            opacity: 0.8;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 12px;
            letter-spacing: -1px;
        }

        .stat-footer {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .stat-item {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        /* Dashboard Cards Grid */
        .dashboard-section {
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title-icon {
            font-size: 24px;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 24px;
        }

        .card {
            background: white;
            border-radius: 12px;
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .card:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border-color: var(--primary-light);
            transform: translateY(-2px);
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.02) 0%, rgba(59, 130, 246, 0.02) 100%);
        }

        .card-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-title-icon {
            font-size: 18px;
        }

        .card-action {
            color: var(--primary-light);
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s ease;
        }

        .card-action:hover {
            color: var(--primary);
            gap: 8px;
        }

        .card-body {
            padding: 24px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .item-row {
            padding: 16px;
            background: var(--gray-50);
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .item-row:hover {
            background: #f0f9ff;
            border-color: var(--accent);
        }

        .item-left {
            flex: 1;
        }

        .item-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .item-meta {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .item-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-approved {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-overdue {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-warning {
            background: #fed7aa;
            color: #7c2d12;
        }

        .progress-container {
            margin-top: 8px;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 6px;
            color: var(--text-secondary);
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: var(--gray-200);
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%);
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        /* Full Width Card */
        .full-width-grid {
            grid-column: 1 / -1;
        }

        .reporting-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 12px;
        }

        /* Metrics Row */
        .metrics-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-top: 12px;
        }

        .metric {
            padding: 12px;
            background: var(--gray-50);
            border-radius: 8px;
            border-left: 3px solid var(--primary-light);
        }

        .metric-label {
            font-size: 11px;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
        }

        .metric-value {
            font-size: 20px;
            font-weight: 800;
            color: var(--text-primary);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .container {
                padding: 32px 24px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 24px 16px;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-right {
                width: 100%;
                justify-content: flex-start;
            }

            .btn {
                flex: 1;
                justify-content: center;
                min-width: 120px;
            }

            .header-title h1 {
                font-size: 24px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .cards-grid {
                grid-template-columns: 1fr;
            }

            .reporting-grid {
                grid-template-columns: 1fr;
            }

            .header {
                margin-bottom: 32px;
            }
        }

        /* Utility */
        .mt-32 {
            margin-top: 32px;
        }

        .mt-24 {
            margin-top: 24px;
        }

        .text-muted {
            color: var(--text-secondary);
        }

        .font-semibold {
            font-weight: 600;
        }

        .font-bold {
            font-weight: 700;
        }
    </style>
@stop
@section('content')
    <div class="container-fluid">
        <div class="stats-grid">
            <!-- Policies Card -->
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Policies</span>
                    <span class="stat-icon">📋</span>
                </div>
                <div class="stat-value">15</div>
                <div class="stat-footer">
                    <div class="stat-item"><span class="badge badge-success">4 Upcoming</span></div>
                    <div class="stat-item"><span class="badge badge-danger">1 Overdue</span></div>
                </div>
                <div class="progress-container">
                    <div class="progress-label">
                        <span>Renewal Progress</span>
                        <span>73%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 73%;"></div>
                    </div>
                </div>
            </div>

            <!-- Training Card -->
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Training Programs</span>
                    <span class="stat-icon">🎓</span>
                </div>
                <div class="stat-value">13</div>
                <div class="stat-footer">
                    <div class="stat-item"><span class="badge badge-warning">13 Pending</span></div>
                    <div class="stat-item"><span class="badge badge-success">4 Complete</span></div>
                </div>
                <div class="progress-container">
                    <div class="progress-label">
                        <span>Completion Rate</span>
                        <span>31%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 31%;"></div>
                    </div>
                </div>
            </div>

            <!-- Vendors Card -->
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Vendors</span>
                    <span class="stat-icon">🤝</span>
                </div>
                <div class="stat-value">36</div>
                <div class="stat-footer">
                    <div class="stat-item"><span class="badge badge-success">36 Active</span></div>
                    <div class="stat-item"><span class="badge badge-warning">1 Pending</span></div>
                </div>
                <div class="progress-container">
                    <div class="progress-label">
                        <span>Approval Status</span>
                        <span>97%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 97%;"></div>
                    </div>
                </div>
            </div>

            <!-- Tasks Card -->
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Tasks Pending</span>
                    <span class="stat-icon">✓</span>
                </div>
                <div class="stat-value">8</div>
                <div class="stat-footer">
                    <div class="stat-item"><span class="badge badge-info">8 Tasks</span></div>
                    <div class="stat-item"><span class="badge badge-success">Under Review</span></div>
                </div>
                <div class="progress-container">
                    <div class="progress-label">
                        <span>Review Progress</span>
                        <span>35%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 35%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Compliance Operations Section -->
        <div class="dashboard-section">
            <div class="section-title">
                <span class="section-title-icon">📊</span>
                Compliance Operations
            </div>
            <div class="cards-grid">
                <!-- Policies Management -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span class="card-title-icon">📋</span>
                            Policy Management
                        </h3>
                        <a href="#" class="card-action">View All →</a>
                    </div>
                    <div class="card-body">
                        <div class="item-row">
                            <div class="item-left">
                                <div class="item-name">Data Protection Policy</div>
                                <div class="item-meta">Active • Updated 2 days ago</div>
                            </div>
                            <div class="item-right">
                                <span class="status-badge status-active">Active</span>
                            </div>
                        </div>
                        <div class="item-row">
                            <div class="item-left">
                                <div class="item-name">KYC Procedures</div>
                                <div class="item-meta">Due in 5 days • Pending Review</div>
                            </div>
                            <div class="item-right">
                                <span class="status-badge status-pending">Pending</span>
                            </div>
                        </div>
                        <div class="item-row">
                            <div class="item-left">
                                <div class="item-name">AML Guidelines</div>
                                <div class="item-meta">Renewal • Q4 2025</div>
                            </div>
                            <div class="item-right">
                                <span class="status-badge status-approved">Approved</span>
                            </div>
                        </div>
                        <div class="metrics-row">
                            <div class="metric">
                                <div class="metric-label">Active Policies</div>
                                <div class="metric-value">15</div>
                            </div>
                            <div class="metric">
                                <div class="metric-label">Upcoming Renewal</div>
                                <div class="metric-value">4</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Training Programs -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span class="card-title-icon">🎓</span>
                            Training Programs
                        </h3>
                        <a href="#" class="card-action">View All →</a>
                    </div>
                    <div class="card-body">
                        <div class="item-row">
                            <div class="item-left">
                                <div class="item-name">Compliance 101</div>
                                <div class="item-meta">12 pending • In Progress</div>
                            </div>
                            <div class="item-right">
                                <span class="status-badge status-warning">In Progress</span>
                            </div>
                        </div>
                        <div class="item-row">
                            <div class="item-left">
                                <div class="item-name">AML/KYC Certification</div>
                                <div class="item-meta">8 completed • Done</div>
                            </div>
                            <div class="item-right">
                                <span class="status-badge status-approved">Complete</span>
                            </div>
                        </div>
                        <div class="item-row">
                            <div class="item-left">
                                <div class="item-name">Data Security Workshop</div>
                                <div class="item-meta">Scheduled • Oct 30, 2025</div>
                            </div>
                            <div class="item-right">
                                <span class="status-badge status-active">Scheduled</span>
                            </div>
                        </div>
                        <div class="metrics-row">
                            <div class="metric">
                                <div class="metric-label">Total Programs</div>
                                <div class="metric-value">13</div>
                            </div>
                            <div class="metric">
                                <div class="metric-label">Completion Rate</div>
                                <div class="metric-value">31%</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vendor Management -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span class="card-title-icon">🤝</span>
                            Vendor Management
                        </h3>
                        <a href="#" class="card-action">View All →</a>
                    </div>
                    <div class="card-body">
                        <div class="item-row">
                            <div class="item-left">
                                <div class="item-name">TechVendor Inc</div>
                                <div class="item-meta">Assessment • Due in 3 days</div>
                            </div>
                            <div class="item-right">
                                <span class="status-badge status-pending">Pending</span>
                            </div>
                        </div>
                        <div class="item-row">
                            <div class="item-left">
                                <div class="item-name">CloudServices Ltd</div>
                                <div class="item-meta">Active • Renewal Q1 2025</div>
                            </div>
                            <div class="item-right">
                                <span class="status-badge status-active">Active</span>
                            </div>
                        </div>
                        <div class="item-row">
                            <div class="item-left">
                                <div class="item-name">DataCore Systems</div>
                                <div class="item-meta">Approved • 2 months ago</div>
                            </div>
                            <div class="item-right">
                                <span class="status-badge status-approved">Approved</span>
                            </div>
                        </div>
                        <div class="metrics-row">
                            <div class="metric">
                                <div class="metric-label">Total Vendors</div>
                                <div class="metric-value">36</div>
                            </div>
                            <div class="metric">
                                <div class="metric-label">Active Vendors</div>
                                <div class="metric-value">36</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Risk Assessment -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span class="card-title-icon">⚠️</span>
                            Risk Assessment
                        </h3>
                        <a href="#" class="card-action">View All →</a>
                    </div>
                    <div class="card-body">
                        <div class="item-row">
                            <div class="item-left">
                                <div class="item-name">Operational Risk</div>
                                <div class="item-meta">Q4 Assessment • Medium</div>
                            </div>
                            <div class="item-right">
                                <span class="status-badge status-warning">Medium Risk</span>
                            </div>
                        </div>
                        <div class="item-row">
                            <div class="item-left">
                                <div class="item-name">Compliance Risk</div>
                                <div class="item-meta">Ongoing Monitoring • Low</div>
                            </div>
                            <div class="item-right">
                                <span class="status-badge status-active">Low Risk</span>
                            </div>
                        </div>
                        <div class="item-row">
                            <div class="item-left">
                                <div class="item-name">Third-Party Risk</div>
                                <div class="item-meta">Annual Review • Due Soon</div>
                            </div>
                            <div class="item-right">
                                <span class="status-badge status-pending">Needs Review</span>
                            </div>
                        </div>
                        <div class="metrics-row">
                            <div class="metric">
                                <div class="metric-label">Total Assessments</div>
                                <div class="metric-value">3</div>
                            </div>
                            <div class="metric">
                                <div class="metric-label">Overdue Items</div>
                                <div class="metric-value">1</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Compliance Builder -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span class="card-title-icon">✅</span>
                            Compliance Builder
                        </h3>
                        <a href="#" class="card-action">View All →</a>
                    </div>
                    <div class="card-body">
                        <div class="item-row">
                            <div class="item-left">
                                <div class="item-name">Policy Templates</div>
                                <div class="item-meta">12 available templates</div>
                            </div>
                            <div class="item-right">
                                <span class="status-badge status-approved">Ready</span>
                            </div>
                        </div>
                        <div class="item-row">
                            <div class="item-left">
                                <div class="item-name">Workflow Builder</div>
                                <div class="item-meta">5 active workflows</div>
                            </div>
                            <div class="item-right">
                                <span class="status-badge status-active">Active</span>
                            </div>
                        </div>
                        <div class="item-row">
                            <div class="item-left">
                                <div class="item-name">Document Generator</div>
                                <div class="item-meta">Auto-generate documents</div>
                            </div>
                            <div class="item-right">
                                <span class="status-badge status-approved">Enabled</span>
                            </div>
                        </div>
                        <div class="metrics-row">
                            <div class="metric">
                                <div class="metric-label">Templates Created</div>
                                <div class="metric-value">12</div>
                            </div>
                            <div class="metric">
                                <div class="metric-label">Workflows Active</div>
                                <div class="metric-value">5</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Entity Management -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span class="card-title-icon">👥</span>
                            Entity Management
                        </h3>
                        <a href="#" class="card-action">View All →</a>
                    </div>
                    <div class="card-body">
                        <div class="item-row">
                            <div class="item-left">
                                <div class="item-name">Legal Entities</div>
                                <div class="item-meta">8 registered entities</div>
                            </div>
                            <div class="item-right">
                                <span class="status-badge status-approved">Verified</span>
                            </div>
                        </div>
                        <div class="item-row">
                            <div class="item-left">
                                <div class="item-name">User Access</div>
                                <div class="item-meta">42 active users</div>
                            </div>
                            <div class="item-right">
                                <span class="status-badge status-active">Managed</span>
                            </div>
                        </div>
                        <div class="item-row">
                            <div class="item-left">
                                <div class="item-name">Permissions</div>
                                <div class="item-meta">Role-based access control</div>
                            </div>
                            <div class="item-right">
                                <span class="status-badge status-approved">Configured</span>
                            </div>
                        </div>
                        <div class="metrics-row">
                            <div class="metric">
                                <div class="metric-label">Total Entities</div>
                                <div class="metric-value">8</div>
                            </div>
                            <div class="metric">
                                <div class="metric-label">Active Users</div>
                                <div class="metric-value">42</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reporting Section -->
        <div class="dashboard-section">
            <div class="section-title">
                <span class="section-title-icon">📈</span>
                Reporting & Submissions
            </div>
            <div class="cards-grid full-width-grid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span class="card-title-icon">📊</span>
                            Recent Reports
                        </h3>
                        <a href="#" class="card-action">View All →</a>
                    </div>
                    <div class="card-body">
                        <div class="reporting-grid">
                            <div class="item-row">
                                <div class="item-left">
                                    <div class="item-name">Monthly Report</div>
                                    <div class="item-meta">Oct 20, 2025</div>
                                </div>
                                <div class="item-right">
                                    <span class="status-badge status-approved">Submitted</span>
                                </div>
                            </div>
                            <div class="item-row">
                                <div class="item-left">
                                    <div class="item-name">Risk Summary</div>
                                    <div class="item-meta">Due Oct 31</div>
                                </div>
                                <div class="item-right">
                                    <span class="status-badge status-pending">In Progress</span>
                                </div>
                            </div>
                            <div class="item-row">
                                <div class="item-left">
                                    <div class="item-name">Board Report</div>
                                    <div class="item-meta">Due Nov 15</div>
                                </div>
                                <div class="item-right">
                                    <span class="status-badge status-active">Scheduled</span>
                                </div>
                            </div>
                            <div class="item-row">
                                <div class="item-left">
                                    <div class="item-name">Audit Report</div>
                                    <div class="item-meta">Quarterly • Q4 2025</div>
                                </div>
                                <div class="item-right">
                                    <span class="status-badge status-active">Scheduled</span>
                                </div>
                            </div>
                            <div class="item-row">
                                <div class="item-left">
                                    <div class="item-name">Regulatory Submission</div>
                                    <div class="item-meta">Dec 15, 2025</div>
                                </div>
                                <div class="item-right">
                                    <span class="status-badge status-active">Scheduled</span>
                                </div>
                            </div>
                            <div class="item-row">
                                <div class="item-left">
                                    <div class="item-name">Annual Compliance</div>
                                    <div class="item-meta">Due Dec 31</div>
                                </div>
                                <div class="item-right">
                                    <span class="status-badge status-active">Pending</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    {{-- <script src="assets/js/pages/apexcharts-column.init.js"></script> --}}

    {{-- <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script> --}}
    {{-- @include('admin.dashboard.include.script') --}}
    <script>
        // console.log($('#total-user'));
        $('.total-loading').html('<span class="fa fa-spinner fa-spin"></span>')
        $.ajax({
            url: '{{ url('/') }}',
            data: {
                dashbaord_meta: 1
            },
            success: function(r) {
                $('#total-user').html(r.total_user)
                $('#total-employees').html(r.total_employees)
                $('#total-accounts').html(r.total_accounts)
                $('#total-teams').html(r.total_teams)
            }
        })
        $(function() {
            var chartColumnColors = getChartColorsArray("column_chart"),
                chartColumnDatatalabelColors =
                (chartColumnColors &&
                    ((options = {
                            chart: {
                                height: 350,
                                type: "bar",
                                toolbar: {
                                    show: !1
                                }
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: !1,
                                    columnWidth: "45%",
                                    endingShape: "rounded"
                                },
                            },
                            dataLabels: {
                                enabled: !1
                            },
                            stroke: {
                                show: !0,
                                width: 2,
                                colors: ["transparent"]
                            },
                            series: [{
                                    name: "Sales",
                                    data: [46, 57, 59, 54, 62, 58, 64, 60, 66]
                                },
                                {
                                    name: "Payments",
                                    data: [74, 83, 102, 97, 86, 106, 93, 114, 94]
                                },
                                {
                                    name: "Due Payments",
                                    data: [37, 42, 38, 26, 47, 50, 54, 55, 43],
                                },
                            ],
                            colors: chartColumnColors,
                            xaxis: {
                                categories: [
                                    "Feb",
                                    "Mar",
                                    "Apr",
                                    "May",
                                    "Jun",
                                    "Jul",
                                    "Aug",
                                    "Sep",
                                    "Oct",
                                ],
                            },
                            yaxis: {
                                title: {
                                    text: "$ (thousands)"
                                }
                            },
                            grid: {
                                borderColor: "#f1f1f1"
                            },
                            fill: {
                                opacity: 1
                            },
                            tooltip: {
                                y: {
                                    formatter: function(e) {
                                        return "$ " + e + " thousands";
                                    },
                                },
                            },
                        }),
                        (chart = new ApexCharts(
                            document.querySelector("#column_chart"),
                            options
                        )).render()))

            var chartColumnColors = getChartColorsArray("purchaseChart"),
                chartColumnDatatalabelColors =
                (chartColumnColors &&
                    ((options = {
                            chart: {
                                height: 350,
                                type: "bar",
                                toolbar: {
                                    show: !1
                                }
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: !1,
                                    columnWidth: "45%",
                                    endingShape: "rounded"
                                },
                            },
                            dataLabels: {
                                enabled: !1
                            },
                            stroke: {
                                show: !0,
                                width: 2,
                                colors: ["transparent"]
                            },
                            series: [{
                                    name: "Sales",
                                    data: [46, 57, 59, 54, 62, 58, 64, 60, 66]
                                },
                                {
                                    name: "Payments",
                                    data: [74, 83, 102, 97, 86, 106, 93, 114, 94]
                                },
                                {
                                    name: "Due Payments",
                                    data: [37, 42, 38, 26, 47, 50, 54, 55, 43],
                                },
                            ],
                            colors: chartColumnColors,
                            xaxis: {
                                categories: [
                                    "Feb",
                                    "Mar",
                                    "Apr",
                                    "May",
                                    "Jun",
                                    "Jul",
                                    "Aug",
                                    "Sep",
                                    "Oct",
                                ],
                            },
                            yaxis: {
                                title: {
                                    text: "$ (thousands)"
                                }
                            },
                            grid: {
                                borderColor: "#f1f1f1"
                            },
                            fill: {
                                opacity: 1
                            },
                            tooltip: {
                                y: {
                                    formatter: function(e) {
                                        return "$ " + e + " thousands";
                                    },
                                },
                            },
                        }),
                        (chart = new ApexCharts(
                            document.querySelector("#purchaseChart"),
                            options
                        )).render()))
        });
    </script>
@stop
