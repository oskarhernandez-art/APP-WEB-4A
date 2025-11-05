<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php?error=2");
    exit;
}

$nombre = $_SESSION['nombre_completo'] ?? 'Usuario';
$rol = $_SESSION['rol'] ?? 'Sin rol';
$id_rol = $_SESSION['id_rol'] ?? 3;

include 'config/database.php';

// Obtener productos con stock bajo (menos de 10 unidades)
$database = new Database();
$db = $database->getConnection();

$query = "SELECT p.*, c.Nombre_c as Categoria 
          FROM Productos p 
          LEFT JOIN Categorias c ON p.Id_categoria = c.Id_categoria 
          WHERE p.Stock < 10 
          ORDER BY p.Stock ASC";
$stmt = $db->prepare($query);
$stmt->execute();
$productos_alerta = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Contar total de alertas para el badge
$query_count = "SELECT COUNT(*) as total FROM Productos WHERE Stock < 10";
$stmt_count = $db->prepare($query_count);
$stmt_count->execute();
$total_alertas = $stmt_count->fetch(PDO::FETCH_ASSOC);
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
    background-color: #1c054a; 
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
          <a class="nav-link" href="catalogo.php" style="color: white;">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="catalogo.php" style="color: white;">Catálogo</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="registro.php" style="color: white;">Registrar</a>
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
          <span class="nav-link" style="color: #6257ffff;">
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

<!-- ALERTAS DE STOCK BAJO -->
<section id="catalogo" class="bg-light py-5">
  <div class="container">
    <h2 class="mb-4 text-center">
      <i class="fas fa-exclamation-triangle text-warning"></i> 
      Alertas de Stock
    </h2>

    <!-- RESUMEN DE ALERTAS -->
    <div class="row mb-4">
      <div class="col-md-6 mx-auto">
        <div class="card <?php echo $total_alertas['total'] > 0 ? 'border-warning' : 'border-success'; ?>">
          <div class="card-body text-center">
            <h5 class="card-title">
              <?php if ($total_alertas['total'] > 0): ?>
                <i class="fas fa-exclamation-circle text-warning"></i>
                ¡Atención! Tienes <?php echo $total_alertas['total']; ?> alertas de stock
              <?php else: ?>
                <i class="fas fa-check-circle text-success"></i>
                ¡Excelente! No hay alertas de stock
              <?php endif; ?>
            </h5>
            <p class="card-text">
              Productos con menos de 10 unidades en inventario
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 g-4">
      <?php if (count($productos_alerta) > 0): ?>
        <?php foreach ($productos_alerta as $producto): ?>
          <div class="col">
            <div class="card border-warning h-100">
              <div class="card-header bg-warning text-dark">
                <strong><i class="fas fa-exclamation-triangle"></i> STOCK BAJO</strong>
              </div>
              <div class="row g-0 align-items-center">
                <div class="col-4 text-center p-2">
                  <img src="https://via.placeholder.com/150x150/ffc107/ffffff?text=ALERTA" 
                       class="img-fluid rounded" 
                       alt="<?php echo htmlspecialchars($producto['Nombre_p']); ?>"
                       style="max-height: 100px; object-fit: contain;">
                </div>
                <div class="col-8">
                  <div class="card-body">
                    <h5 class="card-title text-danger"><?php echo htmlspecialchars($producto['Nombre_p']); ?></h5>
                    <p class="card-text">
                      <strong>Categoría:</strong> <?php echo htmlspecialchars($producto['Categoria']); ?><br>
                      <strong>Precio:</strong> $<?php echo number_format($producto['Precio'], 2); ?><br>
                      <strong>Stock actual:</strong> 
                      <span class="badge bg-danger"><?php echo $producto['Stock']; ?> unidades</span>
                    </p>
                    <div class="d-flex gap-2">
                      <a href="catalogo.php" class="btn btn-warning btn-sm">
                        <i class="fas fa-boxes"></i> Ver en Catálogo
                      </a>
                      <?php if ($id_rol == 1 || $id_rol == 2): ?>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                          <i class="fas fa-edit"></i> Reabastecer
                        </a>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center">
          <div class="alert alert-success">
            <h4><i class="fas fa-check-circle"></i> ¡Todo en orden!</h4>
            <p>No hay productos con stock bajo. Todos tienen 10 o más unidades.</p>
            <a href="catalogo.php" class="btn btn-success">
              <i class="fas fa-arrow-left"></i> Volver al Catálogo
            </a>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

      <!-- Card 1 -->
      <div class="col">
        <div class="row row-cols-1 row-cols-md-2 g-4"> <!--agregado desde estructuras antiguo archivo -->
        <div class="card border-0 shadow-sm h-100">
        <div class="col-sm-6 col-lg-6"> <!--prueba de columnas divididas -->
          <div class="row g-0 align-items-center">
            <div class="col-4 text-center p-2">
              <img src="img/detergenteropa.jpeg" class="img-fluid" alt="Jabón para ropa" style="max-height: 100px; object-fit: contain;">
            </div>
            <div class="col-8">
              <div class="card-body">
                <h5 class="card-title mb-1">Jabón para ropa</h5>
                <p class="card-text mb-2">Stock: <span id="stock-1">0</span></p>
                <div class="d-flex gap-2 mb-2">
                  <button class="btn btn-outline-secondary btn-sm" onclick="decrementStock('stock-1')">–</button>
                  <button class="btn btn-outline-secondary btn-sm" onclick="incrementStock('stock-1')">+</button>
                </div>
                <a class="btn btn-primary btn-sm" href="#">Agregar</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col">
        <div class="card border-0 shadow-sm h-100">
          <div class="row g-0 align-items-center">
            <div class="col-4 text-center p-2">
              <img src="img/jabonbaño.jpeg" class="img-fluid" alt="Jabón para baños" style="max-height: 100px; object-fit: contain;">
            </div>
            <div class="col-8">
              <div class="card-body">
                <h5 class="card-title mb-1">Jabón de baño</h5>
                <p class="card-text mb-2">Stock: <span id="stock-2">0</span></p>
                <div class="d-flex gap-2 mb-2">
                  <button class="btn btn-outline-secondary btn-sm" onclick="decrementStock('stock-2')">–</button>
                  <button class="btn btn-outline-secondary btn-sm" onclick="incrementStock('stock-2')">+</button>
                </div>
                <a class="btn btn-primary btn-sm" href="#">Agregar</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="col">
        <div class="card border-0 shadow-sm h-100">
          <div class="row g-0 align-items-center">
            <div class="col-4 text-center p-2">
              <img src="img/jaboncocina.jpeg" class="img-fluid" alt="Jabones para cocina" style="max-height: 100px; object-fit: contain;">
            </div>
            <div class="col-8">
              <div class="card-body">
                <h5 class="card-title mb-1">Jabones para cocina</h5>
                <p class="card-text mb-2">Stock: <span id="stock-3">0</span></p>
                <div class="d-flex gap-2 mb-2">
                  <button class="btn btn-outline-secondary btn-sm" onclick="decrementStock('stock-3')">–</button>
                  <button class="btn btn-outline-secondary btn-sm" onclick="incrementStock('stock-3')">+</button>
                </div>
                <a class="btn btn-primary btn-sm" href="#">Agregar</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 4 -->
      <div class="col">
        <div class="card border-0 shadow-sm h-100">
          <div class="row g-0 align-items-left">
            <div class="col-4 text-center p-2">
              <img src="img/desengrasantes.jpeg" class="img-fluid" alt="Desengrasantes" style="max-height: 100px; object-fit: contain;">
            </div>
            <div class="col-8">
              <div class="card-body">
                <h5 class="card-title mb-1">Desengrasantes</h5>
                <p class="card-text mb-2">Stock: <span id="stock-4">0</span></p>
                <div class="d-flex gap-2 mb-2">
                  <button class="btn btn-outline-secondary btn-sm" onclick="decrementStock('stock-4')">–</button>
                  <button class="btn btn-outline-secondary btn-sm" onclick="incrementStock('stock-4')">+</button>
                </div>
                <a class="btn btn-primary btn-sm" href="#">Agregar</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 5 -->
      <div class="col">
        <div class="card border-0 shadow-sm h-100">
          <div class="row g-0 align-items-center">
            <div class="col-4 text-center p-2">
              <img src="img/parapisos.jpeg" class="img-fluid" alt="Jabones para pisos" style="max-height: 100px; object-fit: contain;">
            </div>
            <div class="col-8">
              <div class="card-body">
                <h5 class="card-title mb-1">Jabones para pisos</h5>
                <p class="card-text mb-2">Stock: <span id="stock-5">0</span></p>
                <div class="d-flex gap-2 mb-2">
                  <button class="btn btn-outline-secondary btn-sm" onclick="decrementStock('stock-5')">–</button>
                  <button class="btn btn-outline-secondary btn-sm" onclick="incrementStock('stock-5')">+</button>
                </div>
                <a class="btn btn-primary btn-sm" href="#">Agregar</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 6 -->
      <div class="col">
        <div class="card border-0 shadow-sm h-100">
          <div class="row g-0 align-items-center">
            <div class="col-4 text-center p-2">
              <img src="img/artlimpieza.jpeg" class="img-fluid" alt="Artículos de limpieza" style="max-height: 100px; object-fit: contain;">
            </div>
            <div class="col-8">
              <div class="card-body">
                <h5 class="card-title mb-1">Artículos de limpieza</h5>
                <p class="card-text mb-2">Stock: <span id="stock-6">0</span></p>
                <div class="d-flex gap-2 mb-2">
                  <button class="btn btn-outline-secondary btn-sm" onclick="decrementStock('stock-6')">–</button>
                  <button class="btn btn-outline-secondary btn-sm" onclick="incrementStock('stock-6')">+</button>
                </div>
                <a class="btn btn-primary btn-sm" href="#">Agregar</a>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

   <!-- FOOTER -->
  <footer class="bg-dark text-white text-center py-3">
    <div class="container">
      <small>© 2025 | Sitio Proyecto ANADS – Grupo 4A</small>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!--script para que funcionen los botones de + y - -->
<script>
  function incrementStock(id) {
    const stock = document.getElementById(id);
    stock.textContent = parseInt(stock.textContent) + 1;
  }

  function decrementStock(id) {
    const stock = document.getElementById(id);
    const current = parseInt(stock.textContent);
    if (current > 0) stock.textContent = current - 1;
  }
</script>

</body>
</html>
