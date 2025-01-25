<?php
$conn = new mysqli("localhost", "root", "", "albums");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomusuario = trim(mysqli_real_escape_string($conn, $_POST['nomusuario']));
    $ape = trim(mysqli_real_escape_string($conn, $_POST['ape'])); 
    $contraseña = trim($_POST['contraseña']);
    $tipousuario = $_POST['tipousuario']; 

    $query = "SELECT * FROM usuario WHERE nomusuario = '$nomusuario'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $error = "El nombre de usuario ya está en uso.";
    } else {
        $contraseña_cifrada = password_hash($contraseña, PASSWORD_DEFAULT);

        $insert_query = "INSERT INTO usuario (nomusuario, ape, contraseña, tipousuario) 
                         VALUES ('$nomusuario', '$ape', '$contraseña_cifrada', '$tipousuario')";
        
        if ($conn->query($insert_query) === TRUE) {
            echo "<p>¡Usuario creado exitosamente! Ahora puedes iniciar sesión.</p>";
        } else {
            $error = "Error al crear el usuario: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <p class="title" >Crear Usuario</p>
        <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        <form method="POST" action="">
            <p class="title" >Nombre de Usuario</p>
            <input type="text" id="nomusuario" name="nomusuario" required>
            
            <p class="title" >Apellido</p>
            <input type="text" id="ape" name="ape" required>
            
            <p class="title" >Contraseña</p>
            <input type="password" id="contraseña" name="contraseña" required>
            
            <p class="title" >Tipo de Usuario</p>
            <select name="tipousuario" id="tipousuario" required>
                <option value="admin">Administrador</option>
                <option value="usuario">Usuario</option>
            </select>
            
            <button type="submit">Crear Cuenta</button>
        </form>
    </div>
</body>
</html>
