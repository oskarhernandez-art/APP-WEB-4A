<?php
session_start();
// Solo administradores y operativos pueden acceder
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || ($_SESSION['id_rol'] != 1 && $_SESSION['id_rol'] != 2)) {
    header("Location: index.php?error=2");
    exit;
}

$nombre = $_SESSION['nombre_completo'] ?? 'Usuario';
$rol = $_SESSION['rol'] ?? 'Sin rol';
$id_rol = $_SESSION['id_rol'] ?? 3;

include 'config/database.php';

// Procesar el formulario cuando se envía
$mensaje = "";
$mensaje_tipo = ""; // success, danger, etc.

if ($_POST && isset($_POST['guardar'])) {
    $database = new Database();
    $db = $database->getConnection();
    
    // Obtener datos del formulario
    $nombre_producto = trim($_POST['nombre_producto']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);
    $id_categoria = intval($_POST['id_categoria']);

    // Procesar archivo (si se sube)
    $archivo = null;
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
        $archivo = file_get_contents($_FILES['archivo']['tmp_name']);
    }
    
    try {
        // Insertar producto en la base de datos con archivo
        $query = "INSERT INTO productos (Nombre_p, Descripcion, Precio, Stock, Id_categoria, archivo) 
                  VALUES (:nombre, :descripcion, :precio, :stock, :id_categoria, :archivo)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nombre', $nombre_producto);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':id_categoria', $id_categoria);
        $stmt->bindParam(':archivo', $archivo, PDO::PARAM_LOB);
        
        if ($stmt->execute()) {
            $mensaje = "✅ Producto guardado correctamente con archivo";
            $mensaje_tipo = "success";
            $_POST = array(); // limpiar formulario
        } else {
            $mensaje = "❌ Error al guardar el producto";
            $mensaje_tipo = "danger";
        }
    } catch (PDOException $e) {
        $mensaje = "❌ Error: " . $e->getMessage();
        $mensaje_tipo = "danger";
    }
}

// Obtener categorías para el select
$database = new Database();
$db = $database->getConnection();
$query_categorias = "SELECT Id_categoria, Nombre_c FROM Categorias ORDER BY Nombre_c";
$stmt_categorias = $db->prepare($query_categorias);
$stmt_categorias->execute();
$categorias = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);
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

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-custom bg-custom">
<div class="container">
    <a class="navbar-brand fw-bold" href="catalogo.php" style="color: white;">DON CLORO</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="menu" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="catalogo.php" style="color: white;">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="catalogo.php" style="color: white;">Catálogo</a></li>
        <li class="nav-item"><a class="nav-link" href="registro.php" style="color: white;">Registrar</a></li>
        <li class="nav-item">
          <span class="nav-link" style="color: #ffd700;">
            <i class="fas fa-user"></i> <?php echo $nombre; ?>
            <small class="badge bg-success"><?php echo $rol; ?></small>
          </span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php" style="color: #ff6b6b;">
            <i class="fas fa-sign-out-alt"></i> Salir
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- REGISTRO DE PRODUCTO -->
<section id="portfolio" class="bg-light py-5">
  <div class="container">
    <h2 class="mb-4 text-center">Registro de Productos</h2>
    
    <?php if (!empty($mensaje)): ?>
      <div class="alert alert-<?php echo $mensaje_tipo; ?> alert-dismissible fade show" role="alert">
        <?php echo $mensaje; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <div class="d-flex justify-content-center">
      <div class="card" style="width: 50rem;">
        <div class="card-body">
          <!-- FORMULARIO CON SUBIDA DE ARCHIVO -->
          <form method="POST" action="" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="nombre_producto" class="form-label fs-6 fw-bold">Nombre del producto:</label>
              <input type="text" id="nombre_producto" name="nombre_producto" class="form-control" 
                     value="<?php echo $_POST['nombre_producto'] ?? ''; ?>" required>
            </div>

            <div class="mb-3">
              <label for="descripcion" class="form-label fs-6 fw-bold">Descripción:</label>
              <textarea id="descripcion" name="descripcion" class="form-control" rows="3"><?php echo $_POST['descripcion'] ?? ''; ?></textarea>
            </div>

            <div class="mb-3">
              <label for="id_categoria" class="form-label fs-6 fw-bold">Categoría:</label>
              <select id="id_categoria" name="id_categoria" class="form-select" required>
                <option value="">Selecciona una categoría</option>
                <?php foreach ($categorias as $categoria): ?>
                  <option value="<?php echo $categoria['Id_categoria']; ?>" 
                    <?php echo (isset($_POST['id_categoria']) && $_POST['id_categoria'] == $categoria['Id_categoria']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($categoria['Nombre_c']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="row">
              <div class="col-md-6">
                <label for="precio" class="form-label fs-6 fw-bold">Precio ($):</label>
                <input type="number" id="precio" name="precio" class="form-control" step="0.01" min="0" value="<?php echo $_POST['precio'] ?? ''; ?>" required>
              </div>
              <div class="col-md-6">
                <label for="stock" class="form-label fs-6 fw-bold">Stock inicial:</label>
                <input type="number" id="stock" name="stock" class="form-control" min="0" value="<?php echo $_POST['stock'] ?? ''; ?>" required>
              </div>
            </div>

            <div class="mb-3 mt-3">
              <label for="archivo" class="form-label fs-6 fw-bold">Archivo (imagen o PDF):</label>
              <input type="file" id="archivo" name="archivo" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
            </div>

            <div class="d-flex gap-2 mt-4">
              <button type="submit" name="guardar" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar Producto
              </button>
              <a href="catalogo.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Catálogo
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<footer class="bg-dark text-white text-center py-3">
  <div class="container">
    <small>©️ 2025 | Sitio Proyecto Yves – Grupo 4A</small>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>