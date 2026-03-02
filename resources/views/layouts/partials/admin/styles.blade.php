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
        box-shadow: 4px 0 10px rgba(0, 0, 0, 0.3);
        z-index: 100;
        background: linear-gradient(180deg, #212121 0%, #1a1a1a 100%) !important;
        color: var(--white);
        /* Independent Scrolling */
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        height: 100vh;
        overflow-y: auto;
        /* Scrollbar Styling */
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
    }

    /* Webkit Scrollbar */
    .sidebar::-webkit-scrollbar {
        width: 6px;
    }
    .sidebar::-webkit-scrollbar-track {
        background: transparent;
    }
    .sidebar::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 20px;
    }

    .sidebar .sidebar-logo {
        color: var(--white);
        font-weight: 600;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    .sidebar .sidebar-logo img {
        filter: drop-shadow(0 0 5px rgba(255, 193, 7, 0.3));
    }
    
    .sidebar .nav-link {
        color: #e0e0e0;
        padding: 0.8rem 1.2rem;
        border-radius: 12px;
        margin: 0.3rem 0;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        display: flex;
        align-items: center;
        border: 1px solid transparent;
    }
    
    .sidebar .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.08);
        color: var(--primary);
        transform: translateX(5px);
        border-color: rgba(255, 255, 255, 0.1);
    }
    
    .sidebar .nav-link.active {
        background: linear-gradient(90deg, var(--primary) 0%, rgba(255, 193, 7, 0.2) 100%);
        color: #212121;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.2);
        border: none;
    }

    .sidebar .nav-link.active i {
        color: #212121;
    }
    
    .sidebar .nav-link i {
        width: 24px;
        text-align: center;
        margin-right: 12px;
        font-size: 1.1rem;
        transition: transform 0.3s ease;
    }

    .sidebar .nav-link:hover i {
        transform: scale(1.1) rotate(5deg);
    }
    
    .sidebar .text-muted {
        color: var(--primary) !important;
        font-weight: 700;
        letter-spacing: 1.5px;
        font-size: 0.75rem;
        margin-top: 1.5rem;
        opacity: 0.8;
    }
    
    .sidebar hr {
        border-color: rgba(255, 255, 255, 0.1);
        margin: 1.5rem 0;
    }
    
    .sidebar .dropdown-menu {
        background-color: #2c2c2c;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }
    
    .sidebar .dropdown-item {
        color: #e0e0e0;
        padding: 0.7rem 1.5rem;
    }
    
    .sidebar .dropdown-item:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: var(--primary);
        padding-left: 1.8rem; /* Slide effect */
    }
    
    .sidebar .user-dropdown {
        background: linear-gradient(145deg, rgba(255,255,255,0.05) 0%, rgba(0,0,0,0.1) 100%);
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 12px;
        padding: 12px;
        transition: all 0.3s ease;
        margin-top: auto; /* Push to bottom if flex container */
    }
    
    .sidebar .user-dropdown:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    /* Layout Styles */
    body {
        height: 100vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        font-family: 'Poppins', 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        background-color: #f6f8fa;
    }
    
    /* Navbar Styles */
    .navbar {
        background-color: rgba(255, 255, 255, 0.85) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border-bottom: 1px solid rgba(255, 255, 255, 0.5);
        z-index: 999; /* Increased Z-Index to be above content but below sidebar (if sidebar is higher) */
        transition: all 0.3s ease;
        position: sticky;
        top: 0;
    }
    
    .navbar .navbar-toggler {
        border: none;
        color: var(--secondary);
        padding: 0.5rem;
        border-radius: 8px;
        transition: background 0.3s;
    }

    .navbar .navbar-toggler:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }
    
    .navbar .dropdown-menu {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        padding: 0.5rem;
        background-color: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
    }
    
    .navbar .dropdown-header {
        color: var(--primary);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.75rem;
        padding: 0.8rem 1.5rem;
    }
    
    .navbar .dropdown-item {
        padding: 0.7rem 1.5rem;
        border-radius: 10px;
        margin-bottom: 2px;
        transition: all 0.2s ease;
        font-weight: 500;
    }
    
    .navbar .dropdown-item:hover {
        background-color: rgba(255, 193, 7, 0.1);
        color: var(--secondary);
        transform: translateX(5px);
    }
    
    .navbar .nav-link {
        font-weight: 500;
        padding: 0.5rem 1rem !important;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .navbar .nav-link:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }

    .navbar .nav-item .badge {
        font-size: 0.65rem;
        padding: 0.35rem 0.55rem;
        box-shadow: 0 2px 5px rgba(220, 53, 69, 0.4);
        animation: pulse-badge 2s infinite;
    }

    @keyframes pulse-badge {
        0% { transform: translate(-50%, -50%) scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
        70% { transform: translate(-50%, -50%) scale(1.1); box-shadow: 0 0 0 6px rgba(220, 53, 69, 0); }
        100% { transform: translate(-50%, -50%) scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
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
    
    /* Fix DataTables length select dropdown */
    .dataTables_length select {
        min-width: 70px;
        font-size: 0.875rem;
        line-height: 1.5;
        background-color: #fff;
        cursor: pointer;
    }
    
    .dataTables_length label {
        font-size: 0.875rem;
        white-space: nowrap;
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
        .sidebar {
            width: 260px;
        }
        
        .content {
            margin-left: 260px;
            width: calc(100% - 260px);
            height: 100vh;
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