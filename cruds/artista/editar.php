<?php
$conn = new mysqli("localhost", "root", "", "albums");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id_artista = $_GET['id'];

    // Obtener los detalles del artista
    $sql_artista = "SELECT * FROM artista WHERE id_artista = $id_artista";
    $result_artista = $conn->query($sql_artista);

    if ($result_artista->num_rows > 0) {
        $artista = $result_artista->fetch_assoc();
    } else {
        echo "<p>El artista no existe.</p>";
        exit;
    }

    $sql_paises = "SELECT id_pais, nombrepais FROM pais";
    $result_paises = $conn->query($sql_paises);

    $sql_generos = "SELECT id_genero, nombregenero FROM genero";
    $result_generos = $conn->query($sql_generos);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_artista'])) {
    $id_artista = $_POST['id_artista'];
    $nombre = $_POST['nombre'];
    $id_pais = $_POST['id_pais'] ?: NULL;
    $genero = $_POST['id_genero'];
    $biografia = $_POST['bio'];

    // Actualizar los datos del artista
    $sql_update = "UPDATE artista SET 
                    nombre = '$nombre',
                    id_pais = " . ($id_pais === NULL ? "NULL" : "'$id_pais'") . ",
                    id_genero = '$genero',
                    bio = '$biografia'
                   WHERE id_artista = $id_artista";

    if ($conn->query($sql_update) === TRUE) {
        echo "<p class='success'>Artista actualizado correctamente.</p>";
    } else {
        echo "<p class='error'>Error al actualizar el artista: " . $conn->error . "</p>";
    }

    $conn->close();
    exit;
} else {
    echo "<p>Solicitud no válida.</p>";
    exit;
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <p class="title" >Editar Artista</p>
    <link rel="stylesheet" href="http://localhost/albums/cruds/artista/artista.css">
</head>
<body>
    <form action="editar.php" method="POST">
        <input type="hidden" name="id_artista" value="<?php echo $artista['id_artista']; ?>">

        <div>
            <p class="title" >Nombre</p>
            <input type="text" id="nombre" name="nombre" value="<?php echo $artista['nombre']; ?>" required>
        </div>

        <div>
            <p class="title" >Pais</p>
            <select id="id_pais" name="id_pais">
                <option value="" <?php echo is_null($artista['id_pais']) ? 'selected' : ''; ?>>Sin seleccionar</option>
                <?php while ($row = $result_paises->fetch_assoc()) { ?>
                    <option value="<?php echo $row['id_pais']; ?>" <?php echo ($artista['id_pais'] == $row['id_pais']) ? 'selected' : ''; ?>>
                        <?php echo $row['nombrepais']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div>
            <p class="title" >Género Musical</p>
            <select id="id_genero" name="id_genero">
                <?php while ($row = $result_generos->fetch_assoc()) { ?>
                    <option value="<?php echo $row['id_genero']; ?>" <?php echo ($artista['id_genero'] == $row['id_genero']) ? 'selected' : ''; ?>>
                        <?php echo $row['nombregenero']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div>
            <p class="title" >Biografía</p>
            <textarea id="bio" name="bio"><?php echo $artista['bio']; ?></textarea>
        </div>

        <button type="submit">Guardar Cambios</button>
    </form>
</body>
</html>
