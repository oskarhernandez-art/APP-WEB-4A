<?php
session_start();
include 'config/database.php';

if ($_POST) {
    $database = new Database();
    $db = $database->getConnection();
    
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $tipo_usuario = $_POST['tipo_usuario'];
    
    // Buscar usuario con su rol
    $query = "SELECT u.Id_usuario, u.Nombre_usuario, u.Contraseña, 
                     u.Nombre_completo, r.Nombre_rol, r.Id_rol
              FROM Usuarios u 
              INNER JOIN Roles r ON u.Id_rol = r.Id_rol 
              WHERE u.Nombre_usuario = :username AND u.Activo = 1";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($password === $row['Contraseña']) {
            // ✅ INICIAR SESIÓN CON TODOS LOS DATOS
            $_SESSION['user_id'] = $row['Id_usuario'];
            $_SESSION['username'] = $row['Nombre_usuario'];
            $_SESSION['nombre_completo'] = $row['Nombre_completo'];
            $_SESSION['rol'] = $row['Nombre_rol'];           // ← ESTA FALTABA
            $_SESSION['id_rol'] = $row['Id_rol'];            // ← ESTA FALTABA
            $_SESSION['loggedin'] = true;
            
            header("Location: catalogo.php");  // ← Redirigir al catálogo
            exit;
        }
    }
    
    header("Location: index.php?error=1");
    exit;
} else {
    header("Location: index.php");
    exit;
}
?>