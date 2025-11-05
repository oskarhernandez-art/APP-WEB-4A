<!DOCTYPE html>
<html lang="es">
<head> 
  <meta charset="utf-8">
  <title>Sistema de inventarios</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
  .navbar-custom {
    background-color: #1c054a; 
   }
    .error-message {
    color: red;
    font-size: 14px;
    margin-top: 5px;
   }
</style>

</head>
<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-custom bg-custom">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#" style="color: white;">DON CLORO</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
        <span class="navbar-toggler-icon"></span>
      </button>
   <div id="menu" class="collapse navbar-collapse">
    </div>
  </nav>

 <!-- INICIO -->
<header id="inicio" class="bg-light py-5">
  <div class="container">
    <div class="row align-items-center gy-4">
      <div class="col-md-6">
        <h1 class="display-5 fw-bold">Hola, Bienvenido a Don Cloro</h1>
        <p class="lead">Inicia sesión para nada a nuestro sistema de inventarios.</p>
                <!-- Mostrar mensajes de error -->
        <?php if (isset($_GET['error'])): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php 
            switch($_GET['error']) {
              case '1': echo 'Usuario o contraseña incorrectos'; break;
              case '2': echo 'Por favor inicia sesión'; break;
              default: echo 'Error desconocido';
            }
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>
        
      </div>
      <div class="col-md-6 text-center">
        <div class="card h-100">
          <img src="https://blog.flota.es/wp-content/uploads/2015/12/productos-limpieza-basicos.jpg" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title">Inicio de sesión</h5>

            <!-- FORMULARIO DE LOGIN -->
            <form action="procesar_login.php" method="POST">
            <p class="card-text">Tipo de usuario:</p>
              <select name="tipo_usuario" class="form-select mb-3" onchange="mostrarLogin()" required>              <option value="">Selecciona una opción</option>
              <option value="admin">Administrador</option>
              <option value="empleado">Empleado</option>
              <option value="invitado">Invitado</option>
            </select>

            <!-- el login oculto hasta q seleccione q usuario es -->
            <div id="loginCampos" style="display: none;">
              <p class="card-text">Usuario:</p>
                <input type="text" name="username" placeholder="Nombre de usuario" class="form-control mb-2" required>
              <p class="card-text">Contraseña:</p>
                <input type="password" name="password" placeholder="Contraseña" class="form-control mb-3" required>

                <!-- Botón para iniciar sesión -->
                <button type="submit" class="btn btn-outline-primary btn-sm">Iniciar Sesión</button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</header>


  <!-- SERVICIO TECNICO -->
  <section id="contacto" class="py-5">
    <div class="container">
      <h2 class="mb-3 text-center">Contacto</h2>
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <form class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nombre</label>
              <input type="text" class="form-control" placeholder="Tu nombre">
            </div>
            <div class="col-md-6">
              <label class="form-label">Correo</label>
              <input type="email" class="form-control" placeholder="nombre@correo.com">
            </div>
            <div class="col-12">
              <label class="form-label">Especifica tu problema</label>
              <textarea class="form-control" rows="4" placeholder="Escribe tu mensaje"></textarea>
            </div>
            <div class="col-12 text-end">
              <button type="button" class="btn btn-primary">Enviar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="bg-primary text-success text-success py-3">
    <div class="container">
      <small>© 2025 | PAGINA</small>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!--scrip para mostrar el login dsp de seleccionar el tipo de usuario-->
  <script>
  function mostrarLogin() {
    const tipo = document.querySelector('[name="tipo_usuario"]').value;
    const campos = document.getElementById("loginCampos");
    campos.style.display = tipo ? "block" : "none";
  }
</script>

</body>
</html> 
