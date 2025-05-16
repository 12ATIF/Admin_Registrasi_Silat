<!-- Shared Admin Styles -->
<style>
    /* Sidebar Styles */
    .sidebar {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        z-index: 100;
    }
    
    .sidebar .nav-link {
        color: #333;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        margin: 0.2rem 0;
    }
    
    .sidebar .nav-link:hover {
        background-color: #f0f0f0;
    }
    
    .sidebar .nav-link.active {
        background-color: #6c757d;
        color: white;
    }
    
    /* Layout Styles */
    body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    
    .main-content {
        padding-top: 56px; /* Reduce to match navbar height */
    }
    
    @media (min-width: 768px) {
        body {
            flex-direction: row;
        }
        
        .sidebar {
            width: 250px;
            min-height: 100vh;
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
        border-radius: 0.25rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    /* Card Style Utilities */
    .border-left-primary {
        border-left: 4px solid #4e73df;
    }
    
    .border-left-success {
        border-left: 4px solid #1cc88a;
    }
    
    .border-left-warning {
        border-left: 4px solid #f6c23e;
    }
    
    .border-left-danger {
        border-left: 4px solid #e74a3b;
    }
    
    .border-left-info {
        border-left: 4px solid #36b9cc;
    }
    
    /* Dropdown Submenu Support */
    .dropdown-submenu {
        position: relative;
    }
    
    .dropdown-submenu .dropdown-menu {
        top: 0;
        left: 100%;
        margin-top: -1px;
    }
</style>