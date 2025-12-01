# README: Sistema de Gestión Web PHP 

Este documento contiene las instrucciones necesarias para instalar y ejecutar el proyecto de gestión web construido en PHP y MySQL.

##  Requisitos del Sistema

* Servidor Web: Apache o Nginx (Recomendado XAMPP, WAMP o MAMP).
* PHP: Versión 7.x o superior.
* Base de Datos: MySQL o MariaDB.

##  Pasos de Instalación y Configuración

### Paso 1: Archivos del Proyecto

1.  Coloca la carpeta del proyecto (descomprimida del archivo .zip) dentro del directorio raíz de tu servidor web (ej: 'htdocs' en XAMPP).
2.  La URL de acceso será: http://localhost/[nombre_carpeta]/

### Paso 2: Configuración de la Base de Datos

1.  Inicia los servicios de Apache y MySQL.
2.  Accede a phpMyAdmin (http://localhost/phpmyadmin).
3.  Crea una nueva base de datos.
    * Nombre Sugerido: `gestion_db` (o el que uses en tu conexión).
4.  Selecciona la base de datos creada y ve a la pestaña **"Importar"**.
5.  Sube y ejecuta el archivo **`if0_40466430_doncloro.sql`** para crear toda la estructura de tablas (vacías).

### Paso 3: Configuración de la Conexión PHP

1.  Abre el archivo de conexión a la base de datos (generalmente llamado `conexion.php` o similar) que se encuentra en el proyecto.
2.  Modifica las siguientes variables para que coincidan con tu configuración local de MySQL:

    ```php
    // EJEMPLO DE CONFIGURACIÓN LOCAL
    $host = "localhost";
    $user = "root";       // Usuario de tu MySQL
    $pass = "";           // Contraseña de tu MySQL (a menudo vacía)
    $db = "gestion_db";   // Nombre de la base de datos
    ```

### Paso 4: Ejecución y Primer Acceso

1.  Abre tu navegador y ve a la URL del proyecto (ej: http://localhost/[nombre_carpeta]/).
2.  El sistema cargará la página de inicio o login.
3.  **Primer Registro:** Como las tablas están vacías, debes usar la página de registro de usuarios para crear tu primera cuenta de administrador:
    * Accede a: http://localhost/[nombre_carpeta]/registro_usuario.php
    * Crea tu usuario y contraseña.
4.  Una vez registrado, inicia sesión para acceder al panel principal (`main.php`).

   ## ☁️ Instrucciones de Subida a InfinityFree (Hosting)

Si deseas subir el proyecto a InfinityFree (o un servicio similar), los siguientes pasos son **OBLIGATORIOS** para actualizar la conexión.

### Paso A: Subida de Base de Datos

1.  Accede al Panel de Control de InfinityFree y encuentra la sección **MySQL Databases**.
2.  Crea una nueva base de datos. InfinityFree te asignará un **Host**, **Nombre de BD**, **Usuario** y **Contraseña** específicos (que no son `localhost`/`root`).
3.  Accede a **phpMyAdmin** desde el Panel de InfinityFree.
4.  Selecciona la base de datos recién creada y **Importa el archivo `if0_40466430_doncloro.sql`**.

### Paso B: Actualización de la Conexión PHP (Hosting)

1.  Abre el archivo de conexión a la base de datos (`conexion.php`).
2.  **¡Es vital reemplazar las variables locales por las de InfinityFree!**

    ```php
    // CONFIGURACIÓN PARA HOSTING (EJEMPLO DE INFINITYFREE)
    $host = "sqlxxx.epizy.com"; // Tu Host SQL Único
    $user = "if0_40466430";     // Tu Usuario de Base de Datos Único
    $pass = "TuContraseñaSQL";  // La contraseña que te asignó el hosting
    $db = "if0_40466430_doncloro"; // El Nombre de la BD Único
    ```
3.  Sube todos los archivos de tu proyecto (incluyendo la carpeta `img` y los archivos PHP actualizados) al directorio **`htdocs`** de tu cuenta de hosting.

### Paso C: Acceso Final

Accede a tu dominio (`http://[TuDominio].com/`) para verificar que el sitio esté funcionando correctamente.
