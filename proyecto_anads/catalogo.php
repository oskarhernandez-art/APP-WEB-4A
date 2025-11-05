<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php?error=2");
    exit;
}

$nombre = $_SESSION['nombre_completo'] ?? 'Usuario';
$rol = $_SESSION['rol'] ?? 'Sin rol';
$id_rol = $_SESSION['id_rol'] ?? 3;  // Si no existe, asumir "Consulta"


include 'config/database.php';

// Conectar a la base de datos y obtener productos
$database = new Database();
$db = $database->getConnection();

$query = "SELECT p.*, c.Nombre_c as Categoria 
          FROM Productos p 
          LEFT JOIN Categorias c ON p.Id_categoria = c.Id_categoria 
          ORDER BY p.Nombre_p";
$stmt = $db->prepare($query);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head> 
  <meta charset="utf-8">
  <title>Sistema de inventarios</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
  .navbar-custom {
    background-color: #054a15ff; 
   }
</style>

</head>
<body>

<!-- NAVBAR CON CAMPANITA DE NOTIFICACIONES -->
<nav class="navbar navbar-expand-lg navbar-custom bg-custom">
  <div class="container">
    <a class="navbar-brand fw-bold" href="catalogo.php" style="color: white;">DON CLORO</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="menu" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="catalogo.php" style="color: white;">OSKAR HERNANDEZ</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="catalogo.php" style="color: white;">HEIDY GARCIA</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="registro.php" style="color: white;">ADRIANA GONZALEZ</a>
        </li>
        
        <!-- CAMPANITA DE NOTIFICACIONES -->
        <li class="nav-item dropdown">
          <a class="nav-link position-relative" href="alertas.php" style="color: white;">
            <i class="fas fa-bell"></i> Alertas
            <?php
            // Contar productos con stock bajo
            $query_alertas = "SELECT COUNT(*) as total_alertas FROM Productos WHERE Stock < 10";
            $stmt_alertas = $db->prepare($query_alertas);
            $stmt_alertas->execute();
            $alertas = $stmt_alertas->fetch(PDO::FETCH_ASSOC);
            
            if ($alertas['total_alertas'] > 0): 
            ?>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?php echo $alertas['total_alertas']; ?>
                <span class="visually-hidden">alertas de stock</span>
              </span>
            <?php endif; ?>
          </a>
        </li>

        <!-- USUARIO -->
        <li class="nav-item">
          <span class="nav-link" style="color: #ffd700;">
            <i class="fas fa-user"></i> <?php echo $nombre; ?>
            <small class="badge bg-success"><?php echo $rol; ?></small>
          </span>
        </li>
        
        <!-- CERRAR SESIÓN -->
        <li class="nav-item">
          <a class="nav-link" href="logout.php" style="color: #ff6b6b;">
            <i class="fas fa-sign-out-alt"></i> Salir
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
    
    <!-- Mostrar información del usuario -->
    <div class="navbar-text" style="color: white;">
      <i class="fas fa-user"></i> <?php echo $nombre; ?> 
      <span class="badge bg-success ms-1"><?php echo $rol; ?></span>
      <a href="logout.php" class="btn btn-outline-light btn-sm ms-2">
        <i class="fas fa-sign-out-alt"></i> Salir
      </a>
    </div>
  </div>
</nav>

<!-- CATALOGO -->
  <section id="portfolio" class="bg-light py-5">
    <div class="container">
      <h2 class="mb-4 text-center">Acerca del Equipo</h2>
      <div class="row g-4">
        <div class="col-sm-6 col-lg-4">
          <div class="card h-100">
            <img src="img/Image.jpg" class="card-img-top" alt="Proyecto 1">
            <div class="card-body">
              <h5 class="card-title">ROBLOXIANO69</h5>
              <p class="card-text">FAN DE ROBLOX Y BRAINROT Y ME GUSTA UN PROFE BBUENO DOS UNO DE 23 CASI 24 Y OTRO DE 33.</p>
              <a class="btn btn-outline-primary btn-sm" href="#">Detalles</a>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4">
          <div class="card h-100">
            <img src="img/Image2.jpg" class="card-img-top" alt="Proyecto 2">
            <div class="card-body">
              <h5 class="card-title">AMO A MAX CHARLES HOON</h5>
              <p class="card-text">ME GUSTAN 4 Y DOS TRES TRUCOS
                .</p>
              <a class="btn btn-outline-primary btn-sm" href="#">Detalles</a>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4">
          <div class="card h-100">
            <img src="img/Image3.jpeg" class="card-img-top" alt="Proyecto 3">
            <div class="card-body">
              <h5 class="card-title">NOVELALOVER</h5>
              <p class="card-text">AMA LEER CHINOS Y LESBIANOS.</p>
              <a class="btn btn-outline-primary btn-sm" href="#">Detalles</a>
            </div>
          </div>
        </div>
       
  </section>

<!-- CATALOGO CON DATOS DINÁMICOS -->
<section id="portfolio" class="bg-light py-5">
  <div class="container">
    <h2 class="mb-4 text-center">Catálogo de Productos</h2>
    
    <!-- Botones de acción según el rol -->
    <div class="text-center mb-4">
      <a href="catalogo.php" class="btn btn-primary">
        <i class="fas fa-sync-alt"></i> Actualizar
      </a>
      <?php if ($id_rol == 1 || $id_rol == 2): ?>
        <a href="registro.php" class="btn btn-success">
          <i class="fas fa-plus"></i> Agregar Producto
        </a>
      <?php endif; ?>
      <a href="alertas.php" class="btn btn-warning">
        <i class="fas fa-exclamation-triangle"></i> Alertas de Stock
      </a>
    </div>

    
    <div class="row g-4">
      <?php if (count($productos) > 0): ?>
        <?php foreach ($productos as $producto): ?>
          <div class="col-sm-6 col-lg-4">
            <div class="card h-100">
              <!-- Imagen del producto -->
              <img src="https://via.placeholder.com/300x200/007bff/ffffff?text=Producto" 
                   class="card-img-top" alt="<?php echo htmlspecialchars($producto['Nombre_p']); ?>">
              <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($producto['Nombre_p']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($producto['Descripcion']); ?></p>
                <p class="card-text">
                  <strong>Categoría:</strong> <?php echo htmlspecialchars($producto['Categoria']); ?><br>
                  <strong>Precio:</strong> $<?php echo number_format($producto['Precio'], 2); ?><br>
                  <strong>Stock:</strong> 
                  <span class="<?php echo $producto['Stock'] < 10 ? 'text-danger fw-bold' : 'text-success'; ?>">
                    <?php echo $producto['Stock']; ?> unidades
                  </span>
                </p>
                <div class="d-flex gap-2">
                  <a class="btn btn-outline-primary btn-sm" href="#">
                    <i class="fas fa-info-circle"></i> Detalles
                  </a>
                  <?php if ($id_rol == 1 || $id_rol == 2): ?>
                    <a class="btn btn-outline-warning btn-sm" href="#">
                      <i class="fas fa-edit"></i> Editar
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center">
          <div class="alert alert-info">
            <h4><i class="fas fa-info-circle"></i> No hay productos registrados</h4>
            <p>Comienza agregando algunos productos al sistema.</p>
            <?php if ($id_rol == 1 || $id_rol == 2): ?>
              <a href="registro.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Agregar Primer Producto
              </a>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>


  <!-- FOOTER -->
  <footer class="bg-dark text-white text-center py-3">
    <div class="container">
      <small>
        OSKAR HERNANDEZ ADRIANA GONZALEZ HEIDY GARCIA Grupo 4A</small>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

