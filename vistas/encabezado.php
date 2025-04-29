<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TallerJB | Sistema v1</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="plugins/fullcalendar/main.css">
  <script src="plugins/fullcalendar/main.js"></script>
  <script src="plugins/fullcalendar/locales/es.js"></script>
  <link rel="stylesheet" href="vistas/estilospropios.css">

  <style>
    .swal2-popup.alerta-grande {
      width: 450px !important;
      height: auto !important;
      font-size: 14px !important;
    }
    .btnclear {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      border: none;
      background: transparent;
      cursor: pointer;
      padding: 0;
      font-size: 16px;
      color: grey;
    }
    
  </style>
</head>
<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble" src="dist/img/logojb1.png" alt="AdminLTELogo" height="100" width="100">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="?c=turnos" class="nav-link">Inicio</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li> -->
      
    </ul>
    
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="?c=turnos" class="brand-link">
      <img src="dist/img/logojb1.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light" style="font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif">Taller JB</span> 
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          
          <li class="nav-item">
            <a href="?c=turnos" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                Turnos
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="?c=presupuestos" class="nav-link">
              <i class="nav-icon fas fa-solid fa-comments-dollar"></i>
              <p>
                Presupuestos
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="?c=trabajos" class="nav-link">
              <i class="nav-icon fas fa-solid fa-tools"></i>
              <p>
                Trabajos
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="?c=productos" class="nav-link">
              <i class="nav-icon fas fa-solid fa-wrench"></i>
              <p>
                Productos
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="?c=clientes" class="nav-link">
              <i class="nav-icon fas fa-solid fa-users"></i>
              <p>
                Clientes
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="?c=categorias" class="nav-link">
              <i class="nav-icon fas fa-solid fa-list"></i>
              <p>
                Categorias
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="?c=caja" class="nav-link">
              <i class="nav-icon fas fa-solid fa-money-bill-wave"></i>
              <p>
                Caja
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-danger" href="?c=usuarios&a=cerrarSesion" role="button">
              <i class="nav-icon fas fa-solid fa-sign-out-alt"></i><p>Cerrar Sesión</p> 
            </a>
          </li>
        </ul>
      </nav>
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Contenido Principal -->
