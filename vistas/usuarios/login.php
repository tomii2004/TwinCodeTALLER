<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token }}">

  <title>Taller | Iniciar Sesión</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="icon" href="vistas/favicon.ico" type="image/x-icon">

  <style>
    body.login-page {
      background: linear-gradient(135deg, #1a1a1a 0%, #262626 100%);
      position: relative;
      min-height: 100vh;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .gears-bg {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 1;
    }

    .gear {
      position: absolute;
      transform-origin: center center;
      mix-blend-mode: screen;
      opacity: 0.2;
    }

    @keyframes spin-cw {
      from {
        transform: rotate(0deg);
      }

      to {
        transform: rotate(360deg);
      }
    }

    @keyframes spin-ccw {
      from {
        transform: rotate(0deg);
      }

      to {
        transform: rotate(-360deg);
      }
    }

    /* Engranajes: colores metálicos variados */
    .g1 {
      font-size: 8rem;
      top: 10%;
      left: 10%;
      color: #b0b0b0;
      animation: spin-cw 14s linear infinite;
    }

    .g2 {
      font-size: 6rem;
      top: 20%;
      left: 75%;
      color: #8a7f72;
      animation: spin-ccw 11s linear infinite;
    }

    .g3 {
      font-size: 9rem;
      top: 50%;
      left: 30%;
      color: #c0c0c0;
      animation: spin-cw 16s linear infinite;
    }

    .g4 {
      font-size: 5rem;
      top: 75%;
      left: 85%;
      color: #007bff;
      animation: spin-ccw 9s linear infinite;
    }

    .g5 {
      font-size: 7rem;
      top: 35%;
      left: 50%;
      color: #007bff;
      animation: spin-cw 13s linear infinite;
    }

    .g6 {
      font-size: 6.5rem;
      top: 60%;
      left: 65%;
      color: #777777;
      animation: spin-ccw 12s linear infinite;
    }

    .g7 {
      font-size: 4rem;
      top: 15%;
      left: 40%;
      color: #007bff;
      animation: spin-cw 10s linear infinite;
    }

    .g8 {
      font-size: 5.5rem;
      top: 80%;
      left: 20%;
      color: #9e9e9e;
      animation: spin-ccw 14s linear infinite;
    }

    .g9 {
      font-size: 6rem;
      top: 45%;
      left: 85%;
      color: #aaa;
      animation: spin-cw 15s linear infinite;
    }

    .g10 {
      font-size: 7.5rem;
      top: 70%;
      left: 45%;
      color: #007bff;
      animation: spin-ccw 10s linear infinite;
    }




    .login-box {
      position: relative;
      z-index: 2;
      width: 360px;
      margin: 2rem auto;
      border-radius: 1rem;
      /* Redondear bordes */
      overflow: hidden;
      /* Asegura que no haya bordes fuera de lugar */
      background: transparent;
      border: none;
    }

    .login-card-body {
      background: rgba(20, 20, 20, 0.92);
      border-radius: 0.75rem;
      padding: 2rem;
      color: #e0e0e0;
      border: none;
      box-shadow: none;
    }

    /* Agregado para eliminar las puntas blancas */
    .card {
      background: rgba(20, 20, 20, 0.92);
      border: none;
      border-radius: 0.75rem;
      box-shadow: none;
    }


    .login-logo img {
      max-height: 180px;
      height: auto;
      width: auto;
    }

    /* Form moderno: líneas suaves y colores neutros */
    .login-card-body form {
      display: flex;
      flex-direction: column;
      gap: 1.25rem;
    }

    .login-card-body .input-group {
      position: relative;
    }

    .login-card-body .form-control {
      border: none;
      border-bottom: 2px solid #ccc;
      border-radius: 0;
      padding: .75rem 0;
      font-size: 1rem;
      background: transparent;
      color: #f1f1f1;
    }

    .login-card-body .form-control:focus {
      outline: none;
      border-bottom-color: #00aaff;
      box-shadow: none;
    }

    .login-card-body .input-group-text {
      position: absolute;
      right: 0;
      top: 50%;
      transform: translateY(-50%);
      background: transparent;
      border: none;
      color: #aaa;
      padding: 0;
      font-size: 1.2rem;
    }

    .login-card-body .btn-primary {
      background: #007bff;
      border: none;
      border-radius: .5rem;
      padding: .75rem;
      font-size: 1.1rem;
      color: #fff;
      transition: background .3s;
    }

    .login-card-body .btn-primary:hover {
      background: #0056b3;
    }



    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
      -webkit-box-shadow: 0 0 0 1000px rgba(20, 20, 20, 0.92) inset !important;
      -webkit-text-fill-color: #f1f1f1 !important;
      transition: background-color 9999s ease-out 0s !important;
      border-bottom: 2px solid #00aaff !important;

    }
  </style>
</head>

<body class="hold-transition login-page">
  <div class="gears-bg">
    <i class="fas fa-cog gear g1"></i>
    <i class="fas fa-cog gear g2"></i>
    <i class="fas fa-cog gear g3"></i>
    <i class="fas fa-cog gear g4"></i>
    <i class="fas fa-cog gear g5"></i>
    <i class="fas fa-cog gear g6"></i>
    <i class="fas fa-cog gear g7"></i>
    <i class="fas fa-cog gear g8"></i>
    <i class="fas fa-cog gear g9"></i>
    <i class="fas fa-cog gear g10"></i>
  </div>

  <div class="login-box">
    <div class="login-logo">
      <img src="dist/img/logoG.png" alt="Taller">
    </div>
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Iniciar sesión</p>

        <?php if (!empty($error)): ?>
          <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
          </div>
        <?php endif; ?>

        <form action="?c=usuarios&a=login" method="POST">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Usuario" name="Nombre" required>
            <div class="input-group-text"><span class="fas fa-user"></span></div>
          </div>

          <div class="input-group">
            <input type="password" class="form-control" placeholder="Contraseña" name="Password" required>
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>

          <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
        </form>

      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="dist/js/adminlte.min.js"></script>
</body>

</html>