<?php
include_once 'config.php'; // Incluir el archivo de configuración al principio
session_start(); // Iniciar sesión

include_once 'login_helper.php'; // Incluir el archivo con las funciones de autenticación

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirigir al usuario a la página de login si no está autenticado
    exit;
}

// Verificar si el usuario es administrador
if (!$_SESSION['es_admin']) {
    // Si no es administrador, mostrar solo la tabla de archivos
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="index.css?v=<?php echo time(); ?>">
        <title>[Práctica 06] File Manager</title>
    </head>
    <body>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nombre del archivo</th>
                        <th>Tamaño del archivo (KB)</th>
                        <th>Ver archivo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($archivos as $archivo) : ?>
                        <tr>
                            <td><a href="archivo.php?nombre=<?php echo $archivo; ?>" target="_blank"><?php echo $archivo; ?></a></td>
                            <td><?php echo round(filesize(DIR_UPLOAD . $archivo) / 1024, 2); ?></td>
                            <td><a href="archivo.php?nombre=<?php echo $archivo; ?>" target="_blank">Ver</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="cerrar-sesion">
            <form action="logout.php" method="post">
                <button type="submit">Cerrar sesión</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}
// Eliminar archivo si se ha enviado un formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $archivoABorrar = $_POST["delete"];
    $rutaArchivoABorrar = DIR_UPLOAD . $archivoABorrar;
    if (file_exists($rutaArchivoABorrar)) {
        unlink($rutaArchivoABorrar);
    }
    // Redireccionar para evitar envío de formulario repetido
    header("Location: index.php");
    exit();
}

// Subir archivo si se ha enviado un formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $archivo = $_FILES["file"];
    $nombreArchivo = isset($_POST["nombre"]) ? $_POST["nombre"] : pathinfo($archivo["name"], PATHINFO_FILENAME);

    // Validar extensión de archivo
    $extensionesValidas = array("jpg", "jpeg", "png", "gif", "pdf");
    $extension = pathinfo($archivo["name"], PATHINFO_EXTENSION);
    if (!in_array($extension, $extensionesValidas)) {
        echo "Error: Solo se permiten archivos JPG, JPEG, PNG, GIF y PDF.";
        exit();
    }

    // Si no se proporciona un nombre, utilizar el nombre original del archivo sin la extensión
    if (empty($nombreArchivo)) {
        $nombreArchivo = pathinfo($archivo["name"], PATHINFO_FILENAME);
    }

    // Mover archivo a directorio de archivos subidos
    $rutaArchivo = DIR_UPLOAD . $nombreArchivo . "." . $extension;
    if (!move_uploaded_file($archivo["tmp_name"], $rutaArchivo)) {
        echo "Error al subir el archivo.";
        exit();
    }

    // Redireccionar para evitar envío de formulario repetido
    header("Location: index.php");
    exit();
}

// Listar archivos en la tabla
$archivos = array();
if ($handle = opendir(DIR_UPLOAD)) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            $archivos[] = $entry;
        }
    }
    closedir($handle);
}
// Si es administrador, continuar con el resto del código
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="index.css?v=<?php echo time(); ?>">
    <title>[Práctica 06] File Manager</title>
    <script>
        function confirmarBorrado(nombreArchivo) {
            return confirm(`¿Está seguro que desea borrar ${nombreArchivo}?`);
        }
    </script>
</head>
<body>
    <div class="table-container">
        <div class="formulario-subir">
            <form method="post" enctype="multipart/form-data" id="subir-archivo-form">
                <label for="nombre">Nombre del archivo:</label>
                <input type="text" id="nombre" name="nombre">
                <label for="file">Seleccionar archivo:</label>
                <input type="file" name="file">
                <button type="submit">Subir archivo</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nombre del archivo</th>
                    <th>Tamaño del archivo (KB)</th>
                    <th>Ver archivo</th>
                    <th>Borrar archivo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($archivos as $archivo) : ?>
                    <tr>
                        <td><a href="archivo.php?nombre=<?php echo $archivo; ?>" target="_blank"><?php echo $archivo; ?></a></td>
                        <td><?php echo round(filesize(DIR_UPLOAD . $archivo) / 1024, 2); ?></td>
                        <td><a href="archivo.php?nombre=<?php echo $archivo; ?>" target="_blank">Ver</a></td>
                        <td>
                            <form method="post" onsubmit="return confirmarBorrado('<?php echo $archivo; ?>')">
                                <input type="hidden" name="delete" value="<?php echo $archivo; ?>">
                                <button type="submit">Borrar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="cerrar-sesion">
        <form action="logout.php" method="post">
            <button type="submit">Cerrar sesión</button>
        </form>
    </div>
</body>
</html>
