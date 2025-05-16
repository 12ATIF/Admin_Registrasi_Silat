<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Admin Pencak Silat</title>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<!-- DataTables Bootstrap 5 CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.bootstrap5.min.css">

<!-- Custom CSS -->
<style>
    body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    .sidebar {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        z-index: 100;
    }
    .main-content {
        padding-top: 56px; // Reduce to match navbar height
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
</style>

@stack('styles')