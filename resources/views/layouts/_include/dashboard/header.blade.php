<!-- Header -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - @yield('title')</title>

    <link rel="shortcut icon" type="image/x-icon" href="/dashboard/assets/img/favicon.ico">

    <!-- Custom fonts for this template-->
    <link href="/dashboard/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="/dashboard/assets/css/font.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/dashboard/assets/css/sb-admin-2.min.css" rel="stylesheet">
    
    <!-- Global Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Theme CSS - Tema Laranja -->
    <link href="/dashboard/assets/css/custom-theme.css" rel="stylesheet">
    <!-- Dark Mode CSS -->
    <link href="/dashboard/assets/css/dark-mode.css" rel="stylesheet">
    <link href="/dashboard/assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!-- Custom Sidebar Dropdown Styles -->
    <link href="/dashboard/assets/css/custom-sidebar.css" rel="stylesheet">
    <!-- Custom Form Fields Styles -->
    <link href="/dashboard/assets/css/custom-forms.css" rel="stylesheet">

    {{-- sweetalert --}}
    <link rel="stylesheet" href="/dashboard/assets/css/sweetalert2.min.css">
    <script src="/dashboard/assets/js/sweetalert2.all.min.js"></script>

    {{-- FullCalendary --}}
    <link rel="stylesheet" href="/dashboard/assets/css/fullcalendar/fullcalendar.css" />
    <script src="/dashboard/assets/js/fullcalendar/jquery.min.js"></script>
    <script src="/dashboard/assets/js/fullcalendar/jquery-ui.min.js"></script>
    <script src="/dashboard/assets/js/fullcalendar/moment.min.js"></script>
    <script src="/dashboard/assets/js/fullcalendar/fullcalendar.min.js"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- EndHeader -->
