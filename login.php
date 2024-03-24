<?php
session_start(); // Iniciar sesión

include_once 'login_helper.php'; // Incluir el archivo con las funciones de autenticación

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el username y password del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Intentar autenticar al usuario
    $user = autentificar($username, $password);

    // Verificar si la autenticación fue exitosa
    if ($user) {
        // Guardar datos del usuario en la sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['es_admin'] = $user['esAdmin'];

        // Redirigir a la página principal
        header("Location: index.php");
        exit;
    } else {
        // Mostrar mensaje de error
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>[Práctica 06] File Manager</title>
    <link rel="stylesheet" href="login.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
</head>
<body>
<div class="container">
    <div class="wrapper">
        <div class="title"><span>Iniciar Sesión</span></div>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="row">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Usuario" required>
            </div>
            <div class="row">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Contraseña" required>
            </div>
            <?php if(isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <div class="row button">
                <input type="submit" value="Entrar">
            </div>
        </form>
    </div>
</div>
</body>
</html>