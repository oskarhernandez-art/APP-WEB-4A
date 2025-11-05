<!DOCTYPE html>
<html lang="es">
<head> 
  <meta charset="utf-8">
  <title>Sistema de inventarios</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
  .navbar-custom {
    background-color: #9ac65cff; 
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
  <ul class="navbar-nav ms-auto">
    <li class="nav-item">
      <a class="nav-link" href="catalogo.html" style="color: white;">Inicio</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="catalogo.html" style="color: white;">Catálogo</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="registro.html" style="color: white;">Registrar</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="alertas.html" style="color: white;">Otros</a>
    </li>
  </ul>
    </div>
  </nav>

 <!-- INICIO -->
<header id="inicio" class="bg-light py-5">
  <div class="container">
    <div class="row align-items-center gy-4">
      <div class="col-md-6">
        <h1 class="display-5 fw-bold">Hola, Bienvenido a Don Cloro</h1>
      </div>
      <div class="col-md-6 text-center">
        <div class="card h-100">
          <img src="https://blog.flota.es/wp-content/uploads/2015/12/productos-limpieza-basicos.jpg" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title">Cerrar Sesion</h5>
            <p class="card-text">Recuerda cerrar sesión cada fin de turno por seguridad.</p>
            <a class="btn btn-outline-danger btn-sm" href="index.html">Salir</a>
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

</body>
</html> 
