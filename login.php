<?php
$conn = new mysqli("localhost", "root", "", "albums");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomusuario = trim(mysqli_real_escape_string($conn, $_POST['nomusuario']));
    $contraseña = trim($_POST['contraseña']);

    $query = "SELECT * FROM usuario WHERE nomusuario = '$nomusuario'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($contraseña, $user['contraseña'])) {
            session_start();
            $_SESSION['id_usuario'] = $user['id_usuario'];
            $_SESSION['nomusuario'] = $user['nomusuario'];
            $_SESSION['tipousuario'] = $user['tipousuario'];

            echo "<p>¡Inicio de sesión exitoso! Bienvenido, " . htmlspecialchars($user['nomusuario']) . ".</p>";
            header("Location: inicio.php");
            exit();
        } else {
            $error = "La contraseña es incorrecta.";
        }
    } else {
        $error = "El usuario no existe.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <p class="title" >Iniciar Sesión</p>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        <form method="POST" action="">
        <p class="title" >Nombre de Usuario</p>
            <input type="text" id="nomusuario" name="nomusuario" required>
            
            <p class="title" >Contraseña</p>
            <input type="password" id="contraseña" name="contraseña" required>
            
            <button type="submit">Iniciar Sesión</button>
        </form>

            <a href="createuser.php">
            <p class="title" >Crear usuario</p>
            </a>

    </div>
</body>
</html>
