<!-- Shared Admin Styles -->
<style>
    :root {
        --primary: #FFC107; /* Kuning dari sabuk tiger */
        --secondary: #212121; /* Hitam dari seragam tiger */
        --accent: #FF9800; /* Oranye dari warna tiger */
        --success: #4CAF50; /* Hijau dari mata tiger */
        --info: #03A9F4;
        --warning: #FF9800;
        --danger: #F44336;
        --light: #f8f9fa;
        --dark: #343a40;
        --white: #FFFFFF;
        --gray: #6c757d;
        --gray-light: #f0f0f0;
        --gray-dark: #495057;
    }
    
    /* Sidebar Styles */
    .sidebar {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        z-index: 100;
        background-color: var(--secondary) !important;
        color: var(--white);
    }
    
    .sidebar .sidebar-logo {
        color: var(--white);
        font-weight: 600;
    }
    
    .sidebar .sidebar-logo img {
        filter: drop-shadow(0 0 3px rgba(0,0,0,0.3));
    }
    
    .sidebar .nav-link {
        color: #e0e0e0;
        padding: 0.6rem 1rem;
        border-radius: 0.25rem;
        margin: 0.2rem 0;
        transition: all 0.2s ease;
    }
    
    .sidebar .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: var(--primary);
    }
    
    .sidebar .nav-link.active {
        background-color: var(--primary);
        color: var(--secondary);
        font-weight: 600;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .sidebar .nav-link i {
        width: 20px;
        text-align: center;
        margin-right: 8px;
    }
    
    .sidebar .text-muted {
        color: var(--primary) !important;
        font-weight: 600;
        letter-spacing: 1px;
    }
    
    .sidebar hr {
        border-color: rgba(255, 255, 255, 0.1);
        margin: 1rem 0;
    }
    
    .sidebar .dropdown-menu {
        background-color: var(--secondary);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .sidebar .dropdown-item {
        color: #e0e0e0;
    }
    
    .sidebar .dropdown-item:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: var(--primary);
    }
    
    .sidebar .dropdown-divider {
        border-color: rgba(255, 255, 255, 0.1);
    }
    
    .sidebar .user-dropdown {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        padding: 8px;
        transition: all 0.2s ease;
    }
    
    .sidebar .user-dropdown:hover {
        background-color: rgba(0, 0, 0, 0.3);
    }
    
    /* Layout Styles */
    body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        font-family: 'Poppins', 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        background-color: #f6f8fa;
    }
    
    /* Navbar Styles */
    .navbar {
        background-color: var(--white) !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        z-index: 99;
    }
    
    .navbar .navbar-toggler {
        border-color: var(--primary);
        color: var(--secondary);
    }
    
    .navbar .dropdown-menu {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .navbar .dropdown-header {
        color: var(--gray-dark);
        font-weight: 600;
    }
    
    .navbar .dropdown-item {
        padding: 0.5rem 1.5rem;
    }
    
    .navbar .dropdown-item:hover {
        background-color: var(--gray-light);
    }
    
    .navbar .nav-item .badge {
        font-size: 0.65rem;
        padding: 0.2rem 0.5rem;
    }
    
    /* Breadcrumb Styles */
    .breadcrumb {
        background-color: transparent;
        padding: 0;
        margin-bottom: 0;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        font-size: 1.2rem;
        line-height: 1;
        vertical-align: middle;
    }
    
    .breadcrumb-item a {
        color: var(--secondary);
        text-decoration: none;
    }
    
    .breadcrumb-item a:hover {
        color: var(--primary);
        text-decoration: underline;
    }
    
    .breadcrumb-item.active {
        color: var(--gray);
    }
    
    /* Content Styles */
    .main-content {
        padding-top: 56px; /* Reduce to match navbar height */
        background-color: #f6f8fa;
    }
    
    /* Card Styling */
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.2s ease;
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    
    .card-header {
        background-color: rgba(0, 0, 0, 0.02);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        font-weight: 600;
    }
    
    /* Button Styles */
    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
        color: var(--secondary);
        font-weight: 600;
    }
    
    .btn-primary:hover, .btn-primary:focus {
        background-color: #e6ad00;
        border-color: #e6ad00;
        color: var(--secondary);
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .btn-secondary {
        background-color: var(--secondary);
        border-color: var(--secondary);
    }
    
    .btn-secondary:hover, .btn-secondary:focus {
        background-color: #0d0d0d;
        border-color: #0d0d0d;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    /* Button Outline Styles */
    .btn-outline-primary {
        color: var(--primary);
        border-color: var(--primary);
    }
    
    .btn-outline-primary:hover {
        background-color: var(--primary);
        border-color: var(--primary);
        color: var(--secondary);
    }
    
    /* Alert Styles */
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
    
    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    
    /* Footer Styles */
    footer {
        background-color: var(--light);
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        font-size: 0.875rem;
    }
    
    /* DataTables Custom Styling */
    div.dataTables_wrapper div.dataTables_processing {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 200px;
        margin-left: -100px;
        margin-top: -26px;
        text-align: center;
        padding: 1em 0;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    table.dataTable thead th, table.dataTable thead td {
        border-bottom: 1px solid #e0e0e0;
    }
    
    table.dataTable.no-footer {
        border-bottom: 1px solid #e0e0e0;
    }
    
    .dataTables_info, .dataTables_paginate {
        margin-top: 1rem !important;
    }
    
    .dataTables_filter input, .dataTables_length select {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
    }
    
    .dataTables_filter input:focus, .dataTables_length select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }
    
    .page-link {
        color: var(--secondary);
    }
    
    .page-item.active .page-link {
        background-color: var(--primary);
        border-color: var(--primary);
        color: var(--secondary);
    }
    
    /* Utilities - Card Borders */
    .border-left-primary {
        border-left: 4px solid var(--primary) !important;
    }
    
    .border-left-secondary {
        border-left: 4px solid var(--secondary) !important;
    }
    
    .border-left-success {
        border-left: 4px solid var(--success) !important;
    }
    
    .border-left-warning {
        border-left: 4px solid var(--warning) !important;
    }
    
    .border-left-danger {
        border-left: 4px solid var(--danger) !important;
    }
    
    .border-left-info {
        border-left: 4px solid var(--info) !important;
    }
    
    /* Responsive Layout */
    @media (min-width: 768px) {
        body {
            flex-direction: row;
        }
        
        .sidebar {
            width: 260px;
            min-height: 100vh;
            transition: width 0.3s ease;
        }
        
        .sidebar-collapsed {
            width: 70px;
        }
        
        .content {
            flex: 1;
            min-height: 100vh;
        }
        
        .navbar-top {
            height: 56px;
            position: relative;
        }
        
        .main-content {
            padding-top: 20px;
        }
    }
    
    /* Mobile Optimizations */
    @media (max-width: 767.98px) {
        .offcanvas-header {
            background-color: var(--secondary);
            color: var(--white);
        }
        
        .offcanvas-body {
            padding: 1rem;
        }
        
        .navbar .dropdown-menu {
            width: 280px;
        }
        
        .dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate {
            text-align: left !important;
            margin-bottom: 0.5rem;
        }
        
        .dataTables_paginate .pagination {
            justify-content: flex-start !important;
        }
    }
    
    /* Animation */
    .nav-link, .btn, .card {
        transition: all 0.2s ease;
    }
    
    /* Additional User Profile Style */
    .user-profile-circle {
        width: 38px;
        height: 38px;
        background-color: var(--primary);
        color: var(--secondary);
        font-weight: bold;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>